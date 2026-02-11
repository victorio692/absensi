<?php
$db = new mysqli('localhost', 'root', '', 'absensi_db');

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

$result = $db->query('SELECT u.id, u.nama, u.username, s.nisn, s.nis, s.kelas FROM siswa s JOIN users u ON s.user_id = u.id ORDER BY s.id');

echo "\n========================================\n";
echo "AKUN SISWA UNTUK LOGIN\n";
echo "========================================\n\n";

$no = 1;
while ($row = $result->fetch_assoc()) {
    echo "$no. Nama: " . $row['nama'] . "\n";
    echo "   Username: " . $row['username'] . "\n";
    echo "   NISN: " . ($row['nisn'] ?? '-') . "\n";
    echo "   NIS: " . $row['nis'] . "\n";
    echo "   Kelas: " . $row['kelas'] . "\n";
    echo "   Password: siswa123 (atau gunakan NIS jika login dengan NISN)\n";
    echo "----------------------------------------\n\n";
    $no++;
}

$db->close();
?>
