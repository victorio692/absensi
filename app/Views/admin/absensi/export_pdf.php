<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Absensi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            background: white;
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #667eea;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 13px;
        }

        .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
        }

        .info-item {
            flex: 1;
        }

        .info-item strong {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #667eea;
        }

        table td {
            padding: 11px 12px;
            border: 1px solid #e2e8f0;
        }

        table tbody tr:nth-child(even) {
            background: #f9f9fa;
        }

        table tbody tr:hover {
            background: #f0f0f5;
        }

        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 11px;
        }

        .status.izin {
            background-color: #667eea;
            color: white;
        }

        .status.sakit {
            background-color: #764ba2;
            color: white;
        }

        .status.alpha {
            background-color: #dc3545;
            color: white;
        }

        .status.hadir {
            background: #d4edda;
            color: #155724;
        }

        .status.terlambat {
            background: #fff3cd;
            color: #856404;
        }

        .status.tidak {
            background: #f8d7da;
            color: #721c24;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .summary-item h4 {
            color: #667eea;
            font-size: 12px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .summary-item .value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            color: #999;
            font-size: 11px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }

        @media print {
            body {
                background: white;
            }

            .container {
                padding: 0;
            }

            table {
                page-break-inside: avoid;
            }

            .header {
                page-break-after: avoid;
            }
        }

        @page {
            size: A4;
            margin: 20mm;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="icon">📋</i> Laporan Data Absensi</h1>
            <p>Sistem Manajemen Absensi QR Code</p>
        </div>

        <!-- Info -->
        <div class="info">
            <div class="info-item">
                <strong>Tanggal Cetak:</strong> <?= $print_date ?>
            </div>
            <div class="info-item">
                <strong>Total Data:</strong> <?= count($absensi) ?> record
            </div>
        </div>

        <!-- Summary -->
        <?php if (!empty($absensi)): ?>
            <?php
            $hadir = 0;
            $terlambat = 0;
            $izin = 0;
            $sakit = 0;
            $alpha = 0;

            foreach ($absensi as $a) {
                $status = strtolower($a['status']);
                if ($status === 'hadir') $hadir++;
                elseif ($status === 'terlambat') $terlambat++;
                elseif ($status === 'izin') $izin++;
                elseif ($status === 'sakit') $sakit++;
                elseif ($status === 'alpha') $alpha++;
            }
            ?>
            <div class="summary">
                <div class="summary-item">
                    <h4>Hadir</h4>
                    <div class="value" style="color: #28a745;"><?= $hadir ?></div>
                </div>
                <div class="summary-item">
                    <h4>Terlambat</h4>
                    <div class="value" style="color: #ffc107;"><?= $terlambat ?></div>
                </div>
                <div class="summary-item">
                    <h4>Izin</h4>
                    <div class="value" style="color: #667eea;"><?= $izin ?></div>
                </div>
                <div class="summary-item">
                    <h4>Sakit</h4>
                    <div class="value" style="color: #764ba2;"><?= $sakit ?></div>
                </div>
                <div class="summary-item">
                    <h4>Alpha</h4>
                    <div class="value" style="color: #dc3545;"><?= $alpha ?></div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Nama Siswa</th>
                    <th style="width: 10%;">NIS</th>
                    <th style="width: 10%;">Kelas</th>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 12%;">Jam Masuk</th>
                    <th style="width: 12%;">Jam Pulang</th>
                    <th style="width: 12%;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($absensi)): ?>
                    <?php $no = 1; foreach ($absensi as $a): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $a['nama'] ?></td>
                            <td><?= $a['nis'] ?></td>
                            <td><?= $a['kelas'] ?></td>
                            <td><?= date('d-m-Y', strtotime($a['tanggal'])) ?></td>
                            <td><?= $a['jam_masuk'] ? date('H:i', strtotime($a['jam_masuk'])) : '-' ?></td>
                            <td><?= $a['jam_pulang'] ? date('H:i', strtotime($a['jam_pulang'])) : '-' ?></td>
                            <td>
                                <span class="status <?= strtolower($a['status']) ?>">
                                    <?= $a['status'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="no-data">Tidak ada data absensi</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Generated by Sistem Manajemen Absensi QR Code</p>
            <p>Tekan Ctrl+P untuk mencetak atau simpan sebagai PDF</p>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.print();
    </script>
</body>
</html>
