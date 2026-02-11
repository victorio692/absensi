<?php helper('absensi_helper'); ?>

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

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: var(--spacing-2xl);
    }

    .form-section {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-xl);
        padding: var(--spacing-2xl);
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

    .form-input.is-invalid {
        border-color: var(--color-danger);
    }

    .form-helper {
        color: var(--color-text-secondary);
        font-size: var(--font-xs);
        margin-top: var(--spacing-xs);
        display: block;
    }

    .invalid-feedback {
        color: var(--color-danger);
        font-size: var(--font-xs);
        margin-top: var(--spacing-xs);
        display: block;
    }

    .info-alert {
        background: rgba(59, 130, 246, 0.1);
        border-left: 4px solid var(--color-info);
        padding: var(--spacing-lg);
        border-radius: var(--radius-lg);
        color: var(--color-info);
        margin-bottom: var(--spacing-2xl);
        display: flex;
        gap: var(--spacing-md);
        align-items: flex-start;
    }

    .info-alert i {
        flex-shrink: 0;
        margin-top: 2px;
    }

    .info-alert strong {
        color: var(--color-text);
    }

    .form-actions {
        display: flex;
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

    .sidebar-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-xl);
        padding: var(--spacing-lg);
    }

    .sidebar-card h5 {
        margin: 0 0 var(--spacing-md);
        font-weight: 800;
        color: var(--color-text);
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
    }

    .sidebar-card hr {
        margin: var(--spacing-md) 0;
        border: none;
        border-top: 1px solid var(--color-border);
    }

    .sidebar-card strong {
        display: block;
        color: var(--color-text);
        margin: var(--spacing-md) 0 var(--spacing-sm);
    }

    .sidebar-card ul {
        margin: 0 0 var(--spacing-md);
        padding-left: var(--spacing-lg);
        color: var(--color-text-secondary);
        font-size: var(--font-xs);
    }

    .sidebar-card ul li {
        margin-bottom: var(--spacing-xs);
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-cog"></i> Pengaturan Jam Sekolah
    </h1>
</div>

<div class="content-grid">
    <div class="form-section">
        <form action="/admin/pengaturan/update" method="POST">
            <?= csrf_field() ?>

            <div class="info-alert">
                <i class="fas fa-lightbulb"></i>
                <div>
                    <strong>Informasi Penting</strong>
                    <p style="margin: var(--spacing-xs) 0 0;">Pengaturan jam ini akan mempengaruhi status absensi siswa (Hadir, Terlambat, Alpha).</p>
                </div>
            </div>

            <div class="form-group">
                <label for="jam_masuk" class="form-label">Jam Mulai Masuk <span class="text-danger">*</span></label>
                <input type="time" class="form-input <?= isset($errors['jam_masuk']) ? 'is-invalid' : '' ?>" 
                       id="jam_masuk" name="jam_masuk" value="<?= $pengaturan['jam_masuk'] ?>" required>
                <span class="form-helper">Jam dimulainya sistem absensi masuk</span>
                <?php if (isset($errors['jam_masuk'])): ?>
                    <span class="invalid-feedback"><?= $errors['jam_masuk'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="batas_terlambat" class="form-label">Batas Maksimal Jam Masuk (Terlambat) <span class="text-danger">*</span></label>
                <input type="time" class="form-input <?= isset($errors['batas_terlambat']) ? 'is-invalid' : '' ?>" 
                       id="batas_terlambat" name="batas_terlambat" value="<?= $pengaturan['batas_terlambat'] ?>" required>
                <span class="form-helper">Jika masuk setelah jam ini, siswa dianggap terlambat</span>
                <?php if (isset($errors['batas_terlambat'])): ?>
                    <span class="invalid-feedback"><?= $errors['batas_terlambat'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="jam_pulang" class="form-label">Jam Mulai Pulang <span class="text-danger">*</span></label>
                <input type="time" class="form-input <?= isset($errors['jam_pulang']) ? 'is-invalid' : '' ?>" 
                       id="jam_pulang" name="jam_pulang" value="<?= $pengaturan['jam_pulang'] ?>" required>
                <span class="form-helper">Jam dimulainya sistem absensi pulang</span>
                <?php if (isset($errors['jam_pulang'])): ?>
                    <span class="invalid-feedback"><?= $errors['jam_pulang'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="batas_alpha" class="form-label">Batas Jam untuk Alpha Otomatis <span class="text-danger">*</span></label>
                <input type="time" class="form-input <?= isset($errors['batas_alpha']) ? 'is-invalid' : '' ?>" 
                       id="batas_alpha" name="batas_alpha" value="<?= $pengaturan['batas_alpha'] ?>" required>
                <span class="form-helper">Jika jam sekarang melewati batas ini dan siswa belum absen, sistem otomatis menandai Alpha</span>
                <?php if (isset($errors['batas_alpha'])): ?>
                    <span class="invalid-feedback"><?= $errors['batas_alpha'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-modern btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
                <a href="/admin/dashboard" class="btn-modern btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>

    <div class="sidebar-card">
        <h5><i class="fas fa-lightbulb"></i> Contoh</h5>
        <hr>
        <strong>Skenario Sekolah:</strong>
        <ul>
            <li><strong>Jam Masuk:</strong> 07:00</li>
            <li><strong>Batas Terlambat:</strong> 08:00</li>
            <li><strong>Jam Pulang:</strong> 15:00</li>
            <li><strong>Batas Alpha:</strong> 10:00</li>
        </ul>
        <hr>
        <strong>Cara Kerja:</strong>
        <ul>
            <li>Masuk 07:30 → Hadir</li>
            <li>Masuk 08:30 → Terlambat</li>
            <li>Belum absen jam 10:00 → Alpha</li>
            <li>Pulang 15:30 → Tercatat</li>
        </ul>
    </div>
</div>

<?= $this->endSection() ?>
