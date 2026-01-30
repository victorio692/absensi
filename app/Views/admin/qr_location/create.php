<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-2">
                <i class="fas fa-plus-circle text-primary"></i> Tambah Lokasi QR Code
            </h2>
            <p class="text-muted">Tambahkan lokasi baru untuk QR Code sekolah</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marker-alt"></i> Form Lokasi QR Code
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Session Error Alert -->
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-times-circle"></i> <strong>Error:</strong> <?= session('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Validation Error Alert -->
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

                    <form action="<?= base_url('admin/qr-location/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <!-- Nama Lokasi -->
                        <div class="mb-3">
                            <label for="nama_lokasi" class="form-label fw-bold">Nama Lokasi *</label>
                            <input type="text" class="form-control <?= isset($validation) && $validation->hasError('nama_lokasi') ? 'is-invalid' : '' ?>"
                                   id="nama_lokasi" name="nama_lokasi" 
                                   placeholder="Contoh: Gerbang Utama, Aula, Perpustakaan"
                                   value="<?= old('nama_lokasi') ?>" required>
                            <?php if (isset($validation) && $validation->hasError('nama_lokasi')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('nama_lokasi') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                                      placeholder="Keterangan tambahan tentang lokasi ini"><?= old('keterangan') ?></textarea>
                            <small class="text-muted">Opsional - deskripsi singkat tentang lokasi</small>
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="aktif" name="aktif" value="1" checked>
                                <label class="form-check-label fw-bold" for="aktif">
                                    Lokasi Aktif
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">Lokasi aktif akan digunakan untuk generate QR harian</small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="fas fa-save"></i> Simpan Lokasi
                            </button>
                            <a href="<?= base_url('admin/qr-location') ?>" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="col-md-4">
            <div class="card border-0 bg-light">
                <div class="card-header bg-light border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle text-info"></i> Panduan
                    </h6>
                </div>
                <div class="card-body small">
                    <p><strong>Lokasi QR Code</strong> adalah tempat-tempat fisik di sekolah di mana QR Code akan dipasang untuk absensi siswa.</p>
                    
                    <hr>
                    
                    <p><strong>Contoh Lokasi:</strong></p>
                    <ul class="mb-0">
                        <li>Gerbang Utama</li>
                        <li>Aula Sekolah</li>
                        <li>Perpustakaan</li>
                        <li>Kantor Tata Usaha</li>
                        <li>Ruang Guru</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
