<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1 class="mb-4"><i class="fas fa-map-marker-alt"></i> Lokasi Absensi</h1>

<?php if (session()->has('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?= session('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($locations)): ?>
    <div class="row">
        <?php foreach ($locations as $loc): ?>
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">
                                    <i class="fas fa-location-dot text-danger"></i>
                                    <?= $loc['nama_lokasi'] ?>
                                </h5>
                                <small class="text-muted"><?= $loc['keterangan'] ?></small>
                            </div>
                            <span class="badge <?= $loc['aktif'] ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $loc['aktif'] ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <!-- Edit -->
                            <a href="<?= base_url('admin/qr-location/' . $loc['id'] . '/edit') ?>" 
                               class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit Lokasi
                            </a>

                            <!-- Cetak QR -->
                            <a href="<?= base_url('admin/qr-daily/' . $loc['id'] . '/show') ?>" 
                               class="btn btn-outline-info btn-sm <?= !$loc['aktif'] ? 'disabled' : '' ?>"
                               <?= !$loc['aktif'] ? 'onclick="return false;"' : '' ?>>
                                <i class="fas fa-print"></i> Cetak QR Code
                            </a>

                            <!-- Monitor - Hanya tampil jika aktif -->
                            <?php if ($loc['aktif']): ?>
                                <a href="<?= base_url('admin/monitor/display/' . $loc['id']) ?>" 
                                   class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="fas fa-tv"></i> Monitor Display
                                </a>
                            <?php else: ?>
                                <button class="btn btn-outline-secondary btn-sm disabled">
                                    <i class="fas fa-tv"></i> Monitor (Nonaktif)
                                </button>
                            <?php endif; ?>

                            <!-- Hapus -->
                            <a href="<?= base_url('admin/qr-location/' . $loc['id'] . '/delete') ?>" 
                               class="btn btn-outline-danger btn-sm"
                               onclick="return confirm('Yakin hapus lokasi ini? Data absensi akan tetap tersimpan.')">
                                <i class="fas fa-trash"></i> Hapus Lokasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <a href="<?= base_url('admin/qr-location/create') ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle"></i> Tambah Lokasi Baru
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
            <p class="text-muted mt-3">Belum ada lokasi absensi</p>
            <a href="<?= base_url('admin/qr-location/create') ?>" class="btn btn-primary mt-3">
                <i class="fas fa-plus"></i> Tambah Lokasi Pertama
            </a>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
