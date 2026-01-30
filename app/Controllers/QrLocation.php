<?php

namespace App\Controllers;

use App\Models\QrLocationModel;
use App\Models\QrDailyModel;

class QrLocation extends BaseController
{
    protected $qrLocationModel;
    protected $qrDailyModel;

    public function __construct()
    {
        $this->qrLocationModel = model(QrLocationModel::class);
        $this->qrDailyModel = model(QrDailyModel::class);
    }

    /**
     * Tampilkan daftar lokasi QR
     */
    public function index()
    {
        $data = [
            'title'      => 'Kelola Lokasi QR',
            'locations'  => $this->qrLocationModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/qr_location/index', $data);
    }

    /**
     * Form tambah lokasi
     */
    public function create()
    {
        $data = [
            'title'      => 'Tambah Lokasi QR',
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/qr_location/create', $data);
    }

    /**
     * Debug test form
     */
    public function debugTest()
    {
        $data = [
            'title' => 'Debug Test - Tambah Lokasi QR',
        ];

        return view('admin/qr_location/debug_test', $data);
    }

    /**
     * Form tambah lokasi - ORIGINAL
     */
    public function createOld()
    {
        $data = [
            'title'      => 'Tambah Lokasi QR',
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/qr_location/create', $data);
    }

    /**
     * Simpan lokasi baru
     */
    public function store()
    {
        $method = strtolower($this->request->getMethod());
        
        if ($method === 'post') {
            $nama_lokasi = trim($this->request->getPost('nama_lokasi') ?? '');
            $keterangan = trim($this->request->getPost('keterangan') ?? '');
            $aktif = $this->request->getPost('aktif') ? 1 : 0;

            try {
                // Insert directly using database query builder
                $db = \Config\Database::connect();
                $insertResult = $db->table('qr_location')->insert([
                    'nama_lokasi' => $nama_lokasi,
                    'keterangan' => $keterangan,
                    'aktif' => $aktif,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                if ($insertResult) {
                    session()->setFlashdata('success', 'Lokasi QR berhasil ditambahkan!');
                    return redirect()->to(base_url('admin/qr-location'));
                } else {
                    session()->setFlashdata('error', 'Gagal menyimpan data ke database');
                    return redirect()->back()->withInput();
                }
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Error: ' . $e->getMessage());
                return redirect()->back()->withInput();
            }
        }

        // Show create form
        $data = [
            'title'      => 'Tambah Lokasi QR',
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/qr_location/create', $data);
    }

    /**
     * Form edit lokasi
     */
    public function edit($id = null)
    {
        $location = $this->qrLocationModel->find($id);

        if (!$location) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'       => 'Edit Lokasi QR',
            'location'    => $location,
            'validation'  => \Config\Services::validation(),
        ];

        return view('admin/qr_location/edit', $data);
    }

    /**
     * Update lokasi
     */
    public function update($id = null)
    {
        // Get ID dari POST atau parameter URL
        if (!$id) {
            $id = $this->request->getPost('id');
        }
        
        log_message('error', "========== UPDATE METHOD CALLED ==========");
        log_message('error', "ID Parameter: {$id}");
        log_message('error', "Request Method: " . strtoupper($this->request->getMethod()));
        log_message('error', "POST Array Keys: " . json_encode(array_keys($this->request->getPost() ?? [])));
        log_message('error', "POST nama_lokasi: " . ($this->request->getPost('nama_lokasi') ?? 'NOT SET'));
        
        // Validate ID
        if (!$id) {
            log_message('error', "ERROR: ID is null or empty!");
            return redirect()->to(base_url('admin/qr-location'))->with('error', 'ID tidak valid');
        }
        
        // Get location from database
        $db = \Config\Database::connect();
        $location = $db->table('qr_location')->where('id', $id)->get()->getRowArray();
        
        if (!$location) {
            log_message('error', "ERROR: Location ID {$id} not found in database");
            return redirect()->to(base_url('admin/qr-location'))->with('error', 'Lokasi tidak ditemukan');
        }
        
        log_message('error', "Location found: {$location['nama_lokasi']}");
        
        // Check if POST or GET
        $methodStr = strtoupper($this->request->getMethod());
        log_message('error', "Method comparison: '{$methodStr}' === 'POST' ? " . ($methodStr === 'POST' ? 'TRUE' : 'FALSE'));
        
        if ($methodStr === 'POST' || $methodStr === 'post' || $this->request->getMethod() === 'post') {
            log_message('error', "PROCESSING POST REQUEST...");
            
            $nama_lokasi = trim($this->request->getPost('nama_lokasi') ?? '');
            $keterangan = trim($this->request->getPost('keterangan') ?? '');
            $aktif = $this->request->getPost('aktif') ? 1 : 0;
            
            log_message('error', "POST Data - Nama: '{$nama_lokasi}', Keterangan: '{$keterangan}', Aktif: {$aktif}");
            
            // Validation
            if (empty($nama_lokasi) || strlen($nama_lokasi) < 3) {
                log_message('error', "VALIDATION FAILED: nama_lokasi is invalid");
                session()->setFlashdata('error', 'Nama lokasi minimal 3 karakter');
                return redirect()->back()->withInput();
            }
            
            try {
                log_message('error', "Executing UPDATE query...");
                
                $updateResult = $db->table('qr_location')
                    ->where('id', $id)
                    ->update([
                        'nama_lokasi' => $nama_lokasi,
                        'keterangan' => $keterangan,
                        'aktif' => $aktif,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                
                log_message('error', "UPDATE RESULT: {$updateResult}");
                
                session()->setFlashdata('success', 'Lokasi QR berhasil diperbarui!');
                log_message('error', "SUCCESS - Redirecting to list");
                
                return redirect()->to(base_url('admin/qr-location'));
            } catch (\Exception $e) {
                log_message('error', "EXCEPTION: " . $e->getMessage());
                session()->setFlashdata('error', 'Error: ' . $e->getMessage());
                return redirect()->back()->withInput();
            }
        } else {
            log_message('error', "GET REQUEST - Showing edit form");
            
            $data = [
                'title'      => 'Edit Lokasi QR',
                'location'   => $location,
                'validation' => \Config\Services::validation(),
            ];
            
            return view('admin/qr_location/edit', $data);
        }
    }

    /**
     * Hapus lokasi
     */
    public function delete($id = null)
    {
        $location = $this->qrLocationModel->find($id);

        if (!$location) {
            return redirect()->to(base_url('admin/qr-location'))
                ->with('error', 'Lokasi QR tidak ditemukan');
        }

        $this->qrLocationModel->delete($id);

        return redirect()->to(base_url('admin/qr-location'))
            ->with('success', 'Lokasi QR berhasil dihapus');
    }

    /**
     * Generate QR harian untuk semua lokasi
     */
    public function generateDaily()
    {
        try {
            $generated = $this->qrLocationModel->generateDailyQrCodes();

            if (empty($generated)) {
                $message = 'QR Code untuk hari ini sudah tersedia di semua lokasi';
            } else {
                $message = 'QR Code berhasil dihasilkan untuk ' . count($generated) . ' lokasi';
            }

            return redirect()->to(base_url('admin/qr-daily'))
                ->with('success', $message);
        } catch (\Exception $e) {
            log_message('error', 'Generate Daily QR Error: ' . $e->getMessage());

            return redirect()->to(base_url('admin/qr-daily'))
                ->with('error', 'Gagal membuat QR Code: ' . $e->getMessage());
        }
    }
}
