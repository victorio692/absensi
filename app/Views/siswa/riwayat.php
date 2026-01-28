<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-history"></i> Riwayat Absensi</h1>
</div>

<!-- Filter Bulan -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/siswa/riwayat" class="row g-3">
            <div class="col-md-3">
                <label for="bulan" class="form-label">Bulan</label>
                <select class="form-select" id="bulan" name="bulan">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= sprintf('%02d', $i) ?>" <?= $bulan == sprintf('%02d', $i) ? 'selected' : '' ?>>
                            <?= \DateTime::createFromFormat('!m', $i)->format('F') ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="tahun" class="form-label">Tahun</label>
                <select class="form-select" id="tahun" name="tahun">
                    <?php for ($i = date('Y') - 2; $i <= date('Y'); $i++): ?>
                        <option value="<?= $i ?>" <?= $tahun == $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Lihat Riwayat
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <h6><i class="fas fa-calendar"></i> Periode</h6>
            <h3><?= $bulan ?>/<?= $tahun ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left-color: #48bb78;">
            <h6><i class="fas fa-check-circle"></i> Hadir</h6>
            <h3><?= $hadir ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left-color: #ed8936;">
            <h6><i class="fas fa-hourglass-start"></i> Terlambat</h6>
            <h3><?= $terlambat ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left-color: #4299e1;">
            <h6><i class="fas fa-list"></i> Total</h6>
            <h3><?= $total ?></h3>
        </div>
    </div>
</div>

<!-- Tabel Riwayat -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-table"></i> Detail Absensi
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
                    <?php if (!empty($riwayat)): ?>
                        <?php $no = 1; foreach ($riwayat as $absensi): ?>
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
                            <td colspan="6" class="text-center">Belum ada data absensi untuk bulan ini</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
