<?php

namespace App\Models;

use CodeIgniter\Model;

class IzinSakitModel extends Model
{
    protected $table = 'izin_sakit';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'siswa_id', 'tanggal', 'jenis', 'alasan', 'bukti_file', 
        'status', 'catatan_admin', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'siswa_id' => 'required|integer|is_not_empty',
        'tanggal' => 'required|valid_date',
        'jenis' => 'required|in_list[izin,sakit]',
        'alasan' => 'required|string|min_length[5]|max_length[500]',
        'bukti_file' => 'permit_empty|uploaded[bukti_file]|max_size[bukti_file,5120]|mime_in[bukti_file,image/jpeg,image/png,application/pdf]',
    ];

    /**
     * Get izin/sakit dengan informasi siswa
     */
    public function getWithSiswa($filters = [])
    {
        $builder = $this->select('izin_sakit.*, siswa.nama, siswa.kelas, siswa.nis')
            ->join('siswa', 'siswa.id = izin_sakit.siswa_id', 'left')
            ->orderBy('izin_sakit.tanggal', 'DESC')
            ->orderBy('izin_sakit.created_at', 'DESC');

        if (!empty($filters['siswa_id'])) {
            $builder->where('izin_sakit.siswa_id', $filters['siswa_id']);
        }
        if (!empty($filters['status'])) {
            $builder->where('izin_sakit.status', $filters['status']);
        }
        if (!empty($filters['jenis'])) {
            $builder->where('izin_sakit.jenis', $filters['jenis']);
        }
        if (!empty($filters['start_date'])) {
            $builder->where('izin_sakit.tanggal >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('izin_sakit.tanggal <=', $filters['end_date']);
        }

        return $builder;
    }

    /**
     * Cek apakah siswa sudah punya izin/sakit di tanggal tertentu
     */
    public function isAlreadySubmitted($siswa_id, $tanggal)
    {
        return $this->where('siswa_id', $siswa_id)
            ->where('tanggal', $tanggal)
            ->first();
    }

    /**
     * Get izin/sakit yang sudah disetujui untuk tanggal tertentu
     */
    public function getApprovedByDate($siswa_id, $tanggal)
    {
        return $this->where('siswa_id', $siswa_id)
            ->where('tanggal', $tanggal)
            ->where('status', 'approved')
            ->first();
    }

    /**
     * Get pending izin/sakit for admin
     */
    public function getPending()
    {
        return $this->getWithSiswa(['status' => 'pending'])->findAll();
    }

    /**
     * Approve izin/sakit
     */
    public function approve($id, $catatan = '')
    {
        return $this->update($id, [
            'status' => 'approved',
            'catatan_admin' => $catatan,
        ]);
    }

    /**
     * Reject izin/sakit
     */
    public function reject($id, $catatan = '')
    {
        return $this->update($id, [
            'status' => 'rejected',
            'catatan_admin' => $catatan,
        ]);
    }

    /**
     * Get siswa yang sudah submit izin/sakit untuk tanggal tertentu (approved)
     */
    public function getApprovedSiswaByDate($tanggal)
    {
        return $this->select('siswa_id, jenis')
            ->where('tanggal', $tanggal)
            ->where('status', 'approved')
            ->findAll();
    }
}
