<?php
$conn = new mysqli("localhost", "root", "", "absensi_db");
if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}

$adminPass = password_hash("admin123", PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO users (nama, email, password, role, created_at, updated_at) VALUES (\"Administrator\", \"admin@absensi.local\", \"$adminPass\", \"admin\", NOW(), NOW())");

$siswaPass = password_hash("siswa123", PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO users (nama, email, password, role, created_at, updated_at) VALUES (\"Siswa Test\", \"siswa@absensi.local\", \"$siswaPass\", \"karyawan\", NOW(), NOW())");

$result = $conn->query("SELECT id FROM users WHERE email=\"siswa@absensi.local\" AND role=\"karyawan\"");
if ($row = $result->fetch_assoc()) {
    $siswaUserId = $row["id"];
    $conn->query("INSERT IGNORE INTO siswa (user_id, nisn, nis, kelas, created_at, updated_at) VALUES ($siswaUserId, \"1234567890\", \"0123456\", \"XII-A\", NOW(), NOW())");
}

echo "Test data created!\n";
echo "Admin:\n";
echo "  Email: admin@absensi.local\n";
echo "  Password: admin123\n\n";
echo "Siswa:\n";
echo "  Email: siswa@absensi.local\n";
echo "  Password: siswa123\n";
$conn->close();
?>
