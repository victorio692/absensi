<?php
$db = mysqli_connect('localhost', 'root', '', 'absensi_db');
if (!$db) {
    echo "Connection failed: " . mysqli_connect_error();
    exit;
}

$result = $db->query('DESCRIBE users');
echo "Users table columns:\n";
while($row = $result->fetch_assoc()) {
    echo $row['Field'] . ' (' . $row['Type'] . ")\n";
}

echo "\nFirst user record:\n";
$result = $db->query('SELECT * FROM users LIMIT 1');
if($row = $result->fetch_assoc()) {
    foreach($row as $key => $val) {
        echo "$key: $val\n";
    }
}

$db->close();
