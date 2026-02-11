<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi QR Code</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap">
    <style>
        :root {
            --color-primary: #4F46E5;
            --color-primary-light: #6366F1;
            --color-primary-dark: #4338CA;
            --color-background: #F8FAFC;
            --color-surface: #FFFFFF;
            --color-text: #1E293B;
            --color-text-secondary: #64748B;
            --color-text-tertiary: #94A3B8;
            --color-border: #E2E8F0;
            --color-success: #10B981;
            --color-danger: #EF4444;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 2.5rem;
            --spacing-3xl: 3rem;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition-smooth: 300ms ease-in-out;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-lg);
        }

        .login-wrapper {
            width: 100%;
            max-width: 450px;
        }

        .login-card {
            background: var(--color-surface);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            animation: slideUp 500ms ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
            color: white;
            padding: var(--spacing-2xl) var(--spacing-lg);
            text-align: center;
        }

        .login-header-icon {
            font-size: 2.5rem;
            margin-bottom: var(--spacing-md);
        }

        .login-header h2 {
            margin: 0;
            font-weight: 800;
            font-size: 1.875rem;
            letter-spacing: -0.01em;
        }

        .login-header p {
            margin: var(--spacing-sm) 0 0;
            opacity: 0.95;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .login-body {
            padding: var(--spacing-2xl);
        }

        .alert {
            padding: var(--spacing-lg);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-lg);
            border-left: 4px solid;
            animation: slideDown 300ms ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border-left-color: var(--color-danger);
            color: var(--color-danger);
        }

        .role-tabs {
            display: flex;
            gap: 0;
            margin-bottom: var(--spacing-xl);
            border-bottom: 2px solid var(--color-border);
        }

        .role-tab {
            flex: 1;
            padding: var(--spacing-lg) var(--spacing-md);
            border: none;
            background: none;
            cursor: pointer;
            color: var(--color-text-secondary);
            font-weight: 600;
            font-size: 0.875rem;
            transition: all var(--transition-smooth);
            border-bottom: 3px solid transparent;
            position: relative;
            bottom: -2px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .role-tab i {
            font-size: 1.25rem;
        }

        .role-tab.active {
            color: var(--color-primary);
            border-bottom-color: var(--color-primary);
        }

        .role-tab:hover {
            color: var(--color-primary);
        }

        .form-group {
            margin-bottom: var(--spacing-lg);
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            font-weight: 700;
            color: var(--color-text);
            margin-bottom: var(--spacing-sm);
            font-size: 0.875rem;
        }

        .form-label i {
            color: var(--color-primary);
        }

        .form-control {
            width: 100%;
            padding: var(--spacing-md) var(--spacing-lg);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-family: inherit;
            color: var(--color-text);
            background-color: var(--color-surface);
            transition: all var(--transition-smooth);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-hint {
            font-size: 0.8125rem;
            color: var(--color-text-tertiary);
            margin-top: var(--spacing-sm);
        }

        .btn-login {
            width: 100%;
            padding: var(--spacing-lg);
            background: var(--color-primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all var(--transition-smooth);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
            margin-top: var(--spacing-lg);
        }

        .btn-login:hover {
            background: var(--color-primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .divider {
            border: none;
            border-top: 1px solid var(--color-border);
            margin: var(--spacing-xl) 0;
        }

        .register-box {
            background: rgba(79, 70, 229, 0.05);
            border: 1px solid rgba(79, 70, 229, 0.2);
            border-left: 4px solid var(--color-primary);
            border-radius: var(--radius-lg);
            padding: var(--spacing-lg);
            text-align: center;
            display: none;
        }

        .register-box p {
            margin: 0 0 var(--spacing-md);
            color: var(--color-text);
            font-weight: 700;
            font-size: 0.875rem;
        }

        .register-box a {
            display: inline-block;
            background: var(--color-primary);
            color: white;
            padding: var(--spacing-md) var(--spacing-xl);
            border-radius: var(--radius-md);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .register-box a:hover {
            background: var(--color-primary-dark);
            transform: translateY(-2px);
        }

        .footer {
            text-align: center;
            margin-top: var(--spacing-2xl);
            color: white;
            font-size: 0.8125rem;
        }

        .footer p {
            margin: 0;
        }

        @media (max-width: 480px) {
            .role-tab {
                padding: var(--spacing-md);
            }

            .login-header h2 {
                font-size: 1.5rem;
            }

            .login-body {
                padding: var(--spacing-lg);
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <div class="login-header-icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h2>Absensi</h2>
                <p>Sistem Informasi Absensi Siswa QR Code</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <!-- Alert -->
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger">
                        <strong>
                            <i class="fas fa-exclamation-circle"></i> Error
                        </strong>
                        <p style="margin-top: var(--spacing-sm); margin-bottom: 0;">
                            <?= session()->getFlashdata('error') ?>
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Role Tabs -->
                <div class="role-tabs">
                    <button type="button" class="role-tab active" data-role="admin">
                        <i class="fas fa-user-shield"></i>
                        <span>Admin</span>
                    </button>
                    <button type="button" class="role-tab" data-role="siswa">
                        <i class="fas fa-user-graduate"></i>
                        <span>Siswa</span>
                    </button>
                </div>

                <!-- Form -->
                <form action="/login" method="POST" id="loginForm">
                    <?= csrf_field() ?>
                    <input type="hidden" id="user_role" name="user_role" value="admin">

                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="fas fa-user"></i>
                            <span id="username-label">Username</span>
                        </label>
                        <input type="text" class="form-control" id="username" name="username" 
                               placeholder="Masukkan username" autofocus required>
                        <div class="form-hint" id="username-hint">Gunakan akun admin</div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            <span id="password-label">Password</span>
                        </label>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Masukkan password" required>
                        <div class="form-hint" id="password-hint"></div>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> LOGIN
                    </button>
                </form>

                <!-- Register Box -->
                <hr class="divider" id="register-divider" style="display: none;">
                <div id="register-box" class="register-box">
                    <p>Belum punya akun?</p>
                    <a href="/register">
                        <i class="fas fa-user-plus"></i> Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2025 Sistem Informasi Absensi Siswa | PKL SMK</p>
        </div>
    </div>

    <script>
        // Role configuration
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

        // Tab switching
        document.querySelectorAll('.role-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Update active state
                document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // Get role
                const role = tab.dataset.role;
                const config = roleConfig[role];
                
                // Update form
                document.getElementById('user_role').value = role;
                document.getElementById('username-label').textContent = config.usernameLabel;
                document.getElementById('username').placeholder = config.usernamePlaceholder;
                document.getElementById('username-hint').textContent = config.usernameHint;
                document.querySelector('#username').parentElement.querySelector('i').className = config.usernameIcon;
                
                document.getElementById('password-label').textContent = config.passwordLabel;
                document.getElementById('password').placeholder = config.passwordPlaceholder;
                document.getElementById('password-hint').textContent = config.passwordHint;
                document.querySelector('#password').parentElement.querySelector('i').className = config.passwordIcon;
                
                // Show/hide register box
                const registerBox = document.getElementById('register-box');
                const registerDivider = document.getElementById('register-divider');
                if (role === 'siswa') {
                    registerBox.style.display = 'block';
                    registerDivider.style.display = 'block';
                } else {
                    registerBox.style.display = 'none';
                    registerDivider.style.display = 'none';
                }
                
                // Clear inputs
                document.getElementById('username').value = '';
                document.getElementById('password').value = '';
                document.getElementById('username').focus();
            });
        });

        // Auto-close alerts
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.animation = 'slideDown 300ms ease-out reverse';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    </script>
</body>
</html>
