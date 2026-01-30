<?php
$conn = new mysqli("localhost", "root", "", "absensi_db");
$result = $conn->query("SHOW TABLES");
echo "Tables in absensi_db:\n";
while ($row = $result->fetch_assoc()) {
    echo "- " . array_values($row)[0] . "\n";
}
$conn->close();
?>
