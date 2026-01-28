<?php ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code - <?= htmlspecialchars($qr_daily['nama_lokasi']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        
        .qr-container {
            border: 3px solid #000;
            padding: 20px;
            text-align: center;
            page-break-inside: avoid;
        }
        
        .qr-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .qr-image {
            max-width: 100%;
            margin: 20px 0;
        }
        
        .qr-info {
            font-size: 18px;
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row no-print mb-3">
            <div class="col-12">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <button onclick="window.history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </div>
        </div>

        <!-- Print Content -->
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="qr-container">
                    <div class="qr-title">
                        <?= htmlspecialchars($qr_daily['nama_lokasi']) ?>
                    </div>
                    
                    <img src="<?= $qr_image_url ?>" alt="QR Code" class="qr-image" style="width: 400px; height: 400px;">
                    
                    <div class="qr-info">
                        <p><strong><?= tanggalIndo($qr_daily['tanggal']) ?></strong></p>
                        <p class="text-muted" style="font-size: 14px;">
                            Scan QR Code ini untuk absen masuk
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
