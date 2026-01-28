<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1 class="mb-4"><i class="fas fa-qrcode"></i> Scan QR - Absen Masuk</h1>

<?php if (session()->has('success')): ?>
    <div class="alert alert-success alert-dismissible fade show alert-lg" role="alert">
        <i class="fas fa-check-circle"></i> <strong><?= session('success') ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show alert-lg" role="alert">
        <i class="fas fa-exclamation-circle"></i> <strong><?= session('error') ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> 
                    Arahkan kamera ke QR Code yang terpasang di lokasi masuk sekolah.
                </div>

                <!-- Video untuk scanning -->
                <div id="qr-reader" style="width: 100%; min-height: 400px; border: 2px solid #ddd; border-radius: 5px; background: #000;"></div>

                <div class="mt-3">
                    <p class="text-center text-muted">
                        <small><i class="fas fa-lightbulb"></i> Pastikan pencahayaan cukup dan fokus pada QR Code</small>
                    </p>
                </div>

                <!-- Form hidden untuk submit QR -->
                <form id="scanForm" method="POST" action="/siswa/scan-masuk/proses" style="display: none;">
                    <?= csrf_field() ?>
                    <input type="hidden" id="qr_content" name="qr_content">
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h5><i class="fas fa-clock"></i> Status Absensi Hari Ini</h5>
                <div id="status-absensi" class="alert alert-info">
                    <small>Loading...</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5><i class="fas fa-info-circle"></i> Panduan Scanning</h5>
                <ol class="small">
                    <li>Temukan QR Code yang terpasang di lokasi masuk</li>
                    <li>Arahkan kamera smartphone ke QR Code</li>
                    <li>Tunggu hingga QR terdeteksi otomatis</li>
                    <li>Absensi masuk akan tercatat dengan status Hadir/Terlambat</li>
                </ol>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5><i class="fas fa-clock"></i> Jam Masuk</h5>
                <p class="mb-0">
                    Jam masuk sekolah: <strong id="jam-masuk">--:--</strong>
                </p>
                <small class="text-muted">Terlambat jika masuk setelah jam tersebut</small>
            </div>
        </div>

        <a href="/siswa/dashboard" class="btn btn-secondary w-100 mt-3">
            <i class="fas fa-home"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js"></script>
<script>
    let html5QrcodeScanner = null;
    let lastScannedValue = '';
    const debounceDelay = 2000;
    let lastScanTime = 0;

    function onScanSuccess(decodedText, decodedResult) {
        const now = Date.now();
        
        // Debounce: jangan proses QR yang sama dalam 2 detik
        if (decodedText === lastScannedValue && (now - lastScanTime) < debounceDelay) {
            return;
        }

        lastScannedValue = decodedText;
        lastScanTime = now;

        // Submit form dengan QR content
        document.getElementById('qr_content').value = decodedText;
        document.getElementById('scanForm').submit();
    }

    function onScanFailure(error) {
        // Ignore errors untuk continuous scanning
    }

    // Initialize scanner
    html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader",
        {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        }
    );
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);

    // Load jam masuk via AJAX
    fetch('/api/pengaturan')
        .then(r => r.json())
        .then(data => {
            if (data.jam_masuk) {
                document.getElementById('jam-masuk').textContent = data.jam_masuk;
            }
        })
        .catch(e => console.log('Note: jam_masuk tidak tersedia'));

    // Load status absensi
    fetch('/api/absensi-hari-ini')
        .then(r => r.json())
        .then(data => {
            const statusEl = document.getElementById('status-absensi');
            if (data.has_absen_masuk) {
                statusEl.innerHTML = `
                    <i class="fas fa-check-circle text-success"></i> 
                    <strong>Sudah absen masuk</strong> pukul ${data.jam_masuk}
                    <br><small>Status: ${data.status}</small>
                `;
                statusEl.className = 'alert alert-success';
            } else {
                statusEl.innerHTML = '<i class="fas fa-clock"></i> Belum absen masuk';
                statusEl.className = 'alert alert-warning';
            }
        })
        .catch(e => console.log('Status tidak tersedia'));
</script>

<?= $this->endSection() ?>
