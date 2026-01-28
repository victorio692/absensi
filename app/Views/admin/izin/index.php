<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-file-medical"></i> Verifikasi Izin/Sakit</h1>
</div>

<?php if (!empty($izinMenunggu)): ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Diajukan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($izinMenunggu as $i): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $i['nama'] ?></td>
                                <td><?= $i['kelas'] ?></td>
                                <td><?= tanggalIndo($i['tanggal']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $i['jenis'] === 'sakit' ? 'danger' : 'warning' ?>">
                                        <?= ucfirst($i['jenis']) ?>
                                    </span>
                                </td>
                                <td><?= tanggalIndo($i['created_at'], true) ?></td>
                                <td>
                                    <span class="badge bg-warning">Menunggu</span>
                                </td>
                                <td>
                                    <a href="/izin/<?= $i['id'] ?>/verifikasi" class="btn btn-sm btn-primary" title="Verifikasi">
                                        <i class="fas fa-check"></i> Verifikasi
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> Semua izin/sakit telah diverifikasi!
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
