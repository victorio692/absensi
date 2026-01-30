<?php

namespace App\Controllers;

use App\Models\QrDailyModel;
use App\Models\QrLocationModel;

class MonitorQr extends BaseController
{
    protected $qrDailyModel;
    protected $qrLocationModel;

    public function __construct()
    {
        $this->qrDailyModel = model(QrDailyModel::class);
        $this->qrLocationModel = model(QrLocationModel::class);
    }

    /**
     * Pilih lokasi untuk ditampilkan di monitor
     */
    public function selectLocation()
    {
        $lokasi = $this->qrLocationModel->getAktif();

        $data = [
            'title' => 'Pilih Lokasi Monitor',
            'lokasi' => $lokasi,
        ];

        return view('admin/monitor/select_location', $data);
    }

    /**
     * Tampilkan QR code besar di monitor (fullscreen)
     */
    public function display($locationId = null)
    {
        if (!$locationId) {
            return redirect()->to('/admin/monitor/select');
        }

        // Validasi lokasi aktif
        $location = $this->qrLocationModel->find($locationId);

        if (!$location || !$location['aktif']) {
            session()->setFlashdata('error', 'Lokasi tidak aktif atau tidak ditemukan!');
            return redirect()->to('/admin/monitor/select');
        }

        $today = date('Y-m-d');

        // Cek apakah QR sudah ada untuk hari ini
        $qrDaily = $this->qrDailyModel
            ->select('qr_daily.*, qr_location.nama_lokasi')
            ->join('qr_location', 'qr_location.id = qr_daily.location_id')
            ->where('qr_daily.location_id', $locationId)
            ->where('qr_daily.tanggal', $today)
            ->first();

        // Auto-generate jika belum ada
        if (!$qrDaily) {
            $this->qrDailyModel->generateQrForLocation($locationId, $today);

            $qrDaily = $this->qrDailyModel
                ->select('qr_daily.*, qr_location.nama_lokasi')
                ->join('qr_location', 'qr_location.id = qr_daily.location_id')
                ->where('qr_daily.location_id', $locationId)
                ->where('qr_daily.tanggal', $today)
                ->first();
        }

        if (!$qrDaily) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Monitor QR - ' . $qrDaily['nama_lokasi'],
            'qr_daily' => $qrDaily,
            'qr_content' => $qrDaily['location_id'] . '|' . $qrDaily['tanggal'] . '|' . $qrDaily['token'],
        ];

        return view('admin/monitor/display', $data);
    }

    /**
     * API untuk get QR data (AJAX)
     */
    public function getQrData($locationId = null)
    {
        if (!$locationId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Location ID required'
            ]);
        }

        $today = date('Y-m-d');

        $qrDaily = $this->qrDailyModel
            ->select('qr_daily.*')
            ->where('location_id', $locationId)
            ->where('tanggal', $today)
            ->first();

        if (!$qrDaily) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'QR code not found for today'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'location_id' => $qrDaily['location_id'],
            'tanggal' => $qrDaily['tanggal'],
            'token' => $qrDaily['token'],
            'qr_content' => $qrDaily['location_id'] . '|' . $qrDaily['tanggal'] . '|' . $qrDaily['token'],
        ]);
    }
}
