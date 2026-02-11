<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<style>
    .page-header {
        margin-bottom: var(--spacing-3xl);
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
    }

    .page-header h1 {
        margin: 0;
        font-weight: 800;
        color: var(--color-text);
    }

    .alert-modern {
        padding: var(--spacing-lg);
        border-radius: var(--radius-lg);
        margin-bottom: var(--spacing-2xl);
        display: flex;
        gap: var(--spacing-md);
        align-items: flex-start;
    }

    .alert-modern i {
        flex-shrink: 0;
        margin-top: 2px;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border-left: 4px solid var(--color-success);
        color: var(--color-success);
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border-left: 4px solid var(--color-danger);
        color: var(--color-danger);
    }

    .location-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: all 300ms ease-in-out;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .location-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--color-primary);
    }

    .location-card-header {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
        color: white;
        padding: var(--spacing-lg);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: var(--spacing-md);
    }

    .location-card-header h4 {
        margin: 0;
        font-weight: 800;
        font-size: var(--font-lg);
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
    }

    .location-card-status {
        padding: var(--spacing-xs) var(--spacing-md);
        border-radius: var(--radius-sm);
        font-size: var(--font-xs);
        font-weight: 700;
        background: rgba(255, 255, 255, 0.2);
    }

    .location-card-desc {
        color: rgba(255, 255, 255, 0.8);
        font-size: var(--font-sm);
        margin-top: var(--spacing-xs);
    }

    .location-card-body {
        padding: var(--spacing-lg);
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
    }

    .location-card-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-sm);
    }

    .btn-compact {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--spacing-xs);
        padding: var(--spacing-sm) var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: var(--font-xs);
        cursor: pointer;
        transition: all 150ms ease-in-out;
        text-decoration: none;
        background: var(--color-background);
        color: var(--color-text);
    }

    .btn-compact:hover {
        background: var(--color-primary);
        color: white;
        border-color: var(--color-primary);
    }

    .btn-compact.danger {
        color: var(--color-danger);
    }

    .btn-compact.danger:hover {
        background: var(--color-danger);
        color: white;
    }

    .locations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-3xl);
    }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: var(--spacing-3xl);
        background: var(--color-surface);
        border: 2px dashed var(--color-border);
        border-radius: var(--radius-xl);
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--color-text-tertiary);
        display: block;
        margin-bottom: var(--spacing-md);
    }

    .empty-state p {
        color: var(--color-text-secondary);
        margin: 0;
    }

    .add-button {
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-md);
        padding: var(--spacing-md) var(--spacing-lg);
        background: var(--color-primary);
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-weight: 700;
        font-size: var(--font-base);
        cursor: pointer;
        transition: all 150ms ease-in-out;
        text-decoration: none;
    }

    .add-button:hover {
        background: var(--color-primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-map-marker-alt"></i> Lokasi Absensi
    </h1>
</div>

<?php if (session()->has('success')): ?>
    <div class="alert-modern alert-success">
        <i class="fas fa-check-circle"></i>
        <div><?= session('success') ?></div>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div class="alert-modern alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div><?= session('error') ?></div>
    </div>
<?php endif; ?>

<?php if (!empty($locations)): ?>
    <div class="locations-grid">
        <?php foreach ($locations as $loc): ?>
            <div class="location-card">
                <div class="location-card-header">
                    <div>
                        <h4><i class="fas fa-location-dot"></i> <?= $loc['nama_lokasi'] ?></h4>
                        <div class="location-card-desc"><?= $loc['keterangan'] ?: 'Lokasi absensi' ?></div>
                    </div>
                    <div class="location-card-status"><?= $loc['aktif'] ? 'Aktif' : 'Nonaktif' ?></div>
                </div>
                <div class="location-card-body">
                    <div class="location-card-actions">
                        <a href="<?= base_url('admin/qr-location/' . $loc['id'] . '/edit') ?>" class="btn-compact" title="Edit lokasi">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?= base_url('admin/qr-daily/' . $loc['id'] . '/show') ?>" class="btn-compact" title="Cetak QR Code" <?= !$loc['aktif'] ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : '' ?>>
                            <i class="fas fa-qrcode"></i> QR
                        </a>
                        <?php if ($loc['aktif']): ?>
                            <a href="<?= base_url('admin/monitor/display/' . $loc['id']) ?>" class="btn-compact" title="Monitor display" target="_blank">
                                <i class="fas fa-tv"></i> Monitor
                            </a>
                        <?php else: ?>
                            <button class="btn-compact" disabled style="opacity:0.5;cursor:not-allowed;" title="Monitor (nonaktif)">
                                <i class="fas fa-tv"></i> Monitor
                            </button>
                        <?php endif; ?>
                        <a href="<?= base_url('admin/qr-location/' . $loc['id'] . '/delete') ?>" class="btn-compact danger" onclick="return confirm('Yakin hapus? Data absensi tetap tersimpan.');" title="Hapus lokasi">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="<?= base_url('admin/qr-location/create') ?>" class="add-button">
        <i class="fas fa-plus-circle"></i> Tambah Lokasi Baru
    </a>
<?php else: ?>
    <div class="locations-grid">
        <div class="empty-state">
            <i class="fas fa-map"></i>
            <p style="font-weight: 700; margin-bottom: var(--spacing-sm);">Belum ada lokasi absensi</p>
            <p>Mulai dengan menambahkan lokasi pertama untuk QR Code absensi.</p>
            <a href="<?= base_url('admin/qr-location/create') ?>" class="add-button" style="display: inline-flex; margin-top: var(--spacing-lg);">
                <i class="fas fa-plus-circle"></i> Tambah Lokasi Pertama
            </a>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
