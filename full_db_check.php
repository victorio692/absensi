<?php
$db = mysqli_connect('localhost', 'root', '', 'absensi_db');
if (!$db) die("Connection failed: " . mysqli_connect_error());

// List all tables
echo "Tables in absensi_db:\n";
$result = mysqli_query($db, "SHOW TABLES");
while ($row = mysqli_fetch_row($result)) {
    echo "  - " . $row[0] . "\n";
}

// List all columns in users table
echo "\nColumns in users table:\n";
$result = mysqli_query($db, "SHOW COLUMNS FROM users");
if (!$result) {
    echo "Error: " . mysqli_error($db) . "\n";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "  - " . $row['Field'] . " (" . $row['Type'] . ") [Null: " . $row['Null'] . "]\n";
    }
}

// Try the exact query that CodeIgniter uses
echo "\nTrying exact query:\n";
$query = "SELECT * FROM `users` WHERE `email` = 'admin@absensi.com' LIMIT 1";
echo "Query: $query\n";
$result = mysqli_query($db, $query);
if (!$result) {
    echo "Error: " . mysqli_error($db) . "\n";
} else {
    if ($row = mysqli_fetch_assoc($result)) {
        echo "Found: " . $row['nome'] . "\n";
    } else {
        echo "No results\n";
    }
}

mysqli_close($db);
