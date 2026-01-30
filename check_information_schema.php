<?php
$db = mysqli_connect('localhost', 'root', '', 'information_schema');
if (!$db) die("Connection failed");

// Check absensi_db.users table
$query = "SELECT COLUMN_NAME FROM COLUMNS WHERE TABLE_SCHEMA='absensi_db' AND TABLE_NAME='users'";
echo "Columns in absensi_db.users (from INFORMATION_SCHEMA):\n";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_assoc($result)) {
    echo "  - " . $row['COLUMN_NAME'] . "\n";
}

mysqli_close($db);
