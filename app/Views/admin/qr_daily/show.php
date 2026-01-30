<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1 class="mb-4"><i class="fas fa-qrcode"></i> Cetak QR - <?= $qr_daily['nama_lokasi'] ?></h1>

<div class="row">
    <div class="col-12 col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-map-marker-alt"></i> <?= $qr_daily['nama_lokasi'] ?>
                <small class="text-muted float-end">Tanggal: <?= tanggalIndo($qr_daily['tanggal']) ?></small>
            </div>
            <div class="card-body text-center">
                <div id="qrcode" style="display: inline-block; padding: 20px; background: white; border: 2px solid #ddd; border-radius: 5px;"></div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <button class="btn btn-success" onclick="downloadQr()">
                    <i class="fas fa-download"></i> Download
                </button>
                <a href="<?= base_url('admin/qr-daily') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h5><i class="fas fa-info-circle"></i> Informasi QR Code</h5>
                <table class="table table-sm mb-0">
                    <tr>
                        <td><strong>Lokasi</strong></td>
                        <td><?= $qr_daily['nama_lokasi'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal</strong></td>
                        <td><?= tanggalIndo($qr_daily['tanggal']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Token</strong></td>
                        <td><code class="text-danger" style="font-size: 10px;"><?= substr($qr_daily['token'], 0, 50) ?>...</code></td>
                    </tr>
                    <tr>
                        <td><strong>QR Content</strong></td>
                        <td><code style="font-size: 11px; word-break: break-all;"><?= $qr_daily['location_id'] . '|' . $qr_daily['tanggal'] . '|' . $qr_daily['token'] ?></code></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card">
            <div class="card-body">
                <h5><i class="fas fa-lightbulb"></i> Tips Penggunaan</h5>
                <ul class="small">
                    <li>Cetak QR ini dan pasang di lokasi: <strong><?= $qr_daily['nama_lokasi'] ?></strong></li>
                    <li>QR Code ini hanya berlaku untuk <strong><?= tanggalIndo($qr_daily['tanggal']) ?></strong></li>
                    <li>QR baru akan otomatis di-generate setiap hari</li>
                    <li>Siswa scan QR ini untuk absen masuk</li>
                    <li>Ukuran cetak minimal A4 untuk hasil yang baik</li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5><i class="fas fa-shield-alt"></i> Keamanan</h5>
                <p class="small text-muted mb-0">
                    QR Code dilindungi dengan token yang berubah setiap hari. Tidak ada QR lama yang masih bisa digunakan untuk absensi.
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // Generate QR code dengan format: location_id|tanggal|token
    const qrContent = "<?= $qr_daily['location_id'] . '|' . $qr_daily['tanggal'] . '|' . $qr_daily['token'] ?>";
    
    new QRCode(document.getElementById("qrcode"), {
        text: qrContent,
        width: 300,
        height: 300,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    function downloadQr() {
        const canvas = document.querySelector('#qrcode canvas');
        const link = document.createElement('a');
        link.href = canvas.toDataURL();
        link.download = 'QR-<?= strtoupper(str_replace(' ', '-', $qr_daily['nama_lokasi'])) ?>-<?= $qr_daily['tanggal'] ?>.png';
        link.click();
    }
</script>

<style media="print">
    .card-footer, .btn, a.btn, h1 { display: none; }
    #qrcode { border: none; }
</style>

<?= $this->endSection() ?>
