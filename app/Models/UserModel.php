<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nama', 'email', 'password', 'role'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Validasi untuk create
     */
    public function setValidationRulesCreate()
    {
        $this->validationRules = [
            'nama'     => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'password' => 'required|min_length[6]|max_length[255]',
            'role'     => 'required|in_list[admin,siswa]',
        ];

        $this->validationMessages = [
            'nama'     => ['required' => 'Nama harus diisi', 'min_length' => 'Nama minimal 3 karakter'],
            'username' => ['required' => 'Username harus diisi', 'is_unique' => 'Username sudah digunakan'],
            'password' => ['required' => 'Password harus diisi', 'min_length' => 'Password minimal 6 karakter'],
            'role'     => ['required' => 'Role harus dipilih'],
        ];
    }

    /**
     * Cari user berdasarkan username untuk login
     */
    public function findByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Cari siswa berdasarkan NISN
     */
    public function findSiswaByNisn($nisn)
    {
        return $this->select('users.id, users.nama, users.username, users.password, users.role, siswa.id as siswa_id, siswa.nisn, siswa.nis')
                    ->join('siswa', 'siswa.user_id = users.id')
                    ->where('siswa.nisn', $nisn)
                    ->where('users.role', 'siswa')
                    ->first();
    }
}
