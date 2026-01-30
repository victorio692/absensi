<?php echo $this->extend('layout'); ?>

<?php echo $this->section('content'); ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-0">
                    <i class="fas fa-history me-2"></i>Riwayat Pengajuan Izin / Sakit
                </h2>
                <a href="/siswa/izin-sakit-create" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-2"></i>Ajukan Baru
                </a>
            </div>

            <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo session('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <?php if (empty($izinSakit)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada pengajuan izin/sakit</p>
                            <a href="/siswa/izin-sakit-create" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Ajukan Sekarang
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th style="width: 10%">Tanggal</th>
                                        <th style="width: 10%">Jenis</th>
                                        <th style="width: 25%">Alasan</th>
                                        <th style="width: 15%">Status</th>
                                        <th style="width: 25%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($izinSakit as $item): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td>
                                                <strong><?php echo date('d/m/Y', strtotime($item['tanggal'])); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge" style="background-color: <?php echo $item['jenis'] === 'izin' ? '#667eea' : '#764ba2'; ?>">
                                                    <?php echo ucfirst($item['jenis']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small><?php echo substr($item['alasan'], 0, 30) . (strlen($item['alasan']) > 30 ? '...' : ''); ?></small>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = match($item['status']) {
                                                    'pending' => 'warning',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger',
                                                    default => 'secondary'
                                                };
                                                $statusLabel = match($item['status']) {
                                                    'pending' => 'Menunggu',
                                                    'approved' => 'Disetujui',
                                                    'rejected' => 'Ditolak',
                                                    default => 'Tidak Diketahui'
                                                };
                                                ?>
                                                <span class="badge bg-<?php echo $statusClass; ?>">
                                                    <?php echo $statusLabel; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/siswa/izin-sakit-detail/<?php echo $item['id']; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i>Lihat Detail
                                                </a>
                                                <?php if ($item['bukti_file']): ?>
                                                    <a href="/siswa/izin-sakit-download-bukti/<?php echo $item['id']; ?>" class="btn btn-sm btn-secondary">
                                                        <i class="fas fa-download me-1"></i>Bukti
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>
