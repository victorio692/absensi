<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<style>
    .page-header {
        margin-bottom: var(--spacing-3xl);
    }

    .page-header h1 {
        font-weight: 800;
        color: var(--color-text);
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        margin: 0;
    }

    .page-header p {
        color: var(--color-text-secondary);
        margin: var(--spacing-sm) 0 0;
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

    .alert-modern {
        padding: var(--spacing-lg);
        border-radius: var(--radius-lg);
        margin-bottom: var(--spacing-lg);
        display: flex;
        gap: var(--spacing-md);
        align-items: flex-start;
    }

    .alert-modern i {
        flex-shrink: 0;
        margin-top: 2px;
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border-left: 4px solid var(--color-danger);
        color: var(--color-danger);
    }

    .alert-danger strong {
        color: var(--color-text);
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

    .form-input,
    .form-textarea {
        width: 100%;
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-family: inherit;
        font-size: var(--font-base);
        color: var(--color-text);
        transition: all 150ms ease-in-out;
    }

    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-helper {
        color: var(--color-text-secondary);
        font-size: var(--font-xs);
        margin-top: var(--spacing-xs);
        display: block;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
    }

    .form-check input {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .form-check-label {
        margin: 0;
        font-weight: 700;
        color: var(--color-text);
        cursor: pointer;
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

    .sidebar-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-xl);
        padding: var(--spacing-lg);
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .sidebar-card h6 {
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

    .sidebar-card p,
    .sidebar-card ul {
        margin: 0 0 var(--spacing-md);
        color: var(--color-text-secondary);
        font-size: var(--font-sm);
        line-height: 1.5;
    }

    .sidebar-card ul {
        padding-left: var(--spacing-lg);
    }

    .sidebar-card li {
        margin-bottom: var(--spacing-xs);
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }

        .sidebar-card {
            position: relative;
            top: auto;
        }
    }
</style>

<div class="page-header">
    <h1><i class="fas fa-plus-circle"></i> Tambah Lokasi QR Code</h1>
    <p>Tambahkan lokasi baru untuk QR Code sekolah</p>
</div>

<div class="content-grid">
    <div class="form-section">
        <!-- Session Error Alert -->
        <?php if (session()->has('error')): ?>
            <div class="alert-modern alert-danger">
                <i class="fas fa-times-circle"></i>
                <div>
                    <strong>Error:</strong>
                    <p style="margin: var(--spacing-xs) 0 0;"><?= session('error') ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Validation Error Alert -->
        <?php if (isset($validation) && count($validation->getErrors()) > 0): ?>
            <div class="alert-modern alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Validasi Gagal:</strong>
                    <ul style="margin: var(--spacing-sm) 0 0; padding-left: var(--spacing-lg);">
                        <?php foreach ($validation->getErrors() as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/qr-location/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nama_lokasi" class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                <input type="text" class="form-input" id="nama_lokasi" name="nama_lokasi" 
                       placeholder="Contoh: Gerbang Utama, Aula, Perpustakaan"
                       value="<?= old('nama_lokasi') ?>" required>
                <span class="form-helper">Nama lokasi di sekolah tempat QR Code akan dipasang</span>
            </div>

            <div class="form-group">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-input form-textarea" id="keterangan" name="keterangan" rows="3"
                          placeholder="Keterangan tambahan tentang lokasi ini"><?= old('keterangan') ?></textarea>
                <span class="form-helper">Opsional - deskripsi singkat tentang lokasi</span>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="aktif" name="aktif" value="1" checked>
                    <label class="form-check-label" for="aktif">Lokasi Aktif</label>
                </div>
                <span class="form-helper" style="padding-left: 28px;">Lokasi aktif akan digunakan untuk generate QR harian</span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-modern btn-primary">
                    <i class="fas fa-save"></i> Simpan Lokasi
                </button>
                <a href="<?= base_url('admin/qr-location') ?>" class="btn-modern btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>

    <div class="sidebar-card">
        <h6><i class="fas fa-lightbulb"></i> Panduan</h6>
        <hr>
        <p><strong>Lokasi QR Code</strong> adalah tempat-tempat fisik di sekolah di mana QR Code akan dipasang untuk absensi siswa.</p>
        <p><strong>Contoh Lokasi:</strong></p>
        <ul>
            <li>Gerbang Utama</li>
            <li>Aula Sekolah</li>
            <li>Perpustakaan</li>
            <li>Kantor Tata Usaha</li>
            <li>Ruang Guru</li>
        </ul>
    </div>
</div>

<?= $this->endSection() ?>
