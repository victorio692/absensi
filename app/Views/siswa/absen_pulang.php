<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">
                <i class="fas fa-sign-out-alt text-danger"></i> Absen Pulang
            </h2>
            <p class="text-muted">Tekan tombol di bawah untuk melakukan absensi pulang</p>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Confirm Modal -->
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock"></i> Konfirmasi Absen Pulang
                    </h5>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-question-circle fa-4x text-warning"></i>
                    </div>
                    
                    <h6 class="fw-bold mb-4">Apakah Anda yakin ingin absen pulang sekarang?</h6>
                    
                    <p class="text-muted mb-4">
                        <i class="fas fa-calendar"></i> <strong><?= tanggalIndo(date('Y-m-d')) ?></strong><br>
                        <i class="fas fa-clock"></i> <strong><?= date('H:i:s') ?></strong>
                    </p>

                    <!-- Form -->
                    <form action="<?= base_url('siswa/absen-pulang') ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger btn-lg mb-2" style="width: 100%;">
                            <i class="fas fa-check"></i> Ya, Absen Pulang
                        </button>
                    </form>

                    <a href="<?= base_url('siswa/dashboard') ?>" class="btn btn-secondary btn-lg" style="width: 100%;">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-update time every second
setInterval(() => {
    const now = new Date();
    const timeElement = document.querySelector('[data-time]');
    if (timeElement) {
        timeElement.textContent = now.toLocaleTimeString('id-ID');
    }
}, 1000);
</script>
<?= $this->endSection() ?>
