<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use App\Models\AbsensiModel;

class AdminController extends \CodeIgniter\Controller
{
    use ResponseTrait;

    protected $absensiModel;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
    }

    public function dashboard()
    {
        $userId = session()->get('user_id');
        $userRole = session()->get('role');

        if (!$userId || $userRole !== 'admin') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Unauthorized: Admin access required'
            ])->setStatusCode(403);
        }

        // Get today's date
        $today = date('Y-m-d');

        // Count attendance by status for today
        $statistics = $this->absensiModel
            ->selectCount('id', 'count')
            ->where('DATE(check_in_time)', $today)
            ->groupBy('status')
            ->findAll();

        $stats = [
            'hadir' => 0,
            'terlambat' => 0,
            'izin' => 0,
            'sakit' => 0,
            'total' => 0
        ];

        foreach ($statistics as $stat) {
            if (isset($stats[$stat['status']])) {
                $stats[$stat['status']] = (int)$stat['count'];
                $stats['total'] += (int)$stat['count'];
            }
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => [
                'date' => $today,
                'statistics' => $stats
            ]
        ])->setStatusCode(200);
    }

    public function attendance()
    {
        $userId = session()->get('user_id');
        $userRole = session()->get('role');

        if (!$userId || $userRole !== 'admin') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Unauthorized: Admin access required'
            ])->setStatusCode(403);
        }

        $data = $this->request->getJSON(true);

        // Get query parameters
        $dateFrom = $data['date_from'] ?? date('Y-m-d', strtotime('-30 days'));
        $dateTo = $data['date_to'] ?? date('Y-m-d');
        $page = (int)($data['page'] ?? 1);
        $perPage = (int)($data['per_page'] ?? 10);

        // Validate dates
        if (!strtotime($dateFrom) || !strtotime($dateTo)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid date format'
            ])->setStatusCode(400);
        }

        // Calculate offset
        $offset = ($page - 1) * $perPage;

        // Get total count
        $totalCount = $this->absensiModel
            ->where('DATE(check_in_time) >=', $dateFrom)
            ->where('DATE(check_in_time) <=', $dateTo)
            ->countAllResults(false);

        // Get paginated data
        $records = $this->absensiModel
            ->where('DATE(check_in_time) >=', $dateFrom)
            ->where('DATE(check_in_time) <=', $dateTo)
            ->orderBy('check_in_time', 'DESC')
            ->limit($perPage, $offset)
            ->findAll();

        // Calculate pagination info
        $totalPages = ceil($totalCount / $perPage);

        return $this->response->setJSON([
            'status' => true,
            'data' => [
                'records' => $records,
                'pagination' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $totalCount,
                    'total_pages' => $totalPages
                ]
            ]
        ])->setStatusCode(200);
    }

    public function classes()
    {
        $userId = session()->get('user_id');
        $userRole = session()->get('role');

        if (!$userId || $userRole !== 'admin') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Unauthorized: Admin access required'
            ])->setStatusCode(403);
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => []
        ])->setStatusCode(200);
    }
}
