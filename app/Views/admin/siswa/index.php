<?php helper('absensi_helper'); ?>

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

    .info-box {
        background: rgba(59, 130, 246, 0.1);
        border-left: 4px solid var(--color-info);
        padding: var(--spacing-lg);
        border-radius: var(--radius-lg);
        margin-bottom: var(--spacing-lg);
        display: flex;
        gap: var(--spacing-md);
        align-items: flex-start;
    }

    .info-box i {
        color: var(--color-info);
        margin-top: 2px;
        flex-shrink: 0;
    }

    .info-box div {
        flex: 1;
    }

    .info-box strong {
        color: var(--color-text);
        display: block;
        margin-bottom: var(--spacing-sm);
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

    .action-buttons {
        display: flex;
        gap: var(--spacing-sm);
    }

    .action-button {
        padding: var(--spacing-sm) var(--spacing-md);
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        color: white;
        font-size: var(--font-sm);
        text-decoration: none;
        transition: all var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-xs);
    }

    .action-button.edit {
        background: var(--color-warning);
    }

    .action-button.edit:hover {
        background: #D97706;
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .action-button.delete {
        background: var(--color-danger);
    }

    .action-button.delete:hover {
        background: #DC2626;
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .empty-state {
        text-align: center;
        padding: var(--spacing-3xl);
        color: var(--color-text-tertiary);
    }

    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }

        .action-button {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-users"></i> Kelola Siswa
    </h1>
</div>

<div class="info-box">
    <i class="fas fa-info-circle"></i>
    <div>
        <strong>Catatan:</strong>
        Siswa dapat mendaftar mandiri melalui halaman registrasi. Admin dapat melihat, mengedit, atau menghapus data siswa di sini.
    </div>
</div>

<div class="card-modern">
    <div class="card-body-modern">
        <div style="overflow-x: auto;">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NISN</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($siswa)): ?>
                        <?php $no = 1; foreach ($siswa as $s): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $s['nama'] ?></td>
                                <td><?= $s['nisn'] ?? '-' ?></td>
                                <td><?= $s['nis'] ?></td>
                                <td><?= $s['kelas'] ?></td>
                                <td><?= tanggalIndo($s['created_at']) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/admin/siswa/<?= $s['id'] ?>/edit" class="action-button edit" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="/admin/siswa/<?= $s['id'] ?>/delete" class="action-button delete" title="Hapus" 
                                           onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-inbox" style="font-size: var(--font-3xl); margin-bottom: var(--spacing-lg); display: block;"></i>
                                    Belum ada data siswa
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
