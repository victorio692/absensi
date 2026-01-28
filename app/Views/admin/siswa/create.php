<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1 class="mb-4"><i class="fas fa-plus"></i> Tambah Siswa</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="/admin/siswa/store" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= old('nama') ?>" required>
                        <?php if (isset($errors['nama'])): ?>
                            <div class="text-danger small mt-1"><?= $errors['nama'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>" required>
                        <?php if (isset($errors['username'])): ?>
                            <div class="text-danger small mt-1"><?= $errors['username'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <?php if (isset($errors['password'])): ?>
                            <div class="text-danger small mt-1"><?= $errors['password'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nis" name="nis" value="<?= old('nis') ?>" required>
                        <?php if (isset($errors['nis'])): ?>
                            <div class="text-danger small mt-1"><?= $errors['nis'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select class="form-select" id="kelas" name="kelas" required>
                            <option value="">-- Pilih Kelas --</option>
                            <option value="10 RPL A" <?= old('kelas') === '10 RPL A' ? 'selected' : '' ?>>10 RPL A</option>
                            <option value="10 RPL B" <?= old('kelas') === '10 RPL B' ? 'selected' : '' ?>>10 RPL B</option>
                            <option value="11 RPL A" <?= old('kelas') === '11 RPL A' ? 'selected' : '' ?>>11 RPL A</option>
                            <option value="11 RPL B" <?= old('kelas') === '11 RPL B' ? 'selected' : '' ?>>11 RPL B</option>
                            <option value="12 RPL A" <?= old('kelas') === '12 RPL A' ? 'selected' : '' ?>>12 RPL A</option>
                            <option value="12 RPL B" <?= old('kelas') === '12 RPL B' ? 'selected' : '' ?>>12 RPL B</option>
                        </select>
                        <?php if (isset($errors['kelas'])): ?>
                            <div class="text-danger small mt-1"><?= $errors['kelas'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="/admin/siswa" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
