<?php

namespace App\Controllers;

use App\Models\QrDailyModel;
use App\Models\QrLocationModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrDaily extends BaseController
{
    protected $qrDailyModel;
    protected $qrLocationModel;

    public function __construct()
    {
        $this->qrDailyModel = model(QrDailyModel::class);
        $this->qrLocationModel = model(QrLocationModel::class);
    }

    /**
     * Tampilkan daftar QR harian
     */
    public function index()
    {
        $today = date('Y-m-d');
        
        $qrDaily = $this->qrDailyModel
            ->select('qr_daily.*, qr_location.nama_lokasi')
            ->join('qr_location', 'qr_location.id = qr_daily.location_id')
            ->where('qr_daily.tanggal', $today)
            ->findAll();

        $data = [
            'title'    => 'QR Code Harian',
            'tanggal'  => $today,
            'qr_daily' => $qrDaily,
        ];

        return view('admin/qr_daily/index', $data);
    }

    /**
     * Tampilkan detail QR untuk dicetak
     */
    public function show($id = null)
    {
        $qrDaily = $this->qrDailyModel
            ->select('qr_daily.*, qr_location.nama_lokasi')
            ->join('qr_location', 'qr_location.id = qr_daily.location_id')
            ->find($id);

        if (!$qrDaily) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'   => 'Detail QR Code',
            'qr_daily' => $qrDaily,
        ];

        return view('admin/qr_daily/show', $data);
    }

    /**
     * Generate QR Code image
     */
    public function generateImage($id = null)
    {
        $qrDaily = $this->qrDailyModel->find($id);

        if (!$qrDaily) {
            return $this->response->setStatusCode(404, 'Not Found');
        }

        // Generate QR Code menggunakan library endroid/qr-code
        // Jika belum install, gunakan alternatif dengan canvas
        return $this->generateQrCodeImage($qrDaily['qr_content']);
    }

    /**
     * Generate QR Code image (alternatif jika library tidak tersedia)
     */
    private function generateQrCodeImage($content)
    {
        // Menggunakan data URI QR code dari external service
        // Alternatif: gunakan library javascript di view
        
        $filename = md5($content) . '.png';
        
        // Cek cache
        $cachePath = WRITEPATH . 'uploads/qr_codes/' . $filename;
        
        if (!is_dir(WRITEPATH . 'uploads/qr_codes/')) {
            mkdir(WRITEPATH . 'uploads/qr_codes/', 0755, true);
        }

        if (!file_exists($cachePath)) {
            // Generate menggunakan external API
            $url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($content);
            
            // Download dan cache
            $imageData = @file_get_contents($url);
            
            if ($imageData) {
                file_put_contents($cachePath, $imageData);
            }
        }

        if (file_exists($cachePath)) {
            return $this->response->download($cachePath, null, true);
        }

        return $this->response->setStatusCode(404, 'QR Code tidak dapat dihasilkan');
    }

    /**
     * Display QR as image/png dalam view
     */
    public function getQrImage($id = null)
    {
        $qrDaily = $this->qrDailyModel->find($id);

        if (!$qrDaily) {
            return $this->response->setStatusCode(404);
        }

        // Generate QR Code data URI
        $qrContent = $qrDaily['qr_content'];
        $url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrContent);

        return redirect()->to($url);
    }

    /**
     * Cetak QR Code
     */
    public function printQr($id = null)
    {
        $qrDaily = $this->qrDailyModel
            ->select('qr_daily.*, qr_location.nama_lokasi')
            ->join('qr_location', 'qr_location.id = qr_daily.location_id')
            ->find($id);

        if (!$qrDaily) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'    => 'Cetak QR Code',
            'qr_daily' => $qrDaily,
            'qr_image_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=' . urlencode($qrDaily['qr_content']),
        ];

        return view('admin/qr_daily/print', $data);
    }

    /**
     * API untuk generate/refresh QR harian
     */
    public function refreshDaily()
    {
        try {
            // Ini bisa dipanggil dari cron job
            $generated = $this->qrLocationModel->generateDailyQrCodes();

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'QR Code refreshed',
                'generated' => $generated,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Refresh Daily QR Error: ' . $e->getMessage());

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API untuk validasi QR Code saat scan
     */
    public function validateQr()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request'], 400);
        }

        $qrContent = $this->request->getPost('qr_content');

        if (!$qrContent) {
            return $this->response->setJSON([
                'valid'   => false,
                'message' => 'QR Code tidak boleh kosong',
            ]);
        }

        $validation = $this->qrDailyModel->validateQrCode($qrContent);

        return $this->response->setJSON($validation);
    }
}
