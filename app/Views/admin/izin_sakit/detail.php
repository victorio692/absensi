<?php echo $this->extend('layout'); ?>

<?php echo $this->section('content'); ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="/admin/izin-sakit" class="btn btn-secondary btn-sm mb-3">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Detail Pengajuan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold text-uppercase">Nama Siswa</h6>
                            <p>
                                <strong><?php echo $izin['nama']; ?></strong><br>
                                <small class="text-muted"><?php echo $izin['kelas']; ?> | NIS: <?php echo $izin['nis']; ?></small>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold text-uppercase">Jenis & Tanggal</h6>
                            <p>
                                <span class="badge" style="background-color: <?php echo $izin['jenis'] === 'izin' ? '#667eea' : '#764ba2'; ?>">
                                    <?php echo ucfirst($izin['jenis']); ?>
                                </span>
                                <strong class="ms-2"><?php echo date('l, d F Y', strtotime($izin['tanggal'])); ?></strong>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
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

                    <div class="mb-4">
                        <h6 class="text-muted fw-bold text-uppercase">Alasan</h6>
                        <p class="text-justify" style="line-height: 1.6;">
                            <?php echo nl2br(htmlspecialchars($izin['alasan'])); ?>
                        </p>
                    </div>

                    <?php if ($izin['bukti_file']): ?>
                        <div class="mb-4">
                            <h6 class="text-muted fw-bold text-uppercase">Bukti File</h6>
                            <p>
                                <a href="/admin/izin-sakit-download-bukti/<?php echo $izin['id']; ?>" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-download me-2"></i><?php echo basename($izin['bukti_file']); ?>
                                </a>
                            </p>
                        </div>
                        <hr>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <?php if ($izin['status'] === 'pending'): ?>
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Pengajuan menunggu persetujuan Anda</strong>
                        </div>

                        <!-- Approve Form -->
                        <form method="POST" action="/admin/izin-sakit-approve/<?php echo $izin['id']; ?>" class="mb-3">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label for="catatan_approve" class="form-label fw-bold">Catatan (Opsional)</label>
                                <textarea class="form-control" id="catatan_approve" name="catatan_admin" rows="3" placeholder="Catatan untuk siswa..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success me-2" onclick="return confirm('Setujui pengajuan ini?')">
                                <i class="fas fa-check me-2"></i>Setujui Pengajuan
                            </button>
                        </form>

                        <!-- Reject Form -->
                        <form method="POST" action="/admin/izin-sakit-reject/<?php echo $izin['id']; ?>">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label for="catatan_reject" class="form-label fw-bold">Alasan Penolakan (Opsional)</label>
                                <textarea class="form-control" id="catatan_reject" name="catatan_admin" rows="3" placeholder="Jelaskan mengapa pengajuan ditolak..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak pengajuan ini?')">
                                <i class="fas fa-times me-2"></i>Tolak Pengajuan
                            </button>
                        </form>
                    <?php else: ?>
                        <?php if ($izin['catatan_admin']): ?>
                            <div class="alert alert-light border">
                                <h6 class="text-muted fw-bold text-uppercase mb-2">Catatan Admin</h6>
                                <p class="mb-0">
                                    <?php echo nl2br(htmlspecialchars($izin['catatan_admin'])); ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex gap-2 mt-3">
                            <a href="/admin/izin-sakit" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <form method="POST" action="/admin/izin-sakit-delete/<?php echo $izin['id']; ?>" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Hapus pengajuan ini?')">
                                    <i class="fas fa-trash me-2"></i>Hapus Pengajuan
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>
