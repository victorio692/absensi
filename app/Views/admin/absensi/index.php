<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-column flex-sm-row gap-3">
    <h1 class="mb-0"><i class="fas fa-clipboard-list"></i> Data Absensi</h1>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-success btn-sm" onclick="exportToExcel()">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
        <button type="button" class="btn btn-danger btn-sm" onclick="exportToPDF()">
            <i class="fas fa-file-pdf"></i> Export PDF
        </button>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-filter"></i> Filter Data
    </div>
    <div class="card-body">
        <form method="GET" action="/admin/absensi" class="row g-3" id="filterForm">
            <div class="col-md-3">
                <label for="siswa_id" class="form-label">Siswa</label>
                <select class="form-select" id="siswa_id" name="siswa_id">
                    <option value="">-- Semua Siswa --</option>
                    <?php foreach ($siswaList as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $filters['siswa_id'] == $s['id'] ? 'selected' : '' ?>>
                            <?= $s['nama'] ?> (<?= $s['nis'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="kelas" class="form-label">Kelas</label>
                <select class="form-select" id="kelas" name="kelas">
                    <option value="">-- Semua Kelas --</option>
                    <?php foreach ($kelasList as $k): ?>
                        <option value="<?= $k['kelas'] ?>" <?= $filters['kelas'] == $k['kelas'] ? 'selected' : '' ?>>
                            <?= $k['kelas'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="start_date" class="form-label">Dari Tanggal</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $filters['start_date'] ?>">
            </div>
            <div class="col-md-2">
                <label for="end_date" class="form-label">Sampai Tanggal</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $filters['end_date'] ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="absensiTable">
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
                            <td colspan="8" class="text-center">Belum ada data absensi</td>
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
        // Open in new tab untuk view dan bisa print
        window.open('<?= base_url('admin/absensi/export-pdf') ?>?' + params.toString(), '_blank');
    }
</script>

<?= $this->endSection() ?>
