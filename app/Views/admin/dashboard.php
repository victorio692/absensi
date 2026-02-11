<?php helper('absensi_helper'); ?>

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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-3xl);
    }

    .stat-card {
        background: var(--color-surface);
        border-radius: var(--radius-xl);
        padding: var(--spacing-lg);
        border-left: 4px solid var(--color-primary);
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-smooth);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-card.success {
        border-left-color: var(--color-success);
    }

    .stat-card.info {
        border-left-color: var(--color-info);
    }

    .stat-card.warning {
        border-left-color: var(--color-warning);
    }

    .stat-card-label {
        font-size: var(--font-sm);
        color: var(--color-text-secondary);
        font-weight: 500;
        margin-bottom: var(--spacing-sm);
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
    }

    .stat-card-label i {
        color: var(--color-primary);
    }

    .stat-card.success .stat-card-label i {
        color: var(--color-success);
    }

    .stat-card.info .stat-card-label i {
        color: var(--color-info);
    }

    .stat-card.warning .stat-card-label i {
        color: var(--color-warning);
    }

    .stat-card-value {
        font-size: var(--font-3xl);
        font-weight: 800;
        color: var(--color-text);
        line-height: 1;
    }

    .stat-card-meta {
        font-size: var(--font-xs);
        color: var(--color-text-tertiary);
        margin-top: var(--spacing-sm);
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-3xl);
    }

    .card-modern {
        overflow: hidden;
    }

    .card-header-modern {
        background: var(--color-background-secondary);
        padding: var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
        font-weight: 700;
        color: var(--color-text);
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
    }

    .card-header-modern i {
        color: var(--color-primary);
        font-size: var(--font-lg);
    }

    .card-body-modern {
        padding: var(--spacing-lg);
    }

    .card-body-modern p {
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-lg);
    }

    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }

    .table-modern thead {
        background-color: var(--color-background-secondary);
    }

    .table-modern th {
        padding: var(--spacing-md) var(--spacing-lg);
        text-align: left;
        font-weight: 700;
        color: var(--color-text);
        border-bottom: 2px solid var(--color-border);
        font-size: var(--font-sm);
    }

    .table-modern td {
        padding: var(--spacing-md) var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-secondary);
    }

    .table-modern tbody tr:hover {
        background-color: var(--color-surface-hover);
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .cards-grid {
            grid-template-columns: 1fr;
        }

        .stat-card-value {
            font-size: var(--font-2xl);
        }
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-chart-line"></i> Dashboard Admin
    </h1>
</div>

<!-- Statistik Kartu -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-label">
            <i class="fas fa-users"></i> Total Siswa
        </div>
        <div class="stat-card-value"><?= $totalSiswa ?></div>
    </div>
    <div class="stat-card success">
        <div class="stat-card-label">
            <i class="fas fa-check-circle"></i> Absensi Hari Ini
        </div>
        <div class="stat-card-value"><?= $absensiHariIni ?></div>
    </div>
    <div class="stat-card info">
        <div class="stat-card-label">
            <i class="fas fa-history"></i> Total Absensi
        </div>
        <div class="stat-card-value"><?= $totalAbsensi ?></div>
    </div>
    <div class="stat-card warning">
        <div class="stat-card-label">
            <i class="fas fa-calendar"></i> Tanggal
        </div>
        <div class="stat-card-value"><?= date('d') ?></div>
        <div class="stat-card-meta"><?= date('M Y') ?></div>
    </div>
</div>

<!-- Menu Utama -->
<div class="cards-grid">
    <div class="card-modern">
        <div class="card-header-modern">
            <i class="fas fa-users"></i> Manajemen Siswa
        </div>
        <div class="card-body-modern">
            <p>Kelola data siswa dengan mudah</p>
            <a href="/admin/siswa" class="btn-modern btn-primary">
                <i class="fas fa-arrow-right"></i> Lihat Daftar Siswa
            </a>
        </div>
    </div>
    <div class="card-modern">
        <div class="card-header-modern">
            <i class="fas fa-clipboard-list"></i> Data Absensi
        </div>
        <div class="card-body-modern">
            <p>Lihat laporan absensi siswa dengan berbagai filter</p>
            <a href="/admin/absensi" class="btn-modern btn-primary">
                <i class="fas fa-arrow-right"></i> Lihat Data Absensi
            </a>
        </div>
    </div>
</div>

<!-- Absensi Terkini -->
<div class="card-modern">
    <div class="card-header-modern">
        <i class="fas fa-history"></i> Absensi Terkini
    </div>
    <div class="card-body-modern">
        <div style="overflow-x: auto;">
            <table class="table-modern">
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
                            <td colspan="7" style="text-align: center; padding: var(--spacing-xl); color: var(--color-text-tertiary);">
                                Belum ada data absensi
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
