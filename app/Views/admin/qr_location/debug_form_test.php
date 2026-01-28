<!DOCTYPE html>
<html>
<head>
    <title>Form Submission Debug Test</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .section { border: 1px solid #ccc; padding: 10px; margin: 10px 0; }
        textarea { width: 100%; height: 100px; }
        pre { background: #f0f0f0; padding: 10px; overflow-x: auto; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Form Submission Debug Test</h1>
    
    <div class="section">
        <h2>1. Sebelum Submit</h2>
        <p>Lihat Network tab di F12 (Inspector)</p>
    </div>
    
    <div class="section">
        <h2>2. Form Test Simple</h2>
        <form action="<?= base_url('admin/qr-location/store') ?>" method="post">
            <?= csrf_field() ?>
            <input type="text" name="nama_lokasi" value="Debug Test <?= date('Y-m-d H:i:s') ?>" required>
            <textarea name="keterangan">Testing simple form</textarea>
            <label><input type="checkbox" name="aktif" value="1" checked> Aktif</label>
            <button type="submit">Submit Form</button>
        </form>
    </div>
    
    <div class="section">
        <h2>3. Setelah Submit</h2>
        <p>Catat:</p>
        <ul>
            <li>Apakah halaman refresh?</li>
            <li>Apakah ada pesan success/error?</li>
            <li>Pada Network tab, lihat response dari POST /admin/qr-location/store</li>
            <li>Status code apa? (200, 303, 500?)</li>
            <li>Response body apa?</li>
        </ul>
    </div>
</body>
</html>
