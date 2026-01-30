<?php
// Test direct access to IzinSakitModel
require_once __DIR__ . '/vendor/autoload.php';

$config = new \Config\Database();
$db = $config->default;

// Simple test
echo "DB Host: " . $db['hostname'] . "\n";
echo "DB Name: " . $db['database'] . "\n";

// Try to check migration
$mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);

if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

// Check if table exists
$result = $mysqli->query("SHOW TABLES LIKE 'izin_sakit'");
if ($result->num_rows > 0) {
    echo "✓ Table izin_sakit exists\n";
    
    // Check columns
    $columns = $mysqli->query("DESCRIBE izin_sakit");
    echo "\nColumns in izin_sakit:\n";
    while($col = $columns->fetch_assoc()) {
        echo "  - " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }
} else {
    echo "✗ Table izin_sakit does NOT exist\n";
}

$mysqli->close();
