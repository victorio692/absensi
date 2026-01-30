<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\AbsensiModel;

class StudentController extends \CodeIgniter\Controller
{
    use ResponseTrait;

    protected $userModel;
    protected $absensiModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->absensiModel = new AbsensiModel();
    }

    public function dashboard()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Unauthorized: User not logged in'
            ])->setStatusCode(401);
        }

        // Get user data
        $user = $this->userModel->find($userId);

        if (!$user) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User not found'
            ])->setStatusCode(404);
        }

        // Get today's attendance status
        $today = date('Y-m-d');
        $todayAttendance = $this->absensiModel
            ->where('user_id', $userId)
            ->where('DATE(check_in_time)', $today)
            ->first();

        $todayStatus = null;
        if ($todayAttendance) {
            $todayStatus = [
                'check_in' => $todayAttendance['check_in_time'],
                'check_out' => $todayAttendance['check_out_time'],
                'status' => $todayAttendance['status']
            ];
        }

        // Get attendance statistics (last 30 days)
        $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));
        $statistics = $this->absensiModel
            ->selectCount('id', 'total')
            ->where('user_id', $userId)
            ->where('DATE(check_in_time) >=', $thirtyDaysAgo)
            ->groupBy('status')
            ->findAll();

        $stats = [
            'hadir' => 0,
            'terlambat' => 0,
            'izin' => 0,
            'sakit' => 0
        ];

        foreach ($statistics as $stat) {
            if (isset($stats[$stat['status']])) {
                $stats[$stat['status']] = $stat['total'];
            }
        }

        // Get attendance history (last 10 records)
        $history = $this->absensiModel
            ->where('user_id', $userId)
            ->orderBy('check_in_time', 'DESC')
            ->limit(10)
            ->findAll();

        return $this->response->setJSON([
            'status' => true,
            'data' => [
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'role' => $user['role']
                ],
                'today_status' => $todayStatus,
                'statistics' => $stats,
                'history' => $history
            ]
        ])->setStatusCode(200);
    }

    public function checkin()
    {
        $data = $this->request->getJSON(true);
        $userId = session()->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Unauthorized: User not logged in'
            ])->setStatusCode(401);
        }

        // Validate QR code
        if (empty($data['qr_code'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'QR code is required'
            ])->setStatusCode(400);
        }

        // Check if already checked in today
        $today = date('Y-m-d');
        $existingCheckin = $this->absensiModel
            ->where('user_id', $userId)
            ->where('DATE(check_in_time)', $today)
            ->first();

        if ($existingCheckin) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'You already checked in today'
            ])->setStatusCode(400);
        }

        // Create attendance record
        $attendanceData = [
            'user_id' => $userId,
            'qr_code' => $data['qr_code'],
            'check_in_time' => date('Y-m-d H:i:s'),
            'status' => 'hadir',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $attendanceId = $this->absensiModel->insert($attendanceData);

        if (!$attendanceId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to create attendance record'
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Check-in successful',
            'data' => [
                'attendance_id' => $attendanceId,
                'check_in_time' => $attendanceData['check_in_time']
            ]
        ])->setStatusCode(201);
    }

    public function checkout()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Unauthorized: User not logged in'
            ])->setStatusCode(401);
        }

        // Find today's check-in
        $today = date('Y-m-d');
        $attendance = $this->absensiModel
            ->where('user_id', $userId)
            ->where('DATE(check_in_time)', $today)
            ->first();

        if (!$attendance) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No check-in record found for today'
            ])->setStatusCode(404);
        }

        if ($attendance['check_out_time']) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'You already checked out today'
            ])->setStatusCode(400);
        }

        // Update checkout time
        $this->absensiModel->update($attendance['id'], [
            'check_out_time' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Check-out successful',
            'data' => [
                'attendance_id' => $attendance['id'],
                'check_out_time' => date('Y-m-d H:i:s')
            ]
        ])->setStatusCode(200);
    }
}
