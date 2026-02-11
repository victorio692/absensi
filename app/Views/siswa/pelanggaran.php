<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<style>
    .page-header {
        margin-bottom: var(--spacing-3xl);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--spacing-lg);
        flex-wrap: wrap;
    }

    .page-header h1 {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        font-weight: 800;
        color: var(--color-text);
        margin: 0;
    }

    .filter-section {
        display: flex;
        gap: var(--spacing-sm);
        align-items: center;
    }

    .filter-input {
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-family: inherit;
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
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--color-border);
        display: flex;
        align-items: center;
        gap: var(--spacing-lg);
    }

    .stat-card-icon {
        font-size: 2.5rem;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-lg);
        flex-shrink: 0;
    }

    .stat-card.warning .stat-card-icon {
        background: rgba(245, 158, 11, 0.1);
        color: var(--color-warning);
    }

    .stat-card.danger .stat-card-icon {
        background: rgba(239, 68, 68, 0.1);
        color: var(--color-danger);
    }

    .stat-card-content h3 {
        margin: 0;
        font-size: var(--font-2xl);
        font-weight: 800;
        color: var(--color-text);
    }

    .stat-card-content p {
        margin: var(--spacing-sm) 0 0;
        color: var(--color-text-secondary);
        font-size: var(--font-sm);
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

    .badge-modern {
        display: inline-block;
        padding: var(--spacing-sm) var(--spacing-md);
        border-radius: var(--radius-md);
        font-size: var(--font-xs);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-modern.danger {
        background: rgba(239, 68, 68, 0.15);
        color: var(--color-danger);
    }

    .badge-modern.warning {
        background: rgba(245, 158, 11, 0.15);
        color: var(--color-warning);
    }

    .empty-state {
        text-align: center;
        padding: var(--spacing-3xl);
        color: var(--color-text-tertiary);
    }

    .empty-state i {
        font-size: var(--font-3xl);
        color: var(--color-success);
        margin-bottom: var(--spacing-md);
        display: block;
    }

    .info-box {
        background: rgba(59, 130, 246, 0.1);
        border-left: 4px solid var(--color-info);
        padding: var(--spacing-lg);
        border-radius: var(--radius-lg);
        color: var(--color-info);
    }

    .info-box strong {
        display: block;
        color: var(--color-text);
        margin-bottom: var(--spacing-sm);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .filter-section {
            width: 100%;
        }

        .filter-input {
            flex: 1;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-exclamation-triangle"></i> Riwayat Pelanggaran Absensi
    </h1>
    <form action="/siswa/pelanggaran" method="GET" class="filter-section">
        <input type="month" name="bulan" value="<?= $bulanTerpilih ?>" class="filter-input">
        <button type="submit" class="btn-modern btn-primary">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>
</div>

<!-- Ringkasan Pelanggaran -->
<div class="stats-grid">
    <div class="stat-card warning">
        <div class="stat-card-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-card-content">
            <h3><?= $totalPelanggaran['Terlambat'] ?? 0 ?></h3>
            <p>Total Keterlambatan</p>
        </div>
    </div>
    <div class="stat-card danger">
        <div class="stat-card-icon">
            <i class="fas fa-ban"></i>
        </div>
        <div class="stat-card-content">
            <h3><?= $totalPelanggaran['Alpha'] ?? 0 ?></h3>
            <p>Total Alpha</p>
        </div>
    </div>
</div>

<!-- Tabel Detail Pelanggaran -->
<div class="card-modern" style="margin-bottom: var(--spacing-3xl);">
    <div class="card-header-modern">
        <i class="fas fa-list"></i> Detail Pelanggaran Bulan <?= tanggalIndo($bulanTerpilih . '-01', false, 'F Y') ?>
    </div>
    <div class="card-body-modern">
        <div style="overflow-x: auto;">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Bulan</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pelanggaran)): ?>
                        <?php $no = 1; foreach ($pelanggaran as $p): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= tanggalIndo($p['bulan'], false, 'F Y') ?></td>
                                <td>
                                    <span class="badge-modern <?= $p['jenis'] === 'Alpha' ? 'danger' : 'warning' ?>">
                                        <?= $p['jenis'] ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?= $p['jumlah'] ?></strong> kali
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-check-circle"></i>
                                    <p><strong>Sempurna!</strong></p>
                                    <p>Tidak ada pelanggaran pada bulan ini</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tips -->
<div class="info-box">
    <strong>
        <i class="fas fa-lightbulb"></i> Tips
    </strong>
    <p style="margin: var(--spacing-sm) 0 0; color: var(--color-text-secondary);">
        Jika memiliki keterlambatan atau alpha, pastikan mengajukan izin dengan bukti yang valid agar status dapat diubah menjadi "Izin".
    </p>
</div>

<?= $this->endSection() ?>
