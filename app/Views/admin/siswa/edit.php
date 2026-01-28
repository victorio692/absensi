<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1 class="mb-4"><i class="fas fa-edit"></i> Edit Siswa</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="/admin/siswa/<?= $siswa['id'] ?>/update" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $siswa['nama'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= $siswa['username'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nis" name="nis" value="<?= $siswa['nis'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select class="form-select" id="kelas" name="kelas" required>
                            <option value="10 RPL A" <?= $siswa['kelas'] === '10 RPL A' ? 'selected' : '' ?>>10 RPL A</option>
                            <option value="10 RPL B" <?= $siswa['kelas'] === '10 RPL B' ? 'selected' : '' ?>>10 RPL B</option>
                            <option value="11 RPL A" <?= $siswa['kelas'] === '11 RPL A' ? 'selected' : '' ?>>11 RPL A</option>
                            <option value="11 RPL B" <?= $siswa['kelas'] === '11 RPL B' ? 'selected' : '' ?>>11 RPL B</option>
                            <option value="12 RPL A" <?= $siswa['kelas'] === '12 RPL A' ? 'selected' : '' ?>>12 RPL A</option>
                            <option value="12 RPL B" <?= $siswa['kelas'] === '12 RPL B' ? 'selected' : '' ?>>12 RPL B</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Simpan Perubahan
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
