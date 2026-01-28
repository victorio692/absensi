<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1 class="mb-4">
    <i class="fas fa-chart-line"></i> Dashboard Admin
</h1>

<!-- Statistik Kartu -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <h6><i class="fas fa-users"></i> Total Siswa</h6>
            <h3><?= $totalSiswa ?></h3>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card" style="border-left-color: #48bb78;">
            <h6><i class="fas fa-check-circle"></i> Absensi Hari Ini</h6>
            <h3><?= $absensiHariIni ?></h3>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card" style="border-left-color: #4299e1;">
            <h6><i class="fas fa-history"></i> Total Absensi</h6>
            <h3><?= $totalAbsensi ?></h3>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card" style="border-left-color: #ed8936;">
            <h6><i class="fas fa-calendar"></i> Tanggal</h6>
            <h3><?= date('d') ?></h3>
            <small><?= date('M Y') ?></small>
        </div>
    </div>
</div>

<!-- Menu Utama -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-users"></i> Manajemen Siswa
            </div>
            <div class="card-body">
                <p>Kelola data siswa</p>
                <a href="/admin/siswa" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> Lihat Daftar Siswa
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-clipboard-list"></i> Data Absensi
            </div>
            <div class="card-body">
                <p>Lihat laporan absensi siswa dengan berbagai filter</p>
                <a href="/admin/absensi" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> Lihat Data Absensi
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Absensi Terkini -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-history"></i> Absensi Terkini
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentAbsensi)): ?>
                        <?php $no = 1; foreach ($recentAbsensi as $absensi): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $absensi['nama'] ?></td>
                                <td><?= $absensi['kelas'] ?></td>
                                <td><?= tanggalIndo($absensi['tanggal']) ?></td>
                                <td><?= jamFormat($absensi['jam_masuk']) ?></td>
                                <td><?= jamFormat($absensi['jam_pulang']) ?></td>
                                <td><?= badgeStatus($absensi['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data absensi</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
