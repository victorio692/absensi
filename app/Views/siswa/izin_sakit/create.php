<?php echo $this->extend('layout'); ?>

<?php echo $this->section('content'); ?>

<style>
    .izin-form-wrapper {
        max-width: 700px;
        margin: 0 auto;
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

    .form-group:last-of-type {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--color-text);
        margin-bottom: var(--spacing-sm);
        font-size: var(--font-base);
    }

    .form-label .text-danger {
        color: var(--color-danger);
    }

    .form-control, .form-select {
        width: 100%;
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-family: inherit;
        font-size: var(--font-base);
        color: var(--color-text);
        background-color: var(--color-surface);
        transition: all 150ms ease-in-out;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%234F46E5' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right var(--spacing-lg) center;
        padding-right: var(--spacing-3xl);
        cursor: pointer;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    .form-hint {
        display: block;
        font-size: var(--font-xs);
        color: var(--color-text-secondary);
        margin-top: var(--spacing-xs);
    }

    .two-column {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-lg);
    }

    @media (max-width: 768px) {
        .two-column {
            grid-template-columns: 1fr;
        }
        
        .form-section {
            padding: var(--spacing-lg);
        }
    }

    .alert-box {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(79, 70, 229, 0.05) 100%);
        border-left: 4px solid var(--color-primary);
        border-radius: var(--radius-lg);
        padding: var(--spacing-lg);
        margin: var(--spacing-2xl) 0;
        color: var(--color-text);
    }

    .alert-box strong {
        color: var(--color-primary);
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
        margin-bottom: var(--spacing-md);
    }

    .alert-box ul {
        margin: 0;
        padding-left: var(--spacing-lg);
    }

    .alert-box li {
        margin-bottom: var(--spacing-sm);
        color: var(--color-text-secondary);
    }

    .form-actions {
        display: flex;
        gap: var(--spacing-md);
        justify-content: flex-end;
        margin-top: var(--spacing-2xl);
        padding-top: var(--spacing-2xl);
        border-top: 1px solid var(--color-border);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-sm);
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--radius-lg);
        font-weight: 600;
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
        box-shadow: 0 8px 16px rgba(79, 70, 229, 0.25);
    }

    .btn-secondary {
        background-color: var(--color-surface-hover);
        color: var(--color-text);
        border: 1px solid var(--color-border);
    }

    .btn-secondary:hover {
        background-color: var(--color-background);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    @media (max-width: 640px) {
        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-file-medical-alt"></i> Pengajuan Izin / Sakit
    </h1>
</div>

<div class="izin-form-wrapper">
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger d-mb-3" role="alert" style="background-color: rgba(220, 38, 38, 0.1); border-left: 4px solid #DC2626; padding: var(--spacing-lg); border-radius: var(--radius-lg); margin-bottom: var(--spacing-lg);">
            <i class="fas fa-exclamation-circle" style="color: #DC2626;"></i>
            <strong style="color: #DC2626;"><?php echo session('error'); ?></strong>
        </div>
    <?php endif; ?>

    <form class="form-section" action="/siswa/izin-sakit-store" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="two-column">
            <div class="form-group">
                <label for="jenis" class="form-label">
                    Jenis Pengajuan <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="jenis" name="jenis" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="izin" <?php echo old('jenis') === 'izin' ? 'selected' : ''; ?>>Izin</option>
                    <option value="sakit" <?php echo old('jenis') === 'sakit' ? 'selected' : ''; ?>>Sakit</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal" class="form-label">
                    Tanggal <span class="text-danger">*</span>
                </label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" 
                       value="<?php echo old('tanggal') ?? date('Y-m-d'); ?>" required>
                <span class="form-hint">
                    <i class="fas fa-info-circle"></i> Pengajuan untuk hari ini harus sebelum jam 07:00
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="alasan" class="form-label">
                Alasan <span class="text-danger">*</span>
            </label>
            <textarea class="form-control" id="alasan" name="alasan" 
                      placeholder="Jelaskan alasan izin/sakit Anda..." required 
                      minlength="5" maxlength="500"><?php echo old('alasan'); ?></textarea>
            <span class="form-hint">Minimum 5 karakter, maksimal 500 karakter</span>
        </div>

        <div class="form-group">
            <label for="bukti_file" class="form-label">
                Upload Bukti (Opsional)
            </label>
            <input type="file" class="form-control" id="bukti_file" name="bukti_file" accept=".jpg,.jpeg,.png,.pdf">
            <span class="form-hint">
                <i class="fas fa-info-circle"></i> Format: JPG, PNG, PDF | Maksimal ukuran: 5 MB
            </span>
        </div>

        <div class="alert-box">
            <strong>
                <i class="fas fa-lightbulb"></i>Catatan Penting
            </strong>
            <ul>
                <li>Pengajuan izin/sakit untuk hari ini harus dilakukan sebelum jam 07:00</li>
                <li>Anda hanya bisa mengajukan 1 izin/sakit per hari</li>
                <li>Setelah disetujui, status absensi Anda akan otomatis berubah</li>
                <li>Jika ditolak, Anda wajib melakukan absensi QR</li>
            </ul>
        </div>

        <div class="form-actions">
            <a href="/siswa/izin-sakit-riwayat" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check"></i>Kirim Pengajuan
            </button>
        </div>
    </form>
</div>

<?php echo $this->endSection(); ?>
