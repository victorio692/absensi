<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1 class="mb-4"><i class="fas fa-qrcode"></i> QR Code Hari Ini</h1>

<div class="alert alert-info" role="alert">
    <i class="fas fa-calendar-alt"></i> <strong>Tanggal:</strong> <?= $tanggal ?>
    <a href="/admin/qr-daily/generate" class="btn btn-primary btn-sm float-end">
        <i class="fas fa-sync-alt"></i> Generate Ulang
    </a>
</div>

<?php if (session()->has('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?= session('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <?php if (!empty($qrToday)): ?>
        <?php foreach ($qrToday as $qr): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-map-marker-alt"></i> <?= $qr['nama_lokasi'] ?>
                    </div>
                    <div class="card-body text-center">
                        <div id="qr-<?= $qr['location_id'] ?>" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                            <small class="text-muted">Loading QR...</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-info btn-sm w-100" onclick="printQr(<?= $qr['location_id'] ?>, '<?= $qr['nama_lokasi'] ?>')">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                        <a href="/admin/qr-daily/<?= $qr['location_id'] ?>/show" class="btn btn-warning btn-sm w-100 mt-2">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle"></i> Tidak ada lokasi aktif untuk di-generate QR
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // Generate QR codes untuk semua lokasi
    <?php foreach ($qr_daily as $qr): ?>
    new QRCode(document.getElementById("qr-<?= $qr['location_id'] ?>"), {
        text: "<?= $qr['location_id'] ?>|<?= $qr['tanggal'] ?>|<?= $qr['token'] ?>",
        width: 200,
        height: 200,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
    <?php endforeach; ?>

    function printQr(locationId, namaLokasi) {
        const canvas = document.querySelector('#qr-' + locationId + ' canvas');
        const link = document.createElement('a');
        link.href = canvas.toDataURL();
        link.download = 'QR-' + namaLokasi + '-' + new Date().toISOString().split('T')[0] + '.png';
        link.click();
    }
</script>

<?= $this->endSection() ?>
