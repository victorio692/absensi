<?php

// Set up CodeIgniter environment
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SYSPATH', realpath(FCPATH . 'system') . DIRECTORY_SEPARATOR);

// Load CodeIgniter's bootstrap file
require_once SYSPATH . '../vendor/autoload.php';
require_once SYSPATH . 'Config/Paths.php';

// Get config instance
$config = new \Config\App();
$config->baseURL = 'http://localhost:8080/';

// Load database config  
$dbConfig = new \Config\Database();
$db = \Config\Database::connect();

echo "Database Connected: " . ($db ? "YES" : "NO") . "\n";
echo "Database Name: " . $db->getDatabase() . "\n";

// Get tables
$tables = $db->listTables();
echo "\nTables in database:\n";
print_r($tables);

// Describe users table
$fields = $db->getFieldData('users');
echo "\nColumns in 'users' table:\n";
foreach ($fields as $field) {
    echo "  - {$field->name} ({$field->type})\n";
}

// Try direct query
echo "\n\nDirect Query Test:\n";
$result = $db->table('users')->where('email', 'admin@absensi.com')->get();
$rows = $result->getResultArray();

if ($rows) {
    echo "Found " . count($rows) . " user(s)\n";
    echo "First user: " . json_encode($rows[0], JSON_PRETTY_PRINT) . "\n";
} else {
    echo "No users found\n";
}

// Now try using the model
echo "\n\nModel Test:\n";
$userModel = new \App\Models\UserModel();
echo "Model Table: " . $userModel->getTable() . "\n";
echo "Model AllowedFields: " . implode(', ', $userModel->getAllowedFields()) . "\n";

try {
    $user = $userModel->where('email', 'admin@absensi.com')->first();
    if ($user) {
        echo "Model Found User: " . json_encode($user, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "Model: No users found\n";
    }
} catch (\Exception $e) {
    echo "Model Error: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
}
