<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1 class="mb-4"><i class="fas fa-camera"></i> Scan QR Code Absensi</h1>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-video"></i> Kamera Scanner
            </div>
            <div class="card-body" style="position: relative; min-height: 400px; background: #000;">
                <video id="preview" style="width: 100%; height: 400px; display: none;"></video>
                <div id="scanner-placeholder" style="width: 100%; height: 400px; display: flex; align-items: center; justify-content: center; color: white;">
                    <div style="text-align: center;">
                        <p>Izinkan akses kamera untuk scan QR Code</p>
                        <button type="button" class="btn btn-primary" onclick="startScanner()">
                            <i class="fas fa-video"></i> Aktifkan Kamera
                        </button>
                    </div>
                </div>
                <canvas id="canvas" style="display: none;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-keyboard"></i> Input Manual
            </div>
            <div class="card-body">
                <form action="/siswa/scan/proses" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="qr_token" class="form-label">Kode QR <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="qr_token" name="qr_token" 
                               placeholder="Scan QR atau masukkan kode QR" required autofocus>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check"></i> Absen
                    </button>
                </form>

                <hr>

                <div class="alert alert-info">
                    <strong><i class="fas fa-info-circle"></i> Petunjuk:</strong>
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>Aktifkan kamera atau masukkan kode QR secara manual</li>
                        <li>Absen masuk hanya bisa 1 kali per hari</li>
                        <li>Absen pulang hanya setelah absen masuk</li>
                        <li>Sistem otomatis menentukan status (Hadir/Terlambat) berdasarkan jam</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsqr/1.4.0/jsQR.js"></script>
<script>
let stream = null;

function startScanner() {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(s => {
            stream = s;
            const video = document.getElementById('preview');
            video.srcObject = stream;
            video.play();
            document.getElementById('scanner-placeholder').style.display = 'none';
            video.style.display = 'block';
            scanQR();
        })
        .catch(err => {
            alert('Error mengakses kamera: ' + err);
        });
}

function scanQR() {
    const video = document.getElementById('preview');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const qrInput = document.getElementById('qr_token');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const scanInterval = setInterval(() => {
        if (!stream) {
            clearInterval(scanInterval);
            return;
        }

        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);

        if (code) {
            qrInput.value = code.data;
            alert('QR Code terdeteksi: ' + code.data);
            document.querySelector('form').submit();
            clearInterval(scanInterval);
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        }
    }, 300);
}
</script>
<?= $this->endSection() ?>
