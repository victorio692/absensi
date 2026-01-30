<?php echo $this->extend('layout'); ?>

<?php echo $this->section('content'); ?>

<div class="container-fluid py-4">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <h6 class="text-muted mb-1">Menunggu</h6>
                    <h3 class="mb-0"><?php echo $stats['pending']; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <h6 class="text-muted mb-1">Disetujui</h6>
                    <h3 class="mb-0"><?php echo $stats['approved']; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                    <h6 class="text-muted mb-1">Ditolak</h6>
                    <h3 class="mb-0"><?php echo $stats['rejected']; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-total fa-2x" style="color: #667eea;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Total</h6>
                    <h3 class="mb-0"><?php echo $stats['pending'] + $stats['approved'] + $stats['rejected']; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">
                <i class="fas fa-filter me-2"></i>Filter Data
            </h6>
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" <?php echo $filters['status'] === 'pending' ? 'selected' : ''; ?>>Menunggu</option>
                        <option value="approved" <?php echo $filters['status'] === 'approved' ? 'selected' : ''; ?>>Disetujui</option>
                        <option value="rejected" <?php echo $filters['status'] === 'rejected' ? 'selected' : ''; ?>>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="jenis">
                        <option value="">-- Semua Jenis --</option>
                        <option value="izin" <?php echo $filters['jenis'] === 'izin' ? 'selected' : ''; ?>>Izin</option>
                        <option value="sakit" <?php echo $filters['jenis'] === 'sakit' ? 'selected' : ''; ?>>Sakit</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="start_date" value="<?php echo $filters['start_date']; ?>" placeholder="Tanggal Mulai">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="end_date" value="<?php echo $filters['end_date']; ?>" placeholder="Tanggal Selesai">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Daftar Pengajuan Izin & Sakit
            </h5>
        </div>
        <div class="card-body">
            <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo session('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (empty($izinSakitList)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada data pengajuan izin/sakit</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 20%">Nama Siswa / Kelas</th>
                                <th style="width: 10%">Jenis</th>
                                <th style="width: 10%">Tanggal</th>
                                <th style="width: 20%">Alasan</th>
                                <th style="width: 12%">Status</th>
                                <th style="width: 23%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($izinSakitList as $item): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td>
                                        <strong><?php echo $item['nama']; ?></strong><br>
                                        <small class="text-muted"><?php echo $item['kelas']; ?></small>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: <?php echo $item['jenis'] === 'izin' ? '#667eea' : '#764ba2'; ?>">
                                            <?php echo ucfirst($item['jenis']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?php echo date('d/m/Y', strtotime($item['tanggal'])); ?></strong>
                                    </td>
                                    <td>
                                        <small><?php echo substr($item['alasan'], 0, 25) . (strlen($item['alasan']) > 25 ? '...' : ''); ?></small>
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
                                        <a href="/admin/izin-sakit-detail/<?php echo $item['id']; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye me-1"></i>Lihat
                                        </a>
                                        <?php if ($item['status'] === 'pending'): ?>
                                            <a href="/admin/izin-sakit-approve/<?php echo $item['id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Setujui pengajuan ini?')">
                                                <i class="fas fa-check me-1"></i>Setujui
                                            </a>
                                            <a href="/admin/izin-sakit-reject/<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tolak pengajuan ini?')">
                                                <i class="fas fa-times me-1"></i>Tolak
                                            </a>
                                        <?php else: ?>
                                            <a href="/admin/izin-sakit-delete/<?php echo $item['id']; ?>" class="btn btn-sm btn-warning" onclick="return confirm('Hapus pengajuan ini?')">
                                                <i class="fas fa-trash me-1"></i>Hapus
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

<?php echo $this->endSection(); ?>
