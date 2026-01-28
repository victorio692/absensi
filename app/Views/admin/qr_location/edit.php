<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">
                <i class="fas fa-edit text-warning"></i> Edit Lokasi QR Code
            </h2>
            <p class="text-muted">Perbarui informasi lokasi QR Code</p>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <strong>Error:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errors as $field => $message): ?>
                    <li><?= $message ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marker-alt"></i> Form Edit Lokasi
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Error Alert -->
                    <?php if (isset($validation) && count($validation->getErrors()) > 0): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <strong>Validasi Gagal:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($validation->getErrors() as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/qr-location/' . $location['id'] . '/update') ?>" method="post">
                        <?= csrf_field() ?>

                        <!-- Nama Lokasi -->
                        <div class="mb-3">
                            <label for="nama_lokasi" class="form-label fw-bold">Nama Lokasi *</label>
                            <input type="text" class="form-control <?= isset($validation) && $validation->hasError('nama_lokasi') ? 'is-invalid' : '' ?>"
                                   id="nama_lokasi" name="nama_lokasi"
                                   value="<?= htmlspecialchars($location['nama_lokasi']) ?>" required>
                            <?php if (isset($validation) && $validation->hasError('nama_lokasi')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('nama_lokasi') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= htmlspecialchars($location['keterangan'] ?? '') ?></textarea>
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="aktif" name="aktif" value="1"
                                       <?= $location['aktif'] ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="aktif">
                                    Lokasi Aktif
                                </label>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="alert alert-light border mb-3">
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> Dibuat: <?= tanggalIndo($location['created_at']) ?><br>
                                <i class="fas fa-sync"></i> Diubah: <?= tanggalIndo($location['updated_at']) ?>
                            </small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning btn-lg flex-grow-1">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="<?= base_url('admin/qr-location') ?>" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
