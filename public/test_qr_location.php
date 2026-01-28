<?php

// Test script untuk debug qr_location
$config = new \Config\Database();
$db = $config->default;

try {
    $mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);
    
    if ($mysqli->connect_error) {
        die('Connection failed: ' . $mysqli->connect_error);
    }
    
    // Check table structure
    $result = $mysqli->query("DESCRIBE qr_location");
    echo "=== QR_LOCATION TABLE STRUCTURE ===\n";
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . " - Null: " . $row['Null'] . " - Default: " . $row['Default'] . "\n";
    }
    
    // Check data
    $result = $mysqli->query("SELECT * FROM qr_location LIMIT 5");
    echo "\n=== QR_LOCATION DATA (Sample) ===\n";
    while ($row = $result->fetch_assoc()) {
        echo json_encode($row) . "\n";
    }
    
    $mysqli->close();
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
