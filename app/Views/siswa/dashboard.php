<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="dashboard-header mb-5">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="mb-1">
                <i class="fas fa-home text-primary me-3"></i>Dashboard
            </h1>
            <p class="text-muted mb-0">Selamat datang, <?= $siswa['nama'] ?></p>
        </div>
        <div class="text-end">
            <p class="text-muted mb-0"><?= tanggalIndo(date('Y-m-d')) ?></p>
            <p class="text-muted small"><?= hariIndo(date('Y-m-d')) ?></p>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-5">
    <div class="col-12 col-md-6 col-lg-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">NIS</p>
                <h3 class="stat-value"><?= $siswa['nis'] ?></h3>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="stat-card stat-card-info">
            <div class="stat-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Kelas</p>
                <h3 class="stat-value"><?= $siswa['kelas'] ?></h3>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="stat-card stat-card-success">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Status Hari Ini</p>
                <h3 class="stat-value"><?= $sudahAbsenMasuk ? 'Hadir' : 'Belum' ?></h3>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="stat-card stat-card-warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Jam Masuk</p>
                <h3 class="stat-value"><?= $sudahAbsenMasuk ? jamFormat($absensiMasukHariIni['jam_masuk']) : '--:--' ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Today Card -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card card-large">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-day me-2"></i>
                    Status Absensi Hari Ini
                </div>
            </div>
            <div class="card-body">
                <?php if ($sudahAbsenMasuk): ?>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="attendance-detail">
                                <p class="detail-label">Jam Masuk</p>
                                <p class="detail-value text-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <?= jamFormat($absensiMasukHariIni['jam_masuk']) ?>
                                </p>
                                <p class="detail-status"><?= badgeStatus($absensiMasukHariIni['status']) ?></p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="attendance-detail">
                                <p class="detail-label">Jam Pulang</p>
                                <?php if ($sudahAbsenPulang): ?>
                                    <p class="detail-value text-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <?= jamFormat($absensiPulangHariIni['jam_pulang']) ?>
                                    </p>
                                <?php else: ?>
                                    <p class="detail-value text-warning">
                                        <i class="fas fa-hourglass-end me-2"></i>
                                        Menunggu pulang
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p class="text-muted mt-3">Anda belum melakukan absensi hari ini</p>
                        <a href="/siswa/scan" class="btn btn-primary btn-lg mt-3">
                            <i class="fas fa-camera me-2"></i>Scan QR Sekarang
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-5">
    <div class="col-12 col-md-6">
        <?php if (!$sudahAbsenMasuk): ?>
            <a href="/siswa/scan" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-camera me-2"></i>Scan QR Code Masuk
            </a>
        <?php else: ?>
            <button class="btn btn-secondary btn-lg w-100" disabled>
                <i class="fas fa-check-circle me-2"></i>Sudah Absen Masuk
            </button>
        <?php endif; ?>
    </div>
    <div class="col-12 col-md-6 mt-3 mt-md-0">
        <?php if ($sudahAbsenMasuk && !$sudahAbsenPulang): ?>
            <a href="/siswa/scan" class="btn btn-warning btn-lg w-100">
                <i class="fas fa-camera me-2"></i>Scan QR Code Pulang
            </a>
        <?php else: ?>
            <button class="btn btn-secondary btn-lg w-100" disabled>
                <i class="fas fa-check-circle me-2"></i>Sudah Absen Pulang
            </button>
        <?php endif; ?>
    </div>
</div>

<!-- Attendance Calendar -->
<?= view('siswa/partials/calendar', $calendarData) ?>

<!-- Attendance History Table -->
<div class="card card-large">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <i class="fas fa-history me-2"></i>
                Riwayat Absensi Bulan Ini
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($riwayatAbsensi)): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayatAbsensi as $absensi): ?>
                            <tr>
                                <td class="fw-semibold"><?= tanggalIndo($absensi['tanggal']) ?></td>
                                <td><?= hariIndo($absensi['tanggal']) ?></td>
                                <td><?= jamFormat($absensi['jam_masuk']) ?></td>
                                <td><?= jamFormat($absensi['jam_pulang']) ?></td>
                                <td><?= badgeStatus($absensi['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p class="text-muted mt-3">Belum ada data absensi bulan ini</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Dashboard Specific Styles */
.dashboard-header {
    background: linear-gradient(135deg, #f0f5fb 0%, #ffffff 100%);
    padding: var(--space-xl);
    border-radius: var(--radius-lg);
    border-left: 4px solid var(--primary);
}

/* Stat Cards */
.stat-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--space-xl);
    display: flex;
    align-items: center;
    gap: var(--space-lg);
    box-shadow: var(--shadow-md);
    transition: all var(--transition-normal);
    border-left: 4px solid;
    margin-bottom: var(--space-lg);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.stat-card-primary {
    border-left-color: var(--primary);
    background: linear-gradient(135deg, #f0f5fb 0%, var(--white) 100%);
}

.stat-card-info {
    border-left-color: var(--status-izin);
    background: linear-gradient(135deg, #ecf9ff 0%, var(--white) 100%);
}

.stat-card-success {
    border-left-color: var(--status-hadir);
    background: linear-gradient(135deg, #ecfdf5 0%, var(--white) 100%);
}

.stat-card-warning {
    border-left-color: var(--status-terlambat);
    background: linear-gradient(135deg, #fffbeb 0%, var(--white) 100%);
}

.stat-icon {
    font-size: 2rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-lg);
    background-color: rgba(30, 58, 95, 0.1);
    color: var(--primary);
}

.stat-label {
    font-size: 0.875rem;
    color: var(--neutral-medium);
    margin-bottom: var(--space-xs);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--neutral-dark);
    margin: 0;
}

/* Card Large */
.card-large {
    border-radius: var(--radius-xl);
}

.card-large .card-header {
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
    padding: var(--space-xl);
}

/* Attendance Detail */
.attendance-detail {
    padding: var(--space-lg);
    background-color: var(--neutral-lighter);
    border-radius: var(--radius-md);
}

.detail-label {
    font-size: 0.875rem;
    color: var(--neutral-medium);
    margin-bottom: var(--space-md);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: var(--space-md);
}

.detail-status {
    margin: 0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: var(--space-3xl) var(--space-xl);
}

.empty-state i {
    font-size: 3rem;
    color: var(--neutral-light);
}

@media (max-width: 768px) {
    .stat-card {
        gap: var(--space-md);
        padding: var(--space-lg);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
}
</style>

<?= $this->endSection() ?>
