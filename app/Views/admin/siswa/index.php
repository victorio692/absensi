<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-users"></i> Kelola Siswa</h1>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> <strong>Catatan:</strong> Siswa dapat mendaftar mandiri melalui halaman registrasi. Admin dapat melihat, mengedit, atau menghapus data siswa di sini.
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
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
                                    <a href="/admin/siswa/<?= $s['id'] ?>/edit" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/admin/siswa/<?= $s['id'] ?>/delete" class="btn btn-sm btn-danger" title="Hapus" 
                                       onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data siswa</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
