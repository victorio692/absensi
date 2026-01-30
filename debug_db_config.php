<?php
require 'vendor/autoload.php';
$config = require 'app/Config/Database.php';
$db_config = (array)$config;
echo "Default database config:\n";
echo "  Hostname: " . ($db_config['default']['hostname'] ?? 'not set') . "\n";
echo "  Username: " . ($db_config['default']['username'] ?? 'not set') . "\n";
echo "  Database: " . ($db_config['default']['database'] ?? 'not set') . "\n";

// Try to connect
$db = mysqli_connect(
    $db_config['default']['hostname'],
    $db_config['default']['username'],
    $db_config['default']['password'],
    $db_config['default']['database']
);
if (!$db) {
    echo "Connection failed: " . mysqli_connect_error() . "\n";
} else {
    echo "\nConnected! Checking users table:\n";
    $result = mysqli_query($db, "DESCRIBE users");
    if (!$result) {
        echo "Error: " . mysqli_error($db) . "\n";
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "  - " . $row['Field'] . "\n";
        }
    }
    mysqli_close($db);
}
