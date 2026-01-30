<?php echo $this->extend('layout'); ?>

<?php echo $this->section('content'); ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Pengajuan Izin / Sakit
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo session('error'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="/siswa/izin-sakit-store" method="POST" enctype="multipart/form-data" class="needs-validation">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis" class="form-label fw-bold">Jenis Pengajuan *</label>
                                    <select class="form-select" id="jenis" name="jenis" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="izin" <?php echo old('jenis') === 'izin' ? 'selected' : ''; ?>>Izin</option>
                                        <option value="sakit" <?php echo old('jenis') === 'sakit' ? 'selected' : ''; ?>>Sakit</option>
                                    </select>
                                    <div class="invalid-feedback">Jenis pengajuan wajib dipilih</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label fw-bold">Tanggal *</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                           value="<?php echo old('tanggal') ?? date('Y-m-d'); ?>" required>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-info-circle"></i> Pengajuan untuk hari ini harus sebelum jam 07:00
                                    </small>
                                    <div class="invalid-feedback">Tanggal wajib diisi</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alasan" class="form-label fw-bold">Alasan *</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" 
                                      placeholder="Jelaskan alasan izin/sakit Anda..." required 
                                      minlength="5" maxlength="500"><?php echo old('alasan'); ?></textarea>
                            <small class="text-muted d-block mt-1">
                                Minimum 5 karakter, maksimal 500 karakter
                            </small>
                            <div class="invalid-feedback">Alasan wajib diisi (minimum 5 karakter)</div>
                        </div>

                        <div class="mb-3">
                            <label for="bukti_file" class="form-label fw-bold">Upload Bukti (Opsional)</label>
                            <input type="file" class="form-control" id="bukti_file" name="bukti_file"
                                   accept=".jpg,.jpeg,.png,.pdf">
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle"></i> Format: JPG, PNG, PDF | Maksimal ukuran: 5 MB
                            </small>
                        </div>

                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Catatan Penting:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Pengajuan izin/sakit untuk hari ini harus dilakukan sebelum jam 07:00</li>
                                <li>Anda hanya bisa mengajukan 1 izin/sakit per hari</li>
                                <li>Setelah disetujui, status absensi Anda akan otomatis berubah</li>
                                <li>Jika ditolak, Anda wajib melakukan absensi QR</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/siswa/izin-sakit-riwayat" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>
