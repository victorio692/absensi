<?php

// Load CodeIgniter bootstrap
require_once __DIR__ . '/vendor/autoload.php';

// Create CodeIgniter instance
$app = require_once __DIR__ . '/app/Config/Paths.php';

// Get database config
$config = new \Config\Database();

echo "Default connection group: " . $config->defaultGroup . "\n";
echo "Host: " . $config->default['hostname'] . "\n";
echo "Database: " . $config->default['database'] . "\n";
echo "Username: " . $config->default['username'] . "\n";
echo "DBDriver: " . $config->default['DBDriver'] . "\n";
echo "Port: " . $config->default['port'] . "\n\n";

try {
    $db = \Config\Database::connect();
    echo "✓ CodeIgniter Database Connection Successful!\n";
    echo "Database version: " . $db->getVersion() . "\n";
} catch (\Exception $e) {
    echo "✗ CodeIgniter Database Connection Failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
}
