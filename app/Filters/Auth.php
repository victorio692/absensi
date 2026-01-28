<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{
    /**
     * Cek session sebelum mengakses route yang dilindungi
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Pastikan session sudah diinisialisasi
        $session = session();
        
        if (!$session->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Cek role jika ada argument
        if (!empty($arguments)) {
            $userRole = $session->get('user_role');
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/')->with('error', 'Anda tidak memiliki akses ke halaman ini');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada yang perlu dilakukan setelah request
    }
}
