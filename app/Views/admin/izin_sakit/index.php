<?php echo $this->extend('layout'); ?>

<?php echo $this->section('content'); ?>

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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-3xl);
    }

    .stat-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-xl);
        padding: var(--spacing-2xl);
        text-align: center;
        transition: all 150ms ease-in-out;
    }

    .stat-card:hover {
        border-color: var(--color-primary);
        box-shadow: var(--shadow-md);
        transform: translateY(-4px);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: var(--spacing-lg);
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-lg);
    }

    .stat-icon.warning {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .stat-icon.success {
        background: rgba(34, 197, 94, 0.1);
        color: #22C55E;
    }

    .stat-icon.danger {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .stat-icon.info {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
    }

    .stat-label {
        font-size: var(--font-sm);
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-sm);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: var(--color-text);
    }

    .filter-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-xl);
        padding: var(--spacing-2xl);
        margin-bottom: var(--spacing-2xl);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: var(--spacing-lg);
    }

    .form-select, .form-control {
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-family: inherit;
        font-size: var(--font-base);
        color: var(--color-text);
        background-color: var(--color-surface);
        transition: all 150ms ease-in-out;
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%234F46E5' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right var(--spacing-lg) center;
        padding-right: var(--spacing-3xl);
        cursor: pointer;
    }

    .form-select:focus, .form-control:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .btn-search {
        padding: var(--spacing-md) var(--spacing-lg);
        background-color: var(--color-primary);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-weight: 600;
        cursor: pointer;
        transition: all 150ms ease-in-out;
    }

    .btn-search:hover {
        background-color: var(--color-primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .table-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-xl);
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, var(--color-primary) 0%, #4338CA 100%);
        color: white;
        padding: var(--spacing-2xl);
        border-bottom: 1px solid var(--color-border);
    }

    .table-header h2 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .table-modern {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .table-modern thead th {
        background-color: var(--color-background);
        color: var(--color-text-secondary);
        padding: var(--spacing-lg);
        text-align: left;
        font-weight: 600;
        font-size: var(--font-sm);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--color-border);
    }

    .table-modern tbody tr {
        border-bottom: 1px solid var(--color-border);
        transition: background-color 150ms ease-in-out;
    }

    .table-modern tbody tr:hover {
        background-color: var(--color-background);
    }

    .table-modern tbody td {
        padding: var(--spacing-lg);
        color: var(--color-text);
    }

    .badge {
        display: inline-block;
        padding: var(--spacing-xs) var(--spacing-md);
        border-radius: var(--radius-full);
        font-size: var(--font-xs);
        font-weight: 600;
    }

    .badge.pending {
        background: rgba(245, 158, 11, 0.2);
        color: #D97706;
    }

    .badge.approved {
        background: rgba(34, 197, 94, 0.2);
        color: #15803D;
    }

    .badge.rejected {
        background: rgba(239, 68, 68, 0.2);
        color: #B91C1C;
    }

    .action-buttons {
        display: flex;
        gap: var(--spacing-sm);
        flex-wrap: wrap;
    }

    .btn-small {
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-xs);
        padding: 6px 12px;
        border: none;
        border-radius: var(--radius-md);
        font-size: var(--font-xs);
        font-weight: 600;
        cursor: pointer;
        transition: all 150ms ease-in-out;
        text-decoration: none;
    }

    .btn-view {
        background-color: #3B82F6;
        color: white;
    }

    .btn-view:hover {
        background-color: #1D4ED8;
    }

    .btn-approve {
        background-color: #22C55E;
        color: white;
    }

    .btn-approve:hover {
        background-color: #15803D;
    }

    .btn-reject {
        background-color: #EF4444;
        color: white;
    }

    .btn-reject:hover {
        background-color: #B91C1C;
    }

    .btn-delete {
        background-color: #F59E0B;
        color: white;
    }

    .btn-delete:hover {
        background-color: #D97706;
    }

    .empty-state {
        text-align: center;
        padding: var(--spacing-3xl);
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--color-border);
        margin-bottom: var(--spacing-lg);
    }

    .empty-state p {
        color: var(--color-text-secondary);
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-small {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-file-medical-alt"></i> Manajemen Izin & Sakit
    </h1>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-label">Menunggu</div>
        <div class="stat-number"><?php echo $stats['pending']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-label">Disetujui</div>
        <div class="stat-number"><?php echo $stats['approved']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-label">Ditolak</div>
        <div class="stat-number"><?php echo $stats['rejected']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-list"></i>
        </div>
        <div class="stat-label">Total</div>
        <div class="stat-number"><?php echo $stats['pending'] + $stats['approved'] + $stats['rejected']; ?></div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-card">
    <h3 style="margin-top: 0; margin-bottom: var(--spacing-lg); font-weight: 700; color: var(--color-text);">
        <i class="fas fa-filter"></i> Filter Data
    </h3>
    <form method="GET" class="filter-grid">
        <div>
            <select class="form-select" name="status">
                <option value="">-- Semua Status --</option>
                <option value="pending" <?php echo $filters['status'] === 'pending' ? 'selected' : ''; ?>>Menunggu</option>
                <option value="approved" <?php echo $filters['status'] === 'approved' ? 'selected' : ''; ?>>Disetujui</option>
                <option value="rejected" <?php echo $filters['status'] === 'rejected' ? 'selected' : ''; ?>>Ditolak</option>
            </select>
        </div>
        <div>
            <select class="form-select" name="jenis">
                <option value="">-- Semua Jenis --</option>
                <option value="izin" <?php echo $filters['jenis'] === 'izin' ? 'selected' : ''; ?>>Izin</option>
                <option value="sakit" <?php echo $filters['jenis'] === 'sakit' ? 'selected' : ''; ?>>Sakit</option>
            </select>
        </div>
        <div>
            <input type="date" class="form-control" name="start_date" value="<?php echo $filters['start_date']; ?>">
        </div>
        <div>
            <input type="date" class="form-control" name="end_date" value="<?php echo $filters['end_date']; ?>">
        </div>
        <div style="display: flex; align-items: flex-end;">
            <button type="submit" class="btn-search" style="width: 100%;">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
    </form>
</div>

<!-- Data Table -->
<div class="table-card">
    <div class="table-header">
        <h2>
            <i class="fas fa-list"></i> Daftar Pengajuan Izin & Sakit
        </h2>
    </div>
    <div style="padding: var(--spacing-2xl);">
        <?php if (session()->has('success')): ?>
            <div style="background: rgba(34, 197, 94, 0.1); border-left: 4px solid #22C55E; padding: var(--spacing-lg); border-radius: var(--radius-lg); margin-bottom: var(--spacing-lg); color: #15803D;">
                <i class="fas fa-check-circle"></i>
                <strong><?php echo session('success'); ?></strong>
            </div>
        <?php endif; ?>

        <?php if (empty($izinSakitList)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Tidak ada data pengajuan izin/sakit</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 18%">Nama Siswa / Kelas</th>
                            <th style="width: 10%">Jenis</th>
                            <th style="width: 12%">Tanggal</th>
                            <th style="width: 20%">Alasan</th>
                            <th style="width: 12%">Status</th>
                            <th style="width: 23%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($izinSakitList as $item): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <strong><?php echo $item['nama']; ?></strong><br>
                                    <small style="color: var(--color-text-secondary);"><?php echo $item['kelas']; ?></small>
                                </td>
                                <td>
                                    <span class="badge <?php echo $item['jenis'] === 'izin' ? 'pending' : 'rejected'; ?>">
                                        <?php echo ucfirst($item['jenis']); ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?php echo date('d/m/Y', strtotime($item['tanggal'])); ?></strong>
                                </td>
                                <td>
                                    <small><?php echo substr($item['alasan'], 0, 25) . (strlen($item['alasan']) > 25 ? '...' : ''); ?></small>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = match($item['status']) {
                                        'pending' => 'pending',
                                        'approved' => 'approved',
                                        'rejected' => 'rejected',
                                        default => 'pending'
                                    };
                                    $statusLabel = match($item['status']) {
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        default => 'Tidak Diketahui'
                                    };
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo $statusLabel; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/admin/izin-sakit-detail/<?php echo $item['id']; ?>" class="btn-small btn-view">
                                            <i class="fas fa-eye"></i>Lihat
                                        </a>
                                        <?php if ($item['status'] === 'pending'): ?>
                                            <a href="/admin/izin-sakit-approve/<?php echo $item['id']; ?>" class="btn-small btn-approve" onclick="return confirm('Setujui pengajuan ini?')">
                                                <i class="fas fa-check"></i>Setujui
                                            </a>
                                            <a href="/admin/izin-sakit-reject/<?php echo $item['id']; ?>" class="btn-small btn-reject" onclick="return confirm('Tolak pengajuan ini?')">
                                                <i class="fas fa-times"></i>Tolak
                                            </a>
                                        <?php else: ?>
                                            <a href="/admin/izin-sakit-delete/<?php echo $item['id']; ?>" class="btn-small btn-delete" onclick="return confirm('Hapus pengajuan ini?')">
                                                <i class="fas fa-trash"></i>Hapus
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php echo $this->endSection(); ?>
