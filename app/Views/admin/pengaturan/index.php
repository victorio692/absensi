<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1 class="mb-4"><i class="fas fa-cog"></i> Pengaturan Jam Sekolah</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="/admin/pengaturan/update" method="POST">
                    <?= csrf_field() ?>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Informasi:</strong> Pengaturan jam ini akan mempengaruhi status absensi siswa (Hadir, Terlambat, Alpha).
                    </div>

                    <div class="mb-3">
                        <label for="jam_masuk" class="form-label">Jam Mulai Masuk <span class="text-danger">*</span></label>
                        <input type="time" class="form-control <?= isset($errors['jam_masuk']) ? 'is-invalid' : '' ?>" 
                               id="jam_masuk" name="jam_masuk" value="<?= $pengaturan['jam_masuk'] ?>" required>
                        <small class="text-muted d-block mt-2">Jam dimulainya sistem absensi masuk</small>
                        <?php if (isset($errors['jam_masuk'])): ?>
                            <div class="invalid-feedback d-block"><?= $errors['jam_masuk'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="batas_terlambat" class="form-label">Batas Maksimal Jam Masuk (Terlambat) <span class="text-danger">*</span></label>
                        <input type="time" class="form-control <?= isset($errors['batas_terlambat']) ? 'is-invalid' : '' ?>" 
                               id="batas_terlambat" name="batas_terlambat" value="<?= $pengaturan['batas_terlambat'] ?>" required>
                        <small class="text-muted d-block mt-2">Jika masuk setelah jam ini, siswa dianggap terlambat</small>
                        <?php if (isset($errors['batas_terlambat'])): ?>
                            <div class="invalid-feedback d-block"><?= $errors['batas_terlambat'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="jam_pulang" class="form-label">Jam Mulai Pulang <span class="text-danger">*</span></label>
                        <input type="time" class="form-control <?= isset($errors['jam_pulang']) ? 'is-invalid' : '' ?>" 
                               id="jam_pulang" name="jam_pulang" value="<?= $pengaturan['jam_pulang'] ?>" required>
                        <small class="text-muted d-block mt-2">Jam dimulainya sistem absensi pulang</small>
                        <?php if (isset($errors['jam_pulang'])): ?>
                            <div class="invalid-feedback d-block"><?= $errors['jam_pulang'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="batas_alpha" class="form-label">Batas Jam untuk Alpha Otomatis <span class="text-danger">*</span></label>
                        <input type="time" class="form-control <?= isset($errors['batas_alpha']) ? 'is-invalid' : '' ?>" 
                               id="batas_alpha" name="batas_alpha" value="<?= $pengaturan['batas_alpha'] ?>" required>
                        <small class="text-muted d-block mt-2">Jika jam sekarang melewati batas ini dan siswa belum absen, sistem otomatis menandai Alpha</small>
                        <?php if (isset($errors['batas_alpha'])): ?>
                            <div class="invalid-feedback d-block"><?= $errors['batas_alpha'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Pengaturan
                        </button>
                        <a href="/admin/dashboard" class="btn btn-secondary">
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
                <h5 class="card-title"><i class="fas fa-lightbulb"></i> Contoh Pengaturan</h5>
                <hr>
                <strong>Skenario Sekolah:</strong>
                <ul class="small">
                    <li><strong>Jam Masuk:</strong> 07:00</li>
                    <li><strong>Batas Terlambat:</strong> 08:00</li>
                    <li><strong>Jam Pulang:</strong> 15:00</li>
                    <li><strong>Batas Alpha:</strong> 10:00</li>
                </ul>
                <hr>
                <strong>Cara Kerja:</strong>
                <ul class="small">
                    <li>Siswa masuk jam 07:30 → Hadir</li>
                    <li>Siswa masuk jam 08:30 → Terlambat</li>
                    <li>Siswa belum absen jam 10:00 → Alpha</li>
                    <li>Siswa pulang jam 15:30 → Tercatat</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
