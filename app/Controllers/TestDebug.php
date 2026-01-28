<?php

namespace App\Controllers;

class TestDebug extends BaseController
{
    public function testStoreLocation()
    {
        if ($this->request->getMethod() === 'post') {
            $nama_lokasi = $this->request->getPost('nama_lokasi');
            $keterangan = $this->request->getPost('keterangan');
            $aktif = $this->request->getPost('aktif');
            
            echo "DEBUG INFO:\n";
            echo "nama_lokasi: " . var_export($nama_lokasi, true) . "\n";
            echo "keterangan: " . var_export($keterangan, true) . "\n";
            echo "aktif: " . var_export($aktif, true) . "\n";
            echo "aktif ? 1 : 0 = " . ($aktif ? 1 : 0) . "\n";
            
            $qrLocationModel = model('App\Models\QrLocationModel');
            
            try {
                $result = $qrLocationModel->insert([
                    'nama_lokasi' => trim($nama_lokasi),
                    'keterangan' => trim($keterangan ?? ''),
                    'aktif' => $aktif ? 1 : 0,
                ]);
                
                echo "\nINSERT RESULT: " . var_export($result, true);
                echo "\nINSERT ID: " . $qrLocationModel->insertID();
                
                // Show inserted data
                $inserted = $qrLocationModel->find($qrLocationModel->insertID());
                echo "\nINSERTED DATA: " . json_encode($inserted, JSON_PRETTY_PRINT);
                
            } catch (\Exception $e) {
                echo "\nERROR: " . $e->getMessage();
                echo "\nTRACE: " . $e->getTraceAsString();
            }
            
            return;
        }
        
        // Show test form
        echo <<<HTML
        <form method="post">
            <input type="hidden" name="csrf_test" value="">
            <input type="text" name="nama_lokasi" placeholder="Nama Lokasi" required>
            <textarea name="keterangan" placeholder="Keterangan"></textarea>
            <input type="checkbox" name="aktif" value="1" checked>
            <button type="submit">Test Insert</button>
        </form>
        HTML;
    }
}
