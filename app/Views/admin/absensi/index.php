<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-3xl);
        flex-wrap: wrap;
    }

    .page-header h1 {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        font-weight: 800;
        color: var(--color-text);
        margin: 0;
    }

    .button-group {
        display: flex;
        gap: var(--spacing-sm);
    }

    .filter-card {
        background: var(--color-surface);
        border-radius: var(--radius-xl);
        padding: var(--spacing-lg);
        box-shadow: var(--shadow-sm);
        margin-bottom: var(--spacing-lg);
        border: 1px solid var(--color-border);
    }

    .filter-header {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
    }

    .filter-header i {
        color: var(--color-primary);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--spacing-lg);
        align-items: flex-end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-weight: 600;
        color: var(--color-text);
        margin-bottom: var(--spacing-sm);
        font-size: var(--font-sm);
    }

    .form-control-modern,
    .form-control-modern select {
        width: 100%;
        padding: var(--spacing-md) var(--spacing-lg);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-size: var(--font-base);
        font-family: inherit;
        color: var(--color-text);
        background-color: var(--color-surface);
        transition: all var(--transition-fast);
    }

    .form-control-modern:focus,
    .form-control-modern select:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
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
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .button-group {
            width: 100%;
        }

        .button-group .btn-modern {
            flex: 1;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-clipboard-list"></i> Data Absensi
    </h1>
    <div class="button-group">
        <button type="button" class="btn-modern btn-success" onclick="exportToExcel()">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
        <button type="button" class="btn-modern btn-danger" onclick="exportToPDF()">
            <i class="fas fa-file-pdf"></i> Export PDF
        </button>
    </div>
</div>

<!-- Filter Card -->
<div class="filter-card">
    <div class="filter-header">
        <i class="fas fa-filter"></i> Filter Data
    </div>
    <form method="GET" action="/admin/absensi" class="form-grid" id="filterForm">
        <div class="form-group">
            <label for="siswa_id" class="form-label">Siswa</label>
            <select class="form-control-modern" id="siswa_id" name="siswa_id">
                <option value="">-- Semua Siswa --</option>
                <?php foreach ($siswaList as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= $filters['siswa_id'] == $s['id'] ? 'selected' : '' ?>>
                        <?= $s['nama'] ?> (<?= $s['nis'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="kelas" class="form-label">Kelas</label>
            <select class="form-control-modern" id="kelas" name="kelas">
                <option value="">-- Semua Kelas --</option>
                <?php foreach ($kelasList as $k): ?>
                    <option value="<?= $k['kelas'] ?>" <?= $filters['kelas'] == $k['kelas'] ? 'selected' : '' ?>>
                        <?= $k['kelas'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="start_date" class="form-label">Dari Tanggal</label>
            <input type="date" class="form-control-modern" id="start_date" name="start_date" value="<?= $filters['start_date'] ?>">
        </div>
        <div class="form-group">
            <label for="end_date" class="form-label">Sampai Tanggal</label>
            <input type="date" class="form-control-modern" id="end_date" name="end_date" value="<?= $filters['end_date'] ?>">
        </div>
        <button type="submit" class="btn-modern btn-primary" style="height: 44px; display: flex; align-items: center; gap: var(--spacing-sm);">
            <i class="fas fa-search"></i> Cari
        </button>
    </form>
</div>

<!-- Data Table Card -->
<div class="card-modern">
    <div class="card-body-modern">
        <div style="overflow-x: auto;">
            <table class="table-modern" id="absensiTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($absensi)): ?>
                        <?php $no = 1; foreach ($absensi as $a): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $a['nama'] ?></td>
                                <td><?= $a['nis'] ?></td>
                                <td><?= $a['kelas'] ?></td>
                                <td><?= tanggalIndo($a['tanggal']) ?></td>
                                <td><?= jamFormat($a['jam_masuk']) ?></td>
                                <td><?= jamFormat($a['jam_pulang']) ?></td>
                                <td><?= badgeStatus($a['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: var(--spacing-xl); color: var(--color-text-tertiary);">
                                Belum ada data absensi
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.min.js"></script>
<script>
    function exportToExcel() {
        const table = document.getElementById('absensiTable');
        const wb = XLSX.utils.table_to_book(table, {sheet: 'Absensi'});
        const fileName = 'Laporan_Absensi_' + new Date().toISOString().split('T')[0] + '.xlsx';
        XLSX.writeFile(wb, fileName);
    }

    function exportToPDF() {
        const form = document.getElementById('filterForm');
        const params = new URLSearchParams(new FormData(form));
        window.open('<?= base_url('admin/absensi/export-pdf') ?>?' + params.toString(), '_blank');
    }
</script>

<?= $this->endSection() ?>
