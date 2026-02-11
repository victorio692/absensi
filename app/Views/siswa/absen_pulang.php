<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<style>
    .page-header {
        margin-bottom: var(--spacing-3xl);
    }

    .page-header h1 {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        font-weight: 800;
        color: var(--color-text);
    }

    .confirmation-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 60vh;
    }

    .confirmation-card {
        background: var(--color-surface);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        padding: var(--spacing-3xl);
        text-align: center;
        max-width: 500px;
        width: 100%;
        border: 1px solid var(--color-border);
    }

    .confirmation-icon {
        font-size: 3.75rem;
        color: var(--color-danger);
        margin-bottom: var(--spacing-lg);
    }

    .confirmation-title {
        font-size: var(--font-lg);
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: var(--spacing-lg);
    }

    .confirmation-datetime {
        background: var(--color-background);
        border-radius: var(--radius-lg);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-2xl);
        border: 1px solid var(--color-border);
    }

    .confirmation-datetime-item {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--spacing-md);
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-sm);
        font-size: var(--font-sm);
    }

    .confirmation-datetime-item i {
        color: var(--color-primary);
        width: 20px;
    }

    .confirmation-datetime-item strong {
        color: var(--color-text);
    }

    .confirmation-buttons {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .btn-confirm {
        padding: var(--spacing-lg);
        border: none;
        border-radius: var(--radius-md);
        font-weight: 700;
        cursor: pointer;
        transition: all var(--transition-smooth);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--spacing-sm);
        text-decoration: none;
        color: white;
        font-size: 1rem;
    }

    .btn-confirm.danger {
        background: var(--color-danger);
    }

    .btn-confirm.danger:hover {
        background: #DC2626;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-confirm.secondary {
        background: var(--color-text-tertiary);
        color: white;
    }

    .btn-confirm.secondary:hover {
        background: #78716C;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    @media (max-width: 768px) {
        .confirmation-card {
            padding: var(--spacing-xl);
        }

        .confirmation-icon {
            font-size: 2.5rem;
        }
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-sign-out-alt"></i> Absen Pulang
    </h1>
</div>

<div class="confirmation-container">
    <div class="confirmation-card">
        <div class="confirmation-icon">
            <i class="fas fa-question-circle"></i>
        </div>
        
        <h2 class="confirmation-title">
            Apakah Anda yakin ingin absen pulang sekarang?
        </h2>

        <div class="confirmation-datetime">
            <div class="confirmation-datetime-item">
                <i class="fas fa-calendar"></i>
                <strong><?= tanggalIndo(date('Y-m-d')) ?></strong>
            </div>
            <div class="confirmation-datetime-item">
                <i class="fas fa-clock"></i>
                <strong><?= date('H:i:s') ?></strong>
            </div>
        </div>

        <div class="confirmation-buttons">
            <form action="<?= base_url('siswa/absen-pulang') ?>" method="post" style="width: 100%;">
                <?= csrf_field() ?>
                <button type="submit" class="btn-confirm danger" style="width: 100%;">
                    <i class="fas fa-check"></i> Ya, Absen Pulang
                </button>
            </form>

            <a href="<?= base_url('siswa/dashboard') ?>" class="btn-confirm secondary" style="width: 100%;">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
