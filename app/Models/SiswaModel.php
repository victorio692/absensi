<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user_id', 'nisn', 'nis', 'kelas'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil data siswa dengan relasi user
     */
    public function getSiswaWithUser()
    {
        return $this->select('siswa.id, siswa.user_id, siswa.nisn, siswa.nis, siswa.kelas, siswa.created_at, siswa.updated_at, users.nama, users.username, users.password')
                    ->join('users', 'users.id = siswa.user_id')
                    ->findAll();
    }

    /**
     * Ambil detail siswa
     */
    public function getSiswaDetail($id)
    {
        return $this->select('siswa.id, siswa.user_id, siswa.nisn, siswa.nis, siswa.kelas, siswa.created_at, siswa.updated_at, users.nama, users.username')
                    ->join('users', 'users.id = siswa.user_id')
                    ->where('siswa.id', $id)
                    ->first();
    }

    /**
     * Cari siswa berdasarkan QR token
     */
    public function findByQRToken($qrToken)
    {
        return $this->where('qr_token', $qrToken)->first();
    }
}
