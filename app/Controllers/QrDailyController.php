<?php

namespace App\Controllers;

use App\Models\QrDailyModel;
use App\Models\QrLocationModel;
use CodeIgniter\API\ResponseTrait;

class QrDailyController extends BaseController
{
    use ResponseTrait;

    protected $qrDailyModel;
    protected $qrLocationModel;

    public function __construct()
    {
        $this->qrDailyModel = new QrDailyModel();
        $this->qrLocationModel = new QrLocationModel();
    }

    /**
     * Generate QR otomatis untuk semua lokasi hari ini
     */
    public function generate()
    {
        // Biasanya dipanggil via cron job, tapi untuk sekarang bisa manual
        $tanggal = date('Y-m-d');
        $generated = $this->qrDailyModel->generateDailyQr($tanggal);

        return redirect()->to('/admin/qr-daily')
                       ->with('success', 'QR berhasil di-generate untuk ' . count($generated) . ' lokasi');
    }

    /**
     * Tampilkan QR hari ini untuk semua lokasi
     */
    public function index()
    {
        // Cek apakah QR hari ini sudah ada, jika belum generate
        $today = date('Y-m-d');
        $lokasiAktif = $this->qrLocationModel->getAktif();

        foreach ($lokasiAktif as $loc) {
            $existing = $this->qrDailyModel->getByLocationAndDate($loc['id'], $today);
            if (!$existing) {
                $this->qrDailyModel->generateQrForLocation($loc['id'], $today);
            }
        }

        $data = [
            'title'   => 'QR Code Hari Ini',
            'qrToday' => $this->qrDailyModel->getTodayWithLocation(),
            'tanggal' => date('d-m-Y'),
        ];

        return view('admin/qr_daily/index', $data);
    }

    /**
     * Tampilkan QR per lokasi untuk cetak
     */
    public function show($locationId)
    {
        $lokasi = $this->qrLocationModel->find($locationId);

        if (!$lokasi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $tanggal = date('Y-m-d');
        $qr = $this->qrDailyModel->getByLocationAndDate($locationId, $tanggal);

        if (!$qr) {
            $qr = $this->qrDailyModel->generateQrForLocation($locationId, $tanggal);
        }

        // Generate QR content untuk library QR
        $qrContent = $locationId . '|' . $tanggal . '|' . $qr['token'];

        $data = [
            'title'     => 'Cetak QR - ' . $lokasi['nama_lokasi'],
            'lokasi'    => $lokasi,
            'qrContent' => $qrContent,
            'tanggal'   => date('d-m-Y'),
        ];

        return view('admin/qr_daily/show', $data);
    }

    /**
     * API: Generate QR content (untuk AJAX)
     */
    public function getQrContent()
    {
        $locationId = $this->request->getGet('location_id');
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        if (!$locationId) {
            return $this->failValidationErrors('Location ID harus diisi');
        }

        $lokasi = $this->qrLocationModel->find($locationId);

        if (!$lokasi) {
            return $this->failNotFound('Lokasi tidak ditemukan');
        }

        $qr = $this->qrDailyModel->getByLocationAndDate($locationId, $tanggal);

        if (!$qr) {
            $qr = $this->qrDailyModel->generateQrForLocation($locationId, $tanggal);
        }

        $qrContent = $locationId . '|' . $tanggal . '|' . $qr['token'];

        return $this->respond([
            'status' => 'success',
            'data'   => [
                'location_id' => $locationId,
                'lokasi_nama' => $lokasi['nama_lokasi'],
                'tanggal'     => $tanggal,
                'qr_content'  => $qrContent,
                'token'       => $qr['token'],
            ],
        ]);
    }
}
