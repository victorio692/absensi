<?php
$db = mysqli_connect('localhost', 'root', '', 'absensi_qr');
if (!$db) die('Connection failed: ' . mysqli_connect_error());

$result = $db->query('SELECT siswa.id, siswa.nisn, siswa.nis, siswa.kelas, users.nama FROM siswa JOIN users ON siswa.user_id = users.id ORDER BY siswa.id');

echo "========================================\n";
echo "DATA SISWA UNTUK LOGIN\n";
echo "========================================\n\n";

while ($row = $result->fetch_assoc()) {
    echo "Nama: {$row['nama']}\n";
    echo "NISN: {$row['nisn']}\n";
    echo "NIS:  {$row['nis']}\n";
    echo "Kelas: {$row['kelas']}\n";
    echo "----------------------------------------\n";
}

$db->close();
