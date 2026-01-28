<?php

namespace App\Controllers;

class DebugQr extends BaseController
{
    public function testForm()
    {
        $html = <<<'HTML'
        <!DOCTYPE html>
        <html>
        <head>
            <title>Test QR Location Form</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-5">
                <h1>Test QR Location Add Form</h1>
                
                <div class="row">
                    <div class="col-md-8">
                        <form action="/debug-qr/testStore" method="POST">
                            <div class="mb-3">
                                <label>Nama Lokasi:</label>
                                <input type="text" name="nama_lokasi" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Keterangan:</label>
                                <textarea name="keterangan" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>
                                    <input type="checkbox" name="aktif" value="1" checked>
                                    Aktif
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Test</button>
                        </form>
                    </div>
                </div>
                
                <hr>
                <h2 class="mt-5">Data di Database:</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Lokasi</th>
                            <th>Keterangan</th>
                            <th>Aktif</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
HTML;

        $db = \Config\Database::connect();
        $locations = $db->table('qr_location')->get()->getResultArray();
        
        foreach ($locations as $loc) {
            $html .= '<tr>';
            $html .= '<td>' . $loc['id'] . '</td>';
            $html .= '<td>' . $loc['nama_lokasi'] . '</td>';
            $html .= '<td>' . ($loc['keterangan'] ?? '-') . '</td>';
            $html .= '<td>' . ($loc['aktif'] ? 'Ya' : 'Tidak') . '</td>';
            $html .= '<td>' . ($loc['created_at'] ?? '-') . '</td>';
            $html .= '</tr>';
        }
        
        $html .= <<<'HTML'
                    </tbody>
                </table>
            </div>
        </body>
        </html>
HTML;

        return $html;
    }
    
    public function testStore()
    {
        $response = [
            'status' => 'error',
            'message' => '',
        ];
        
        try {
            if ($this->request->getMethod() !== 'post') {
                $response['message'] = 'Method harus POST';
                return $this->response->setJSON($response);
            }
            
            $nama_lokasi = $this->request->getPost('nama_lokasi');
            $keterangan = $this->request->getPost('keterangan');
            $aktif = $this->request->getPost('aktif');
            
            $response['debug'] = [
                'method' => $this->request->getMethod(),
                'post_data' => $this->request->getPost(),
                'nama_lokasi' => $nama_lokasi,
                'keterangan' => $keterangan,
                'aktif' => $aktif,
            ];
            
            if (empty($nama_lokasi)) {
                $response['message'] = 'Nama lokasi tidak boleh kosong';
                return $this->response->setJSON($response);
            }
            
            $db = \Config\Database::connect();
            $result = $db->table('qr_location')->insert([
                'nama_lokasi' => $nama_lokasi,
                'keterangan' => $keterangan ?? '',
                'aktif' => $aktif ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            
            if ($result) {
                $response['status'] = 'success';
                $response['message'] = 'Data berhasil ditambahkan';
                $response['inserted_id'] = $db->insertID();
                
                // Redirect back to form
                return redirect()->to('/debug-qr/testForm')
                    ->with('success', 'Data berhasil disimpan! ID: ' . $db->insertID());
            } else {
                $response['message'] = 'Gagal insert data ke database';
                return $this->response->setJSON($response);
            }
            
        } catch (\Exception $e) {
            $response['message'] = 'Error: ' . $e->getMessage();
            $response['trace'] = $e->getTraceAsString();
            return $this->response->setJSON($response);
        }
    }
}
