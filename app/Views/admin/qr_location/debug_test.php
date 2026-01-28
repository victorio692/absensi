<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h1>Debug: Test Add Lokasi QR</h1>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5>Test Form - dengan Debug Info</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success">
                            ✓ <?= session('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger">
                            ✗ <?= session('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= base_url('admin/qr-location/store') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label class="form-label"><strong>Nama Lokasi:</strong></label>
                            <input type="text" name="nama_lokasi" class="form-control" required 
                                   value="Test <?= time() ?>" placeholder="Minimal 3 karakter">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><strong>Keterangan:</strong></label>
                            <textarea name="keterangan" class="form-control" rows="3"
                                      placeholder="Opsional">Test keterangan lokasi</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label>
                                <input type="checkbox" name="aktif" value="1" checked>
                                <strong>Lokasi Aktif</strong>
                            </label>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                🔘 SUBMIT TEST
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5>Debug Info</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Base URL:</strong></td>
                            <td><code><?= base_url() ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Form Action:</strong></td>
                            <td><code><?= base_url('admin/qr-location/store') ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>CSRF Token Name:</strong></td>
                            <td><code><?= csrf_token() ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>CSRF Value (first 20):</strong></td>
                            <td><code><?= substr(csrf_hash(), 0, 20) ?>...</code></td>
                        </tr>
                        <tr>
                            <td><strong>Session ID:</strong></td>
                            <td><code><?= session_id() ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Method:</strong></td>
                            <td><code>POST</code></td>
                        </tr>
                        <tr>
                            <td><strong>Current User:</strong></td>
                            <td><code><?= session('user_id') ? 'Admin ID: ' . session('user_id') : 'Not logged in' ?></code></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h5>Database Status</h5>
                </div>
                <div class="card-body">
                    <?php 
                    $qrModel = model('App\Models\QrLocationModel');
                    $count = $qrModel->countAll();
                    $active = $qrModel->where('aktif', 1)->countAllResults();
                    ?>
                    <ul class="list-unstyled">
                        <li>✓ Total Locations: <strong><?= $count ?></strong></li>
                        <li>✓ Active: <strong><?= $active ?></strong></li>
                        <li>✓ Inactive: <strong><?= $count - $active ?></strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
