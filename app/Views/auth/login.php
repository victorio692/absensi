<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi QR Code</title>
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
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .login-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 28px;
        }

        .login-header p {
            margin: 0.5rem 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .login-body {
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

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .divider {
            border: none;
            border-top: 1px solid #eee;
            margin: 20px 0;
        }

        .test-credentials {
            background: #f0f7ff;
            border-left: 4px solid #667eea;
            border-radius: 8px;
            padding: 15px;
            font-size: 13px;
        }

        .test-credentials h6 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .test-credentials code {
            background: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }

        .role-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .role-tab {
            padding: 10px 15px;
            border: none;
            background: none;
            cursor: pointer;
            color: #999;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            position: relative;
            bottom: -2px;
        }

        .role-tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        .role-tab:hover {
            color: #667eea;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .input-hint {
            font-size: 12px;
            color: #999;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2><i class="fas fa-qrcode"></i> Absensi</h2>
                <p>Sistem Informasi Absensi Siswa QR Code</p>
            </div>

            <div class="login-body">
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Role Tabs -->
                <div class="role-tabs">
                    <button type="button" class="role-tab active" data-role="admin">
                        <i class="fas fa-user-shield"></i> Admin
                    </button>
                    <button type="button" class="role-tab" data-role="siswa">
                        <i class="fas fa-user-graduate"></i> Siswa
                    </button>
                </div>

                <form action="/login" method="POST" id="loginForm">
                    <?= csrf_field() ?>
                    <input type="hidden" id="user_role" name="user_role" value="admin">

                    <!-- Single Form - Content Changes Based on Role -->
                    <div class="mb-3">
                        <label for="username" class="form-label" id="username-label">
                            <i class="fas fa-user"></i> <span id="username-label-text">Username</span>
                        </label>
                        <input type="text" class="form-control" id="username" name="username" 
                               placeholder="Masukkan username" autofocus required>
                        <div class="input-hint" id="username-hint">Gunakan akun admin</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label" id="password-label">
                            <i class="fas fa-lock"></i> <span id="password-label-text">Password</span>
                        </label>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Masukkan password" required>
                        <div class="input-hint" id="password-hint"></div>
                    </div>

                    <button type="submit" class="btn btn-login w-100">
                        <i class="fas fa-sign-in-alt"></i> LOGIN
                    </button>
                </form>

                <hr class="divider">

                

                <hr class="divider" style="margin-top: 20px; display: none;" id="register-divider">

                <div id="register-box" style="padding: 15px; background: #f0f7ff; border-radius: 8px; border-left: 4px solid #667eea; text-align: center; display: none;">
                    <p style="margin: 0 0 10px 0; color: #333; font-weight: 600;">
                        Belum punya akun?
                    </p>
                    <a href="/register" style="display: inline-block; background: #667eea; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-user-plus"></i> Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px; color: white; font-size: 13px;">
            <p style="margin: 0;">2025 Sistem Informasi Absensi Siswa | PKL SMK</p>
            <p style="margin-top: 10px;">
              
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-close alerts setelah 5 detik
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                new bootstrap.Alert(alert).close();
            }, 5000);
        });

        // Form configuration untuk setiap role
        const roleConfig = {
            admin: {
                usernamePlaceholder: 'Masukkan username',
                usernameLabel: 'Username',
                passwordPlaceholder: 'Masukkan password',
                passwordLabel: 'Password',
                usernameHint: 'Gunakan akun admin',
                passwordHint: '',
                usernameIcon: 'fas fa-user',
                passwordIcon: 'fas fa-lock'
            },
            siswa: {
                usernamePlaceholder: 'Masukkan NISN Anda',
                usernameLabel: 'NISN',
                passwordPlaceholder: 'Masukkan NIS Anda',
                passwordLabel: 'NIS',
                usernameHint: 'Nomor Induk Siswa Nasional',
                passwordHint: 'Nomor Induk Sekolah',
                usernameIcon: 'fas fa-id-card',
                passwordIcon: 'fas fa-id-badge'
            }
        };

        // Handling role tabs
        const roleTabs = document.querySelectorAll('.role-tab');
        const userRoleInput = document.getElementById('user_role');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameLabelText = document.getElementById('username-label-text');
        const usernameHint = document.getElementById('username-hint');
        const usernameIcon = document.querySelector('#username-label i');
        const passwordLabelText = document.getElementById('password-label-text');
        const passwordHint = document.getElementById('password-hint');
        const passwordIcon = document.querySelector('#password-label i');
        const registerBox = document.getElementById('register-box');
        const registerDivider = document.getElementById('register-divider');

        roleTabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Remove active class dari semua tabs
                roleTabs.forEach(t => t.classList.remove('active'));
                
                // Add active class ke tab yang diklik
                tab.classList.add('active');
                
                // Get role yang dipilih
                const role = tab.dataset.role;
                userRoleInput.value = role;
                const config = roleConfig[role];
                
                // Update label dan placeholder
                usernameLabelText.textContent = config.usernameLabel;
                usernameInput.placeholder = config.usernamePlaceholder;
                usernameHint.textContent = config.usernameHint;
                usernameIcon.className = config.usernameIcon;
                
                passwordLabelText.textContent = config.passwordLabel;
                passwordInput.placeholder = config.passwordPlaceholder;
                passwordHint.textContent = config.passwordHint;
                passwordIcon.className = config.passwordIcon;
                
                // Show/Hide register box hanya untuk siswa
                if (role === 'siswa') {
                    registerBox.style.display = 'block';
                    registerDivider.style.display = 'block';
                } else {
                    registerBox.style.display = 'none';
                    registerDivider.style.display = 'none';
                }
                
                // Clear input dan focus ke username
                usernameInput.value = '';
                passwordInput.value = '';
                usernameInput.focus();
            });
        });
    </script>
</body>
</html>
