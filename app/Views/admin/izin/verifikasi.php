<?php helper('absensi_helper'); ?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-clipboard-check"></i> Verifikasi Izin/Sakit</h1>
    <a href="/admin/izin" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <strong>Data Permohonan</strong>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Nama Siswa:</strong> <?= $izin['nama'] ?></p>
                        <p><strong>NIS:</strong> <?= $izin['nis'] ?></p>
                        <p><strong>Kelas:</strong> <?= $izin['kelas'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tanggal:</strong> <?= tanggalIndo($izin['tanggal']) ?></p>
                        <p><strong>Jenis:</strong> 
                            <span class="badge bg-<?= $izin['jenis'] === 'sakit' ? 'danger' : 'warning' ?>">
                                <?= ucfirst($izin['jenis']) ?>
                            </span>
                        </p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-<?= $izin['status'] === 'Menunggu' ? 'warning' : ($izin['status'] === 'Disetujui' ? 'success' : 'danger') ?>">
                                <?= $izin['status'] ?>
                            </span>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <strong>Keterangan:</strong>
                    <p class="border p-3 bg-light mt-2"><?= nl2br($izin['keterangan']) ?></p>
                </div>

                <div class="mb-3">
                    <strong>Bukti Pendukung:</strong><br>
                    <?php if ($izin['file_bukti']): ?>
                        <?php $ext = pathinfo($izin['file_bukti'], PATHINFO_EXTENSION); ?>
                        <?php if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                            <img src="/uploads/izin/<?= $izin['file_bukti'] ?>" alt="Bukti" class="img-fluid mt-2" style="max-width: 300px;">
                        <?php else: ?>
                            <a href="/uploads/izin/<?= $izin['file_bukti'] ?>" target="_blank" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="text-muted">Tidak ada file</span>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <small class="text-muted">
                        Diajukan: <?= tanggalIndo($izin['created_at'], true) ?>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <?php if ($izin['status'] === 'Menunggu'): ?>
            <div class="card border-warning">
                <div class="card-body">
                    <h5 class="card-title">Verifikasi Permohonan</h5>
                    <form action="/izin/<?= $izin['id'] ?>/updateStatus" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="status" class="form-label">Keputusan <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Pilih Keputusan</option>
                                <option value="Disetujui">Setujui</option>
                                <option value="Ditolak">Tolak</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check"></i> Simpan Keputusan
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="card border-<?= $izin['status'] === 'Disetujui' ? 'success' : 'danger' ?>">
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-<?= $izin['status'] === 'Disetujui' ? 'check-circle' : 'times-circle' ?>" 
                           style="font-size: 3em; color: <?= $izin['status'] === 'Disetujui' ? 'green' : 'red' ?>;"></i>
                        <p class="mt-2"><strong>Permohonan <?= $izin['status'] ?></strong></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
