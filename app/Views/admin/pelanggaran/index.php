<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-exclamation-triangle"></i> Riwayat Pelanggaran Absensi</h1>
    <form action="/admin/pelanggaran" method="GET" class="d-flex gap-2">
        <input type="month" name="bulan" value="<?= $bulanTerpilih ?>" class="form-control" style="width: 200px;">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <strong>Pelanggaran Bulan <?= tanggalIndo($bulanTerpilih . '-01', false, 'F Y') ?></strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pelanggaran)): ?>
                        <?php $no = 1; foreach ($pelanggaran as $p): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $p['nama'] ?></td>
                                <td><?= $p['nis'] ?></td>
                                <td><?= $p['kelas'] ?></td>
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
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox" style="font-size: 2em;"></i><br>
                                Tidak ada pelanggaran pada bulan ini
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
