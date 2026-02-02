<?php
// Test database connection
$mysqli = new mysqli('localhost', 'root', '', 'absensi_db');
if ($mysqli->connect_error) {
    echo 'Connection Error: ' . $mysqli->connect_error . "\n";
    exit;
}
echo "Connection Successful!\n";
echo "Database selected: " . $mysqli->select_db('absensi_db') . "\n";
$mysqli->close();
