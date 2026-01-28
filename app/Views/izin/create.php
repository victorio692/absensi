<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1 class="mb-4"><i class="fas fa-file-medical"></i> Ajukan Izin/Sakit</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="/izin/store" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Catatan:</strong> Izin/Sakit hanya dapat diajukan untuk hari ini atau kemarin.
                    </div>

                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control <?= isset($errors['tanggal']) ? 'is-invalid' : '' ?>" 
                               id="tanggal" name="tanggal" value="<?= old('tanggal') ?>" required>
                        <?php if (isset($errors['tanggal'])): ?>
                            <div class="invalid-feedback d-block"><?= $errors['tanggal'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis <span class="text-danger">*</span></label>
                        <select class="form-select <?= isset($errors['jenis']) ? 'is-invalid' : '' ?>" 
                                id="jenis" name="jenis" required>
                            <option value="">Pilih Jenis</option>
                            <option value="izin" <?= old('jenis') === 'izin' ? 'selected' : '' ?>>Izin</option>
                            <option value="sakit" <?= old('jenis') === 'sakit' ? 'selected' : '' ?>>Sakit</option>
                        </select>
                        <?php if (isset($errors['jenis'])): ?>
                            <div class="invalid-feedback d-block"><?= $errors['jenis'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <textarea class="form-control <?= isset($errors['keterangan']) ? 'is-invalid' : '' ?>" 
                                  id="keterangan" name="keterangan" rows="4" placeholder="Jelaskan alasan izin/sakit" 
                                  required><?= old('keterangan') ?></textarea>
                        <?php if (isset($errors['keterangan'])): ?>
                            <div class="invalid-feedback d-block"><?= $errors['keterangan'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="file_bukti" class="form-label">Bukti (Foto/PDF) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control <?= isset($errors['file_bukti']) ? 'is-invalid' : '' ?>" 
                               id="file_bukti" name="file_bukti" accept=".jpg,.jpeg,.png,.gif,.pdf" required>
                        <small class="text-muted d-block mt-2">
                            Format: JPG, PNG, GIF, atau PDF. Maksimal 5MB.
                        </small>
                        <?php if (isset($errors['file_bukti'])): ?>
                            <div class="invalid-feedback d-block"><?= $errors['file_bukti'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Ajukan
                        </button>
                        <a href="/izin" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-lightbulb"></i> Tips</h5>
                <ul class="small">
                    <li>Siapkan dokumen pendukung (surat izin, surat dokter, dll)</li>
                    <li>Upload dalam format yang jelas dan terbaca</li>
                    <li>Pastikan data yang Anda isi akurat</li>
                    <li>Tunggu verifikasi dari admin</li>
                    <li>Status akan diubah menjadi "Disetujui" atau "Ditolak"</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
