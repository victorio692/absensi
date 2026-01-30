<?php
namespace App\Controllers\Api;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
class AuthController extends Controller {
    use ResponseTrait;
    protected $userModel;
    public function __construct() { $this->userModel = new UserModel(); }
    public function loginSiswa() {
        try {
            $json = $this->request->getJSON(true);
            $email = $json['email'] ?? null;
            $password = $json['password'] ?? null;
            if (!$email || !$password) return $this->response->setJSON(['success'=>false,'message'=>'Email dan password harus diisi'])->setStatusCode(400);
            $user = $this->userModel->where('email',$email)->first();
            if (!$user) return $this->response->setJSON(['success'=>false,'message'=>'Email atau password salah'])->setStatusCode(401);
            if (!password_verify($password,$user['password'])) return $this->response->setJSON(['success'=>false,'message'=>'Email atau password salah'])->setStatusCode(401);
            session()->set(['user_id'=>$user['id'],'nama'=>$user['nama'],'email'=>$user['email'],'role'=>$user['role'],'is_logged_in'=>true]);
            return $this->response->setJSON(['success'=>true,'message'=>'Login berhasil','data'=>['user_id'=>$user['id'],'nama'=>$user['nama'],'email'=>$user['email'],'role'=>$user['role']]])->setStatusCode(200);
        } catch (\Exception $e) {
            log_message('error','Login Siswa Error: '.$e->getMessage());
            return $this->response->setJSON(['success'=>false,'message'=>'Terjadi kesalahan server'])->setStatusCode(500);
        }
    }
    public function loginAdmin() {
        try {
            $json = $this->request->getJSON(true);
            $email = $json['email'] ?? null;
            $password = $json['password'] ?? null;
            if (!$email || !$password) return $this->response->setJSON(['success'=>false,'message'=>'Email dan password harus diisi'])->setStatusCode(400);
            $user = $this->userModel->where('email',$email)->first();
            if (!$user) return $this->response->setJSON(['success'=>false,'message'=>'Email atau password salah'])->setStatusCode(401);
            if ($user['role']!=='admin') return $this->response->setJSON(['success'=>false,'message'=>'Hanya admin yang dapat login'])->setStatusCode(403);
            if (!password_verify($password,$user['password'])) return $this->response->setJSON(['success'=>false,'message'=>'Email atau password salah'])->setStatusCode(401);
            session()->set(['user_id'=>$user['id'],'nama'=>$user['nama'],'email'=>$user['email'],'role'=>$user['role'],'is_logged_in'=>true]);
            return $this->response->setJSON(['success'=>true,'message'=>'Login admin berhasil','data'=>['user_id'=>$user['id'],'nama'=>$user['nama'],'email'=>$user['email'],'role'=>$user['role']]])->setStatusCode(200);
        } catch (\Exception $e) {
            log_message('error','Login Admin Error: '.$e->getMessage());
            return $this->response->setJSON(['success'=>false,'message'=>'Terjadi kesalahan server'])->setStatusCode(500);
        }
    }
    public function logout() {
        try {
            session()->destroy();
            return $this->response->setJSON(['success'=>true,'message'=>'Logout berhasil'])->setStatusCode(200);
        } catch (\Exception $e) {
            log_message('error','Logout Error: '.$e->getMessage());
            return $this->response->setJSON(['success'=>false,'message'=>'Terjadi kesalahan server'])->setStatusCode(500);
        }
    }
}
