<?php
require_once __DIR__ . '/vendor/autoload.php';

$db = \Config\Database::connect();
$fields = $db->getFieldData('siswa');

echo "Columns in siswa table:\n";
foreach($fields as $field) {
    echo "- {$field->name} ({$field->type})\n";
}
