<?php

namespace App\Models;

use CodeIgniter\Model;

class QrLocationModel extends Model
{
    protected $table = 'qr_location';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nama_lokasi', 'keterangan', 'aktif'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $validationRules = [
        'nama_lokasi' => 'required|string|max_length[100]|is_unique[qr_location.nama_lokasi,id,{id}]',
        'aktif'       => 'required|in_list[0,1]',
    ];
    protected $validationMessages = [
        'nama_lokasi' => [
            'required'    => 'Nama lokasi harus diisi',
            'is_unique'   => 'Nama lokasi sudah terdaftar',
            'max_length'  => 'Nama lokasi maksimal 100 karakter',
        ],
        'aktif' => [
            'required' => 'Status aktif harus dipilih',
        ],
    ];

    /**
     * Ambil semua lokasi yang aktif
     */
    public function getAktif()
    {
        return $this->where('aktif', true)->findAll();
    }

    /**
     * Ambil lokasi dengan detail QR hari ini
     */
    public function getWithQrToday()
    {
        return $this->select('qr_location.*, qr_daily.token, qr_daily.tanggal')
                    ->join('qr_daily', 'qr_daily.location_id = qr_location.id AND qr_daily.tanggal = CURDATE()', 'left')
                    ->orderBy('qr_location.nama_lokasi', 'ASC')
                    ->findAll();
    }

    /**
     * Check apakah lokasi ada dan aktif
     */
    public function isActive($locationId)
    {
        return $this->where('id', $locationId)
                    ->where('aktif', true)
                    ->first();
    }
}
