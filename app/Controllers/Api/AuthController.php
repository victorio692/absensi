<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class AuthController extends \CodeIgniter\Controller
{
    use ResponseTrait;

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function loginSiswa()
    {
        $data = $this->request->getJSON(true);

        // Validate input
        if (empty($data['email']) || empty($data['password'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email and password are required'
            ])->setStatusCode(400);
        }

        // Debug: log table info
        log_message('info', 'UserModel table: ' . $this->userModel->getTable());
        log_message('info', 'Querying for email: ' . $data['email']);

        // Find user by email
        try {
            $user = $this->userModel->where('email', $data['email'])->first();
        } catch (\Exception $e) {
            log_message('error', 'Database query error: ' . $e->getMessage());
            log_message('error', 'SQL: ' . $e->__toString());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }

        if (!$user) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email or password is incorrect'
            ])->setStatusCode(401);
        }

        // Verify password
        if (!password_verify($data['password'], $user['password'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email or password is incorrect'
            ])->setStatusCode(401);
        }

        // Create session
        session()->set([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'nama' => $user['nama'],
            'role' => $user['role']
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'nama' => $user['nama'],
                'role' => $user['role']
            ]
        ])->setStatusCode(200);
    }

    public function loginAdmin()
    {
        $data = $this->request->getJSON(true);

        // Validate input
        if (empty($data['email']) || empty($data['password'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email and password are required'
            ])->setStatusCode(400);
        }

        // Find user by email
        try {
            $user = $this->userModel->where('email', $data['email'])->first();
        } catch (\Exception $e) {
            log_message('error', 'Database query error: ' . $e->getMessage());
            log_message('error', 'SQL: ' . $e->__toString());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }

        if (!$user) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email or password is incorrect'
            ])->setStatusCode(401);
        }

        // Verify password
        if (!password_verify($data['password'], $user['password'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email or password is incorrect'
            ])->setStatusCode(401);
        }

        // Check if user is admin
        if ($user['role'] !== 'admin') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Unauthorized: Admin access required'
            ])->setStatusCode(403);
        }

        // Create session
        session()->set([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'nama' => $user['nama'],
            'role' => $user['role']
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'nama' => $user['nama'],
                'role' => $user['role']
            ]
        ])->setStatusCode(200);
    }

    public function logout()
    {
        session()->destroy();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Logout successful'
        ])->setStatusCode(200);
    }
}
