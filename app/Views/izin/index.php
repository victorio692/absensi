<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-file-medical"></i> Riwayat Izin/Sakit</h1>
    <a href="/izin/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajukan Izin/Sakit
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($izin)): ?>
                        <?php $no = 1; foreach ($izin as $i): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= tanggalIndo($i['tanggal']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $i['jenis'] === 'sakit' ? 'danger' : 'warning' ?>">
                                        <?= ucfirst($i['jenis']) ?>
                                    </span>
                                </td>
                                <td><?= substr($i['keterangan'], 0, 50) ?>...</td>
                                <td>
                                    <?php if ($i['file_bukti']): ?>
                                        <a href="/uploads/izin/<?= $i['file_bukti'] ?>" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-file"></i> Lihat
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php $statusClass = match($i['status']) {
                                        'Disetujui' => 'success',
                                        'Ditolak' => 'danger',
                                        default => 'warning'
                                    }; ?>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <?= $i['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($i['status'] === 'Menunggu'): ?>
                                        <a href="/izin/<?= $i['id'] ?>/delete" class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox" style="font-size: 2em;"></i><br>
                                Belum ada riwayat izin/sakit
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
