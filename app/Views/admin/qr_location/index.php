<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1 class="mb-4"><i class="fas fa-map-marker-alt"></i> Lokasi QR Code</h1>

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

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list"></i> Daftar Lokasi QR</span>
        <a href="/admin/qr-location/create" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Lokasi
        </a>
    </div>
    <div class="card-body">
        <?php if (!empty($locations)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($locations as $loc): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <i class="fas fa-location-dot"></i> 
                                    <strong><?= $loc['nama_lokasi'] ?></strong>
                                </td>
                                <td>
                                    <?php if ($loc['aktif']): ?>
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><i class="fas fa-times-circle"></i> Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/admin/qr-location/<?= $loc['id'] ?>/edit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="/admin/qr-daily/<?= $loc['id'] ?>/show" class="btn btn-info btn-sm">
                                        <i class="fas fa-qrcode"></i> Cetak QR
                                    </a>
                                    <a href="/admin/qr-location/<?= $loc['id'] ?>/delete" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> Belum ada lokasi QR. <a href="/admin/qr-location/create">Tambah sekarang</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5><i class="fas fa-info-circle"></i> Informasi</h5>
        <p class="text-muted mb-0">
            Lokasi QR adalah tempat-tempat di sekolah tempat QR Code fisik dipasang (contoh: Gerbang, Aula, Ruang Kelas, dll). 
            Setiap lokasi akan memiliki QR Code yang berbeda dan berubah setiap hari.
        </p>
    </div>
</div>

<?= $this->endSection() ?>
