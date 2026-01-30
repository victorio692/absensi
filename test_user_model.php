<?php
require_once 'app/Models/UserModel.php';
require_once 'vendor/autoload.php';

$userModel = new \App\Models\UserModel();
echo "Table: " . $userModel->getTable() . "\n";
echo "Allowed fields: " . implode(', ', $userModel->getAllowedFields()) . "\n";

try {
    $user = $userModel->where('email', 'admin@absensi.com')->first();
    if ($user) {
        echo "Found user!\n";
        foreach ($user as $k => $v) {
            echo "  $k: $v\n";
        }
    } else {
        echo "No user found\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
