#!/usr/bin/env php
<?php
/**
 * Database Setup and Connection Test
 */

echo "===== ABSENSI-CI DATABASE SETUP =====\n\n";

// Step 1: Direct MySQL Connection Test
echo "[1] Testing direct MySQL connection...\n";
$mysqli = new mysqli('localhost', 'root', '', '');
if ($mysqli->connect_error) {
    echo "❌ MySQL Connection Failed: " . $mysqli->connect_error . "\n";
    exit(1);
}
echo "✓ MySQL connection successful\n\n";

// Step 2: Check if database exists
echo "[2] Checking if database 'absensi_db' exists...\n";
$result = $mysqli->query("SHOW DATABASES LIKE 'absensi_db'");
if ($result && $result->num_rows > 0) {
    echo "✓ Database 'absensi_db' exists\n";
} else {
    echo "❌ Database 'absensi_db' does NOT exist\n";
    echo "Creating database 'absensi_db'...\n";
    if ($mysqli->query("CREATE DATABASE absensi_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
        echo "✓ Database created successfully\n";
    } else {
        echo "❌ Failed to create database: " . $mysqli->error . "\n";
        exit(1);
    }
}
$mysqli->close();

// Step 3: Test connection to the database
echo "\n[3] Testing connection to 'absensi_db'...\n";
$mysqli = new mysqli('localhost', 'root', '', 'absensi_db');
if ($mysqli->connect_error) {
    echo "❌ Database connection failed: " . $mysqli->connect_error . "\n";
    exit(1);
}
echo "✓ Database connection successful\n";
echo "Character set: " . $mysqli->character_set_name() . "\n";

// Step 4: List tables
echo "\n[4] Listing tables in 'absensi_db':\n";
$result = $mysqli->query("SHOW TABLES");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_row()) {
        echo "  - " . $row[0] . "\n";
    }
} else {
    echo "  (No tables found)\n";
}

$mysqli->close();

echo "\n===== SETUP COMPLETE =====\n";
echo "Your database is ready. You can now access it via:\n";
echo "  Host: localhost\n";
echo "  User: root\n";
echo "  Password: (empty)\n";
echo "  Database: absensi_db\n";
