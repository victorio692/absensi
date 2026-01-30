<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-tv"></i> Pilih Lokasi untuk Monitor
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Pilih lokasi untuk menampilkan QR code di monitor/layar besar.
                    </p>

                    <div class="list-group">
                        <?php if (!empty($lokasi)): ?>
                            <?php foreach ($lokasi as $l): ?>
                                <a href="<?= base_url('admin/monitor/display/' . $l['id']) ?>" 
                                   class="list-group-item list-group-item-action py-3">
                                    <div class="d-flex w-100 align-items-center">
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="fas fa-map-pin text-primary"></i>
                                                <?= $l['nama_lokasi'] ?>
                                            </h6>
                                            <small class="text-muted"><?= $l['keterangan'] ?></small>
                                        </div>
                                        <small class="ms-auto text-success">
                                            <i class="fas fa-arrow-right"></i>
                                        </small>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle"></i> Tidak ada lokasi aktif
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Pilih lokasi untuk menampilkan QR code fullscreen di monitor
                    </small>
                </div>
            </div>

            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary w-100 mt-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
