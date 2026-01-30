<?php
$conn = new mysqli("localhost", "root", "", "absensi_db");
$result = $conn->query("DESCRIBE users;");
echo "Users table structure:\n";
while ($row = $result->fetch_assoc()) {
    echo "  - " . $row["Field"] . " (" . $row["Type"] . ")\n";
}
$conn->close();
?>
