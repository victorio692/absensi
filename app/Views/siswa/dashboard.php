<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<style>
    .page-header {
        margin-bottom: var(--spacing-3xl);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header h1 {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        font-weight: 800;
        color: var(--color-text);
        margin: 0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-3xl);
    }

    .info-card {
        background: var(--color-surface);
        border-radius: var(--radius-xl);
        padding: var(--spacing-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--color-border);
        transition: all var(--transition-smooth);
    }

    .info-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .info-card-header {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-md);
        padding-bottom: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
    }

    .info-card-header i {
        color: var(--color-primary);
        font-size: var(--font-lg);
    }

    .info-card-header h3 {
        margin: 0;
        font-size: var(--font-lg);
        color: var(--color-text);
    }

    .info-card-content p {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-md);
        color: var(--color-text-secondary);
        font-size: var(--font-sm);
    }

    .info-card-content p strong {
        color: var(--color-text);
    }

    .info-card-content p:last-child {
        margin-bottom: 0;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-3xl);
    }

    .action-button {
        padding: var(--spacing-xl) var(--spacing-lg);
        border-radius: var(--radius-xl);
        text-align: center;
        text-decoration: none;
        color: white;
        font-weight: 600;
        transition: all var(--transition-smooth);
        cursor: pointer;
        border: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: var(--spacing-md);
        box-shadow: var(--shadow-sm);
    }

    .action-button:hover:not(:disabled) {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }

    .action-button i {
        font-size: var(--font-2xl);
    }

    .action-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background: var(--color-text-tertiary);
    }

    .card-modern {
        margin-bottom: var(--spacing-3xl);
    }

    .card-header-modern {
        background: var(--color-background-secondary);
        padding: var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
        font-weight: 700;
        color: var(--color-text);
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
    }

    .card-header-modern i {
        color: var(--color-primary);
        font-size: var(--font-lg);
    }

    .card-body-modern {
        padding: var(--spacing-lg);
    }

    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }

    .table-modern thead {
        background-color: var(--color-background-secondary);
    }

    .table-modern th {
        padding: var(--spacing-md) var(--spacing-lg);
        text-align: left;
        font-weight: 700;
        color: var(--color-text);
        border-bottom: 2px solid var(--color-border);
        font-size: var(--font-sm);
    }

    .table-modern td {
        padding: var(--spacing-md) var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-secondary);
    }

    .table-modern tbody tr:hover {
        background-color: var(--color-surface-hover);
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .actions-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-home"></i> Dashboard Siswa
    </h1>
</div>

<!-- Status Absensi & Informasi Siswa -->
<div class="info-grid">
    <div class="info-card">
        <div class="info-card-header">
            <i class="fas fa-check-circle"></i>
            <h3>Status Absensi Hari Ini</h3>
        </div>
        <div class="info-card-content">
            <?php if ($sudahAbsenMasuk): ?>
                <p><strong>Jam Masuk:</strong> <span><?= jamFormat($absensiMasukHariIni['jam_masuk']) ?></span></p>
                <p><strong>Status:</strong> <span><?= badgeStatus($absensiMasukHariIni['status']) ?></span></p>
                <?php if ($sudahAbsenPulang): ?>
                    <p><strong>Jam Pulang:</strong> <span><?= jamFormat($absensiPulangHariIni['jam_pulang']) ?></span></p>
                <?php endif; ?>
            <?php else: ?>
                <p style="color: var(--color-warning); justify-content: flex-start;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: var(--spacing-sm);"></i>
                    Belum absen hari ini
                </p>
            <?php endif; ?>
        </div>
    </div>
    <div class="info-card">
        <div class="info-card-header">
            <i class="fas fa-user"></i>
            <h3>Informasi Siswa</h3>
        </div>
        <div class="info-card-content">
            <p><strong>Nama:</strong> <span><?= $siswa['nama'] ?></span></p>
            <p><strong>NIS:</strong> <span><?= $siswa['nis'] ?></span></p>
            <p><strong>Kelas:</strong> <span><?= $siswa['kelas'] ?></span></p>
        </div>
    </div>
</div>

<!-- Menu Tombol Aksi -->
<div class="actions-grid">
    <?php if (!$sudahAbsenMasuk): ?>
        <a href="/siswa/scan" class="action-button btn-primary">
            <i class="fas fa-camera"></i>
            <span>Scan QR Code Masuk</span>
        </a>
    <?php else: ?>
        <button class="action-button" disabled>
            <i class="fas fa-check-circle"></i>
            <span>Sudah Absen Masuk</span>
        </button>
    <?php endif; ?>

    <?php if ($sudahAbsenMasuk && !$sudahAbsenPulang): ?>
        <a href="/siswa/scan" class="action-button btn-warning">
            <i class="fas fa-camera"></i>
            <span>Scan QR Code Pulang</span>
        </a>
    <?php else: ?>
        <button class="action-button" disabled>
            <i class="fas fa-check-circle"></i>
            <span>Sudah Absen Pulang</span>
        </button>
    <?php endif; ?>
</div>

<!-- Kalender Absensi Bulanan -->
<?= view('siswa/partials/calendar', $calendarData) ?>

<!-- Riwayat Absensi Bulan Ini -->
<div class="card-modern">
    <div class="card-header-modern">
        <i class="fas fa-calendar"></i> Riwayat Absensi Bulan Ini
    </div>
    <div class="card-body-modern">
        <div style="overflow-x: auto;">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($riwayatAbsensi)): ?>
                        <?php $no = 1; foreach ($riwayatAbsensi as $absensi): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= tanggalIndo($absensi['tanggal']) ?></td>
                                <td><?= hariIndo($absensi['tanggal']) ?></td>
                                <td><?= jamFormat($absensi['jam_masuk']) ?></td>
                                <td><?= jamFormat($absensi['jam_pulang']) ?></td>
                                <td><?= badgeStatus($absensi['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: var(--spacing-xl); color: var(--color-text-tertiary);">
                                Belum ada data absensi bulan ini
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
