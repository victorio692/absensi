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

    .form-wrapper {
        max-width: 600px;
    }

    .form-section {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-xl);
        padding: var(--spacing-2xl);
        margin-bottom: var(--spacing-2xl);
    }

    .form-group {
        margin-bottom: var(--spacing-lg);
    }

    .form-label {
        display: block;
        margin-bottom: var(--spacing-sm);
        font-weight: 700;
        color: var(--color-text);
        font-size: var(--font-sm);
        letter-spacing: 0.02em;
    }

    .form-label .text-danger {
        color: var(--color-danger);
    }

    .form-input {
        width: 100%;
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-family: inherit;
        font-size: var(--font-base);
        color: var(--color-text);
        transition: all 150ms ease-in-out;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23666'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right var(--spacing-md) center;
        background-size: 1.5em 1.5em;
        padding-right: var(--spacing-3xl);
    }

    .form-error {
        color: var(--color-danger);
        font-size: var(--font-xs);
        margin-top: var(--spacing-xs);
        display: block;
    }

    .form-helper {
        color: var(--color-text-secondary);
        font-size: var(--font-xs);
        margin-top: var(--spacing-xs);
        display: block;
    }

    .form-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-md);
        margin-top: var(--spacing-2xl);
        padding-top: var(--spacing-2xl);
        border-top: 1px solid var(--color-border);
    }

    .btn-modern {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--spacing-sm);
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius-lg);
        font-weight: 700;
        font-size: var(--font-base);
        cursor: pointer;
        transition: all 150ms ease-in-out;
        text-decoration: none;
    }

    .btn-primary {
        background-color: var(--color-primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--color-primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-secondary {
        background-color: var(--color-surface-hover);
        color: var(--color-text);
        border: 1px solid var(--color-border);
    }

    .btn-secondary:hover {
        background-color: var(--color-background-secondary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    @media (max-width: 768px) {
        .form-wrapper {
            max-width: 100%;
        }

        .form-actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-plus-circle"></i> Tambah Siswa Baru
    </h1>
</div>

<div class="form-wrapper">
    <div class="form-section">
        <form action="/admin/siswa/store" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nama" class="form-label">
                    Nama Lengkap <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-input" id="nama" name="nama" value="<?= old('nama') ?>" placeholder="Masukkan nama lengkap" required>
                <?php if (isset($errors['nama'])): ?>
                    <span class="form-error"><?= $errors['nama'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="username" class="form-label">
                    Username <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-input" id="username" name="username" value="<?= old('username') ?>" placeholder="Masukkan username" required>
                <?php if (isset($errors['username'])): ?>
                    <span class="form-error"><?= $errors['username'] ?></span>
                <?php endif; ?>
                <span class="form-helper">Gunakan username unik untuk login siswa</span>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    Password <span class="text-danger">*</span>
                </label>
                <input type="password" class="form-input" id="password" name="password" placeholder="Masukkan password" required>
                <?php if (isset($errors['password'])): ?>
                    <span class="form-error"><?= $errors['password'] ?></span>
                <?php endif; ?>
                <span class="form-helper">Minimal 6 karakter dengan kombinasi huruf dan angka</span>
            </div>

            <div class="form-group">
                <label for="nis" class="form-label">
                    NIS <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-input" id="nis" name="nis" value="<?= old('nis') ?>" placeholder="Nomor Induk Siswa" required>
                <?php if (isset($errors['nis'])): ?>
                    <span class="form-error"><?= $errors['nis'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="kelas" class="form-label">
                    Kelas <span class="text-danger">*</span>
                </label>
                <select class="form-input form-select" id="kelas" name="kelas" required>
                    <option value="">-- Pilih Kelas --</option>
                    <option value="10 RPL A" <?= old('kelas') === '10 RPL A' ? 'selected' : '' ?>>10 RPL A</option>
                    <option value="10 RPL B" <?= old('kelas') === '10 RPL B' ? 'selected' : '' ?>>10 RPL B</option>
                    <option value="11 RPL A" <?= old('kelas') === '11 RPL A' ? 'selected' : '' ?>>11 RPL A</option>
                    <option value="11 RPL B" <?= old('kelas') === '11 RPL B' ? 'selected' : '' ?>>11 RPL B</option>
                    <option value="12 RPL A" <?= old('kelas') === '12 RPL A' ? 'selected' : '' ?>>12 RPL A</option>
                    <option value="12 RPL B" <?= old('kelas') === '12 RPL B' ? 'selected' : '' ?>>12 RPL B</option>
                </select>
                <?php if (isset($errors['kelas'])): ?>
                    <span class="form-error"><?= $errors['kelas'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-modern btn-primary">
                    <i class="fas fa-save"></i> Simpan Siswa
                </button>
                <a href="/admin/siswa" class="btn-modern btn-secondary">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
