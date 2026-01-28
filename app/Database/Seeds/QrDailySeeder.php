<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class QrDailySeeder extends Seeder
{
    public function run()
    {
        $today = date('Y-m-d');
        $encryptionKey = config('App')->encryptionKey;

        // Generate QR untuk 4 lokasi
        $locations = [1, 2, 3, 4];

        foreach ($locations as $locationId) {
            $token = hash('sha256', $locationId . '|' . $today . '|' . $encryptionKey);
            $qrContent = $locationId . '|' . $today . '|' . $token;

            $this->db->table('qr_daily')->insert([
                'location_id' => $locationId,
                'tanggal'     => $today,
                'token'       => $token,
                'qr_content'  => $qrContent,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        echo "QR Daily seed completed for date: {$today}\n";
    }
}
