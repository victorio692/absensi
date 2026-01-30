<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor QR - <?= $qr_daily['nama_lokasi'] ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .monitor-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            text-align: center;
            max-width: 600px;
            width: 90%;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .location-header {
            color: #667eea;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .location-name {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
        }

        .qr-wrapper {
            background: #f8f9fa;
            padding: 40px;
            border-radius: 15px;
            margin: 30px 0;
            display: inline-block;
            border: 3px solid #667eea;
        }

        #qrcode {
            display: inline-block;
        }

        #qrcode canvas {
            max-width: 100%;
            height: auto;
            image-rendering: pixelated;
        }

        .status-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            font-size: 0.95rem;
            color: #666;
        }

        .time-display {
            font-size: 1.3rem;
            font-weight: bold;
            color: #667eea;
        }

        .auto-refresh-indicator {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #28a745;
        }

        .dot {
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 49% {
                opacity: 1;
            }
            50%, 100% {
                opacity: 0.3;
            }
        }

        .info-section {
            margin-top: 30px;
            padding: 20px;
            background: #f0f4ff;
            border-radius: 10px;
            color: #667eea;
            font-size: 0.9rem;
        }

        .button-group {
            margin-top: 30px;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-small {
            padding: 8px 16px;
            font-size: 0.9rem;
            border-radius: 8px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .monitor-container {
                padding: 20px;
            }

            .location-header {
                font-size: 1.5rem;
            }

            .location-name {
                font-size: 1.8rem;
            }

            .qr-wrapper {
                padding: 20px;
            }

            .status-bar {
                flex-direction: column;
                gap: 10px;
            }
        }

        /* Hide controls */
        .hide-controls {
            display: none;
        }

        .keyboard-hint {
            position: fixed;
            bottom: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 0.8rem;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="monitor-container" id="mainContainer">
        <div class="location-header">
            <i class="fas fa-tv"></i> MONITOR ABSENSI
        </div>

        <div class="location-name">
            <?= $qr_daily['nama_lokasi'] ?>
        </div>

        <div class="qr-wrapper">
            <div id="qrcode"></div>
        </div>

        <div class="info-section">
            <i class="fas fa-info-circle"></i>
            Scan QR Code ini untuk absen masuk/pulang
        </div>

        <div class="status-bar" id="controlBar">
            <div class="time-display" id="currentTime">00:00:00</div>
            <div class="auto-refresh-indicator">
                <div class="dot"></div>
                <span>Auto Refresh: <span id="refreshTime">60</span>s</span>
            </div>
            <small id="lastUpdate">Terakhir update: --:--:--</small>
        </div>

        <div class="button-group hide-controls" id="buttonGroup">
            <button class="btn btn-sm btn-secondary btn-small" onclick="backToSelect()">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
            <button class="btn btn-sm btn-info btn-small" onclick="toggleFullscreen()">
                <i class="fas fa-expand"></i> Fullscreen
            </button>
            <button class="btn btn-sm btn-warning btn-small" onclick="toggleControls()">
                <i class="fas fa-bars"></i> Menu
            </button>
        </div>
    </div>

    <div class="keyboard-hint" id="keyboardHint">
        Tekan <kbd>M</kbd> untuk menu, <kbd>F</kbd> untuk fullscreen, <kbd>ESC</kbd> untuk kembali
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const locationId = <?= $qr_daily['location_id'] ?>;
        const qrContent = "<?= $qr_content ?>";
        let refreshInterval = null;
        let refreshCountdown = 60;
        const REFRESH_INTERVAL = 60000; // 60 detik

        // Generate QR Code
        function generateQR() {
            document.getElementById('qrcode').innerHTML = '';
            new QRCode(document.getElementById('qrcode'), {
                text: qrContent,
                width: 300,
                height: 300,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }

        // Update waktu
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            document.getElementById('currentTime').textContent = 
                hours + ':' + minutes + ':' + seconds;
        }

        // Update QR periodically
        function startAutoRefresh() {
            // Update time setiap detik
            setInterval(updateTime, 1000);

            // Refresh QR setiap 60 detik
            refreshCountdown = 60;
            setInterval(() => {
                refreshCountdown--;
                document.getElementById('refreshTime').textContent = refreshCountdown;
                
                if (refreshCountdown <= 0) {
                    refreshQRCode();
                    refreshCountdown = 60;
                }
            }, 1000);
        }

        // Refresh QR Code
        function refreshQRCode() {
            fetch('<?= base_url('api/monitor/qr/' . $qr_daily['location_id']) ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update halaman dengan data baru
                        location.reload();
                    }
                })
                .catch(err => console.log('Refresh error:', err));

            document.getElementById('lastUpdate').textContent = 
                'Terakhir update: ' + new Date().toLocaleTimeString('id-ID');
        }

        // Toggle fullscreen
        function toggleFullscreen() {
            const elem = document.documentElement;
            if (!document.fullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.webkitRequestFullscreen) {
                    elem.webkitRequestFullscreen();
                }
                document.getElementById('keyboardHint').style.display = 'none';
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
                document.getElementById('keyboardHint').style.display = 'block';
            }
        }

        // Toggle controls
        function toggleControls() {
            const buttonGroup = document.getElementById('buttonGroup');
            buttonGroup.classList.toggle('hide-controls');
        }

        // Back to select
        function backToSelect() {
            window.location.href = '<?= base_url('admin/monitor/select') ?>';
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'm' || e.key === 'M') {
                toggleControls();
            } else if (e.key === 'f' || e.key === 'F') {
                toggleFullscreen();
            } else if (e.key === 'Escape') {
                backToSelect();
            }
        });

        // Initialize
        window.addEventListener('load', () => {
            generateQR();
            updateTime();
            startAutoRefresh();
        });

        // Hide keyboard hint after 5 seconds
        setTimeout(() => {
            document.getElementById('keyboardHint').style.opacity = '0.3';
        }, 5000);
    </script>
</body>
</html>
