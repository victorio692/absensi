<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<style>
    .page-header {
        margin-bottom: var(--spacing-3xl);
    }

    .page-header h1 {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        font-weight: 800;
        color: var(--color-text);
    }

    .scanner-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-3xl);
    }

    .scanner-card {
        background: var(--color-surface);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--color-border);
    }

    .scanner-card-header {
        background: var(--color-background-secondary);
        padding: var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
        font-weight: 700;
        color: var(--color-text);
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
    }

    .scanner-card-header i {
        color: var(--color-primary);
    }

    .scanner-card-body {
        padding: var(--spacing-lg);
    }

    .video-container {
        position: relative;
        width: 100%;
        aspect-ratio: 1;
        background: #000;
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin-bottom: var(--spacing-lg);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .video-container video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }

    .scanner-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .scanner-placeholder p {
        color: white;
        margin: 0;
    }

    .form-group {
        margin-bottom: var(--spacing-lg);
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--color-text);
        margin-bottom: var(--spacing-sm);
        font-size: var(--font-sm);
    }

    .form-label .required {
        color: var(--color-danger);
    }

    .form-control-modern {
        width: 100%;
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-size: var(--font-base);
        font-family: inherit;
        color: var(--color-text);
        background-color: var(--color-surface);
        transition: all var(--transition-fast);
    }

    .form-control-modern:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .info-box {
        background: rgba(59, 130, 246, 0.1);
        border-left: 4px solid var(--color-info);
        padding: var(--spacing-lg);
        border-radius: var(--radius-lg);
    }

    .info-box strong {
        color: var(--color-text);
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
        margin-bottom: var(--spacing-md);
    }

    .info-box strong i {
        color: var(--color-info);
    }

    .info-box ul {
        margin: 0;
        padding-left: var(--spacing-xl);
        color: var(--color-text-secondary);
    }

    .info-box li {
        margin-bottom: var(--spacing-sm);
    }

    @media (max-width: 768px) {
        .scanner-grid {
            grid-template-columns: 1fr;
        }

        .video-container {
            aspect-ratio: 16/9;
        }
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-camera"></i> Scan QR Code Absensi
    </h1>
</div>

<div class="scanner-grid">
    <!-- Scanner Card -->
    <div class="scanner-card">
        <div class="scanner-card-header">
            <i class="fas fa-video"></i> Kamera Scanner
        </div>
        <div class="scanner-card-body">
            <div class="video-container">
                <video id="preview"></video>
                <div id="scanner-placeholder" class="scanner-placeholder">
                    <div>
                        <i class="fas fa-camera" style="font-size: var(--font-4xl); margin-bottom: var(--spacing-md); display: block;"></i>
                        <p>Izinkan akses kamera untuk scan QR Code</p>
                    </div>
                    <button type="button" class="btn-modern btn-primary" onclick="startScanner()">
                        <i class="fas fa-video"></i> Aktifkan Kamera
                    </button>
                </div>
                <canvas id="canvas" style="display: none;"></canvas>
            </div>
        </div>
    </div>

    <!-- Manual Input Card -->
    <div class="scanner-card">
        <div class="scanner-card-header">
            <i class="fas fa-keyboard"></i> Input Manual
        </div>
        <div class="scanner-card-body">
            <form action="/siswa/scan/proses" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="qr_token" class="form-label">
                        Kode QR <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control-modern" id="qr_token" name="qr_token" 
                           placeholder="Scan QR atau masukkan kode QR" required autofocus>
                </div>

                <button type="submit" class="btn-modern btn-primary" style="width: 100%; padding: var(--spacing-lg);">
                    <i class="fas fa-check"></i> Absen
                </button>
            </form>

            <hr style="border: none; border-top: 1px solid var(--color-border); margin: var(--spacing-lg) 0;">

            <div class="info-box">
                <strong>
                    <i class="fas fa-info-circle"></i> Petunjuk
                </strong>
                <ul>
                    <li>Aktifkan kamera atau masukkan kode QR secara manual</li>
                    <li>Absen masuk hanya bisa 1 kali per hari</li>
                    <li>Absen pulang hanya setelah absen masuk</li>
                    <li>Sistem otomatis menentukan status (Hadir/Terlambat) berdasarkan jam</li>
                </ul>
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
