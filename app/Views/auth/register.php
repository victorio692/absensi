<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Sistem Absensi QR Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }

        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .register-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 28px;
        }

        .register-header p {
            margin: 0.5rem 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .register-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-text {
            font-size: 12px;
            color: #999;
            margin-top: 4px;
        }

        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 0;
            border-radius: 8px;
            font-size: 16px;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
        }

        .btn-login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }

        .info-box {
            background: #f0f7ff;
            border-left: 4px solid #667eea;
            border-radius: 8px;
            padding: 12px;
            font-size: 12px;
            color: #333;
            margin-bottom: 20px;
        }

        .info-box i {
            color: #667eea;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h2><i class="fas fa-user-plus"></i> Registrasi Siswa</h2>
                <p>Daftar Akun Absensi QR Code</p>
            </div>

            <div class="register-body">
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="info-box">
                    <i class="fas fa-info-circle"></i> Isi semua data sesuai dengan data resmi dari sekolah
                </div>

                <form action="/auth/processRegister" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="nisn" class="form-label">
                            <i class="fas fa-id-card"></i> NISN
                        </label>
                        <input type="text" class="form-control" id="nisn" name="nisn" 
                               value="<?= old('nisn') ?>" placeholder="Nomor Induk Siswa Nasional"
                               maxlength="20" required>
                        <div class="form-text">Contoh: 0012345678000001</div>
                    </div>

                    <div class="mb-3">
                        <label for="nis" class="form-label">
                            <i class="fas fa-id-badge"></i> NIS
                        </label>
                        <input type="text" class="form-control" id="nis" name="nis" 
                               value="<?= old('nis') ?>" placeholder="Nomor Induk Sekolah"
                               maxlength="20" required>
                        <div class="form-text">Contoh: 2024001</div>
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">
                            <i class="fas fa-user"></i> Nama Lengkap
                        </label>
                        <input type="text" class="form-control" id="nama" name="nama" 
                               value="<?= old('nama') ?>" placeholder="Masukkan nama lengkap"
                               maxlength="100" required>
                    </div>

                    <div class="mb-3">
                        <label for="kelas" class="form-label">
                            <i class="fas fa-book"></i> Kelas
                        </label>
                        <select class="form-control" id="kelas" name="kelas" required>
                            <option value="">-- Pilih Kelas --</option>
                            <option value="10 RPL A" <?= old('kelas') === '10 RPL A' ? 'selected' : '' ?>>10 RPL A</option>
                            <option value="10 RPL B" <?= old('kelas') === '10 RPL B' ? 'selected' : '' ?>>10 RPL B</option>
                            <option value="11 RPL A" <?= old('kelas') === '11 RPL A' ? 'selected' : '' ?>>11 RPL A</option>
                            <option value="11 RPL B" <?= old('kelas') === '11 RPL B' ? 'selected' : '' ?>>11 RPL B</option>
                            <option value="12 RPL A" <?= old('kelas') === '12 RPL A' ? 'selected' : '' ?>>12 RPL A</option>
                            <option value="12 RPL B" <?= old('kelas') === '12 RPL B' ? 'selected' : '' ?>>12 RPL B</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-register w-100">
                        <i class="fas fa-check-circle"></i> REGISTRASI
                    </button>
                </form>

                <div class="btn-login-link">
                    Sudah punya akun? <a href="/login"><i class="fas fa-sign-in-alt"></i> Login di sini</a>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px; color: white; font-size: 13px;">
            <p style="margin: 0;">© 2025 Sistem Informasi Absensi Siswa | PKL SMK</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                new bootstrap.Alert(alert).close();
            }, 5000);
        });
    </script>
</body>
</html>
