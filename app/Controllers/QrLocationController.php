<?php

namespace App\Controllers;

use App\Models\QrLocationModel;
use App\Models\QrDailyModel;

class QrLocationController extends BaseController
{
    protected $qrLocationModel;
    protected $qrDailyModel;

    public function __construct()
    {
        $this->qrLocationModel = new QrLocationModel();
        $this->qrDailyModel = new QrDailyModel();
    }

    /**
     * Tampilkan daftar lokasi QR
     */
    public function index()
    {
        $data = [
            'title'   => 'Kelola Lokasi QR',
            'lokasi'  => $this->qrLocationModel->findAll(),
        ];

        return view('admin/qr_location/index', $data);
    }

    /**
     * Tampilkan form tambah lokasi
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Lokasi QR',
        ];

        return view('admin/qr_location/form', $data);
    }

    /**
     * Simpan lokasi baru
     */
    public function store()
    {
        if (!$this->validate($this->qrLocationModel->validationRules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $this->qrLocationModel->insert([
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'aktif'       => $this->request->getPost('aktif') ? 1 : 0,
        ]);

        return redirect()->to('/admin/qr-location')
                       ->with('success', 'Lokasi QR berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit lokasi
     */
    public function edit($id)
    {
        $lokasi = $this->qrLocationModel->find($id);

        if (!$lokasi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'  => 'Edit Lokasi QR',
            'lokasi' => $lokasi,
        ];

        return view('admin/qr_location/form', $data);
    }

    /**
     * Perbarui lokasi
     */
    public function update($id)
    {
        $lokasi = $this->qrLocationModel->find($id);

        if (!$lokasi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (!$this->validate($this->qrLocationModel->validationRules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $this->qrLocationModel->update($id, [
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'aktif'       => $this->request->getPost('aktif') ? 1 : 0,
        ]);

        return redirect()->to('/admin/qr-location')
                       ->with('success', 'Lokasi QR berhasil diperbarui');
    }

    /**
     * Hapus lokasi QR
     */
    public function delete($id)
    {
        $lokasi = $this->qrLocationModel->find($id);

        if (!$lokasi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->qrLocationModel->delete($id);

        return redirect()->to('/admin/qr-location')
                       ->with('success', 'Lokasi QR berhasil dihapus');
    }
}
