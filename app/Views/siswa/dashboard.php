<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-home"></i> Dashboard Siswa</h1>
</div>

<!-- Kartu Status Absensi -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-check-circle"></i> Status Absensi Hari Ini
            </div>
            <div class="card-body">
                <?php if ($sudahAbsenMasuk): ?>
                    <p><strong>Jam Masuk:</strong> <?= jamFormat($absensiMasukHariIni['jam_masuk']) ?></p>
                    <p><strong>Status:</strong> <?= badgeStatus($absensiMasukHariIni['status']) ?></p>
                    <?php if ($sudahAbsenPulang): ?>
                        <p><strong>Jam Pulang:</strong> <?= jamFormat($absensiPulangHariIni['jam_pulang']) ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-warning"><i class="fas fa-exclamation-triangle"></i> Belum absen hari ini</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user"></i> Informasi Siswa
            </div>
            <div class="card-body">
                <p><strong>Nama:</strong> <?= $siswa['nama'] ?></p>
                <p><strong>NIS:</strong> <?= $siswa['nis'] ?></p>
                <p><strong>Kelas:</strong> <?= $siswa['kelas'] ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Menu Tombol -->
<div class="row mb-4">
    <div class="col-md-6">
        <?php if (!$sudahAbsenMasuk): ?>
            <a href="/siswa/scan" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-camera"></i> Scan QR Code Masuk
            </a>
        <?php else: ?>
            <button class="btn btn-secondary btn-lg w-100" disabled>
                <i class="fas fa-check-circle"></i> Sudah Absen Masuk
            </button>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <?php if ($sudahAbsenMasuk && !$sudahAbsenPulang): ?>
            <a href="/siswa/scan" class="btn btn-warning btn-lg w-100">
                <i class="fas fa-camera"></i> Scan QR Code Pulang
            </a>
        <?php else: ?>
            <button class="btn btn-secondary btn-lg w-100" disabled>
                <i class="fas fa-check-circle"></i> Sudah Absen Pulang
            </button>
        <?php endif; ?>
    </div>
</div>

<!-- Kalender Absensi Bulanan -->
<?= view('siswa/partials/calendar', $calendarData) ?>

<!-- Riwayat Absensi Bulan Ini -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-calendar"></i> Riwayat Absensi Bulan Ini
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($riwayatAbsensi)): ?>
                        <?php $no = 1; foreach ($riwayatAbsensi as $absensi): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= tanggalIndo($absensi['tanggal']) ?></td>
                                <td><?= hariIndo($absensi['tanggal']) ?></td>
                                <td><?= jamFormat($absensi['jam_masuk']) ?></td>
                                <td><?= jamFormat($absensi['jam_pulang']) ?></td>
                                <td><?= badgeStatus($absensi['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data absensi bulan ini</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
