<?php

namespace App\Controllers;

class TestNotesController extends BaseController
{
    public function index()
    {
        // Test menambah berbagai tipe notes
        
        // Note sukses
        addSuccessNote('Ini catatan sukses!');
        
        // Note error yang tetap
        addErrorNote('Ini catatan error penting yang tetap ditampilkan', 
                    'Error Penting', 
                    isPermanent: true);
        
        // Note warning
        addWarningNote('Ini adalah warning');
        
        // Note info
        addInfoNote('Ini adalah informasi');
        
        return redirect()->to('/');
    }
    
    public function testApi()
    {
        // Contoh API response
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Ini hanya contoh API',
        ]);
    }
}
