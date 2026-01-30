<?php echo $this->extend('layout'); ?>

<?php echo $this->section('content'); ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="/siswa/izin-sakit-riwayat" class="btn btn-secondary btn-sm mb-3">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Detail Pengajuan Izin / Sakit
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold text-uppercase">Jenis</h6>
                            <p>
                                <span class="badge" style="background-color: <?php echo $izin['jenis'] === 'izin' ? '#667eea' : '#764ba2'; ?>">
                                    <?php echo ucfirst($izin['jenis']); ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold text-uppercase">Tanggal</h6>
                            <p>
                                <strong><?php echo date('l, d F Y', strtotime($izin['tanggal'])); ?></strong>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold text-uppercase">Status</h6>
                            <p>
                                <?php
                                $statusClass = match($izin['status']) {
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    default => 'secondary'
                                };
                                $statusLabel = match($izin['status']) {
                                    'pending' => 'Menunggu Persetujuan',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    default => 'Tidak Diketahui'
                                };
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?>" style="font-size: 0.95rem; padding: 0.5rem 1rem;">
                                    <?php echo $statusLabel; ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold text-uppercase">Tanggal Pengajuan</h6>
                            <p>
                                <small><?php echo date('d/m/Y H:i', strtotime($izin['created_at'])); ?></small>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="text-muted fw-bold text-uppercase">Alasan</h6>
                        <p class="text-justify" style="line-height: 1.6;">
                            <?php echo nl2br(htmlspecialchars($izin['alasan'])); ?>
                        </p>
                    </div>

                    <?php if ($izin['catatan_admin']): ?>
                        <hr>
                        <div class="mb-3 alert alert-info">
                            <h6 class="text-muted fw-bold text-uppercase">Catatan Admin</h6>
                            <p class="mb-0">
                                <?php echo nl2br(htmlspecialchars($izin['catatan_admin'])); ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <?php if ($izin['bukti_file']): ?>
                        <hr>
                        <div class="mb-3">
                            <h6 class="text-muted fw-bold text-uppercase">Bukti File</h6>
                            <p>
                                <a href="/siswa/izin-sakit-download-bukti/<?php echo $izin['id']; ?>" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-download me-2"></i><?php echo basename($izin['bukti_file']); ?>
                                </a>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>
