<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-exclamation-triangle"></i> Riwayat Pelanggaran Absensi Saya</h1>
    <form action="/siswa/pelanggaran" method="GET" class="d-flex gap-2">
        <input type="month" name="bulan" value="<?= $bulanTerpilih ?>" class="form-control" style="width: 200px;">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>
</div>

<!-- Ringkasan Pelanggaran -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Keterlambatan</small>
                        <h3 class="text-warning"><?= $totalPelanggaran['Terlambat'] ?? 0 ?></h3>
                    </div>
                    <i class="fas fa-clock" style="font-size: 2em; color: var(--bs-warning);"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Alpha</small>
                        <h3 class="text-danger"><?= $totalPelanggaran['Alpha'] ?? 0 ?></h3>
                    </div>
                    <i class="fas fa-ban" style="font-size: 2em; color: var(--bs-danger);"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Detail Pelanggaran -->
<div class="card">
    <div class="card-header">
        <strong>Detail Pelanggaran Bulan <?= tanggalIndo($bulanTerpilih . '-01', false, 'F Y') ?></strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Bulan</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pelanggaran)): ?>
                        <?php $no = 1; foreach ($pelanggaran as $p): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= tanggalIndo($p['bulan'], false, 'F Y') ?></td>
                                <td>
                                    <span class="badge bg-<?= $p['jenis'] === 'Alpha' ? 'danger' : 'warning' ?>">
                                        <?= $p['jenis'] ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?= $p['jumlah'] ?></strong> kali
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fas fa-check-circle" style="font-size: 2em;"></i><br>
                                Sempurna! Tidak ada pelanggaran pada bulan ini
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tips -->
<div class="alert alert-info mt-4">
    <i class="fas fa-lightbulb"></i>
    <strong>Tips:</strong> Jika memiliki keterlambatan atau alpha, pastikan mengajukan izin dengan bukti yang valid agar status dapat diubah menjadi "Izin".
</div>

<?= $this->endSection() ?>
