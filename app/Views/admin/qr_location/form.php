<?php $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1 class="mb-4">
    <i class="fas fa-map-marker-alt"></i> 
    <?= isset($lokasi) ? 'Edit' : 'Tambah' ?> Lokasi QR
</h1>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> <strong>Validasi Gagal:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach (session('errors') as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= isset($lokasi) ? '/admin/qr-location/' . $lokasi['id'] . '/update' : '/admin/qr-location/store' ?>" class="needs-validation">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="nama_lokasi" class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                <input type="text" class="form-control <?= session('errors.nama_lokasi') ? 'is-invalid' : '' ?>" 
                       id="nama_lokasi" name="nama_lokasi" 
                       value="<?= isset($lokasi) ? $lokasi['nama_lokasi'] : old('nama_lokasi') ?>" 
                       placeholder="Contoh: Gerbang Masuk, Aula, Lapangan"
                       required>
                <small class="text-muted d-block mt-2">
                    <i class="fas fa-lightbulb"></i> Berikan nama yang jelas untuk lokasi QR ini
                </small>
            </div>

            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="aktif" name="aktif" value="1"
                           <?= (isset($lokasi) && $lokasi['aktif']) || !isset($lokasi) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="aktif">
                        Lokasi Aktif
                    </label>
                </div>
                <small class="text-muted d-block mt-2">
                    <i class="fas fa-info-circle"></i> Hanya lokasi aktif yang akan mendapatkan QR Code
                </small>
            </div>

            <div class="mt-4">
                <a href="/admin/qr-location" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= isset($lokasi) ? 'Perbarui' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
