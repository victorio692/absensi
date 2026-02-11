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

    .filter-card {
        background: var(--color-surface);
        border-radius: var(--radius-xl);
        padding: var(--spacing-lg);
        box-shadow: var(--shadow-sm);
        margin-bottom: var(--spacing-lg);
        border: 1px solid var(--color-border);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--spacing-lg);
        align-items: flex-end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-weight: 600;
        color: var(--color-text);
        margin-bottom: var(--spacing-sm);
        font-size: var(--font-sm);
    }

    .form-control-modern select {
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

    .form-control-modern select:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        border: 1px solid var(--color-border);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-card.success {
        border-left-color: var(--color-success);
    }

    .stat-card.warning {
        border-left-color: var(--color-warning);
    }

    .stat-card.info {
        border-left-color: var(--color-info);
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

    .stat-card.warning .stat-card-label i {
        color: var(--color-warning);
    }

    .stat-card.info .stat-card-label i {
        color: var(--color-info);
    }

    .stat-card-value {
        font-size: var(--font-3xl);
        font-weight: 800;
        color: var(--color-text);
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
        .form-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-history"></i> Riwayat Absensi
    </h1>
</div>

<!-- Filter Card -->
<div class="filter-card">
    <form method="GET" action="/siswa/riwayat" class="form-grid">
        <div class="form-group">
            <label for="bulan" class="form-label">Bulan</label>
            <select class="form-control-modern" id="bulan" name="bulan">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= sprintf('%02d', $i) ?>" <?= $bulan == sprintf('%02d', $i) ? 'selected' : '' ?>>
                        <?= \DateTime::createFromFormat('!m', $i)->format('F') ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="tahun" class="form-label">Tahun</label>
            <select class="form-control-modern" id="tahun" name="tahun">
                <?php for ($i = date('Y') - 2; $i <= date('Y'); $i++): ?>
                    <option value="<?= $i ?>" <?= $tahun == $i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <button type="submit" class="btn-modern btn-primary" style="padding: var(--spacing-md) var(--spacing-lg);">
            <i class="fas fa-search"></i> Lihat Riwayat
        </button>
    </form>
</div>

<!-- Statistik -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-label">
            <i class="fas fa-calendar"></i> Periode
        </div>
        <div class="stat-card-value"><?= $bulan ?>/<?= $tahun ?></div>
    </div>
    <div class="stat-card success">
        <div class="stat-card-label">
            <i class="fas fa-check-circle"></i> Hadir
        </div>
        <div class="stat-card-value"><?= $hadir ?></div>
    </div>
    <div class="stat-card warning">
        <div class="stat-card-label">
            <i class="fas fa-hourglass-start"></i> Terlambat
        </div>
        <div class="stat-card-value"><?= $terlambat ?></div>
    </div>
    <div class="stat-card info">
        <div class="stat-card-label">
            <i class="fas fa-list"></i> Total
        </div>
        <div class="stat-card-value"><?= $total ?></div>
    </div>
</div>

<!-- Tabel Riwayat -->
<div class="card-modern">
    <div class="card-header-modern">
        <i class="fas fa-table"></i> Detail Absensi
    </div>
    <div class="card-body-modern">
        <div style="overflow-x: auto;">
            <table class="table-modern">
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
                            <td colspan="6" style="text-align: center; padding: var(--spacing-xl); color: var(--color-text-tertiary);">
                                Belum ada data absensi untuk bulan ini
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
