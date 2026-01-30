<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - Absensi QR' : 'Sistem Absensi QR Code' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #48bb78;
            --danger: #f56565;
            --warning: #ed8936;
            --info: #4299e1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            color: white !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            margin-left: 1rem;
            transition: 0.3s;
        }

        .nav-link:hover {
            color: white !important;
        }

        .sidebar {
            background: white;
            min-height: 100vh;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            padding: 2rem 0;
            position: fixed;
            width: 250px;
            left: 0;
            top: 80px;
        }

        .sidebar-nav {
            list-style: none;
        }

        .sidebar-nav li {
            margin: 0;
        }

        .sidebar-nav a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #333;
            text-decoration: none;
            transition: 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: #f0f0f0;
            color: var(--primary);
            border-left-color: var(--primary);
            font-weight: 600;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 80px;
            padding: 2rem;
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                top: 0;
                height: auto;
                box-shadow: none;
                border-bottom: 1px solid #ddd;
            }

            .main-content {
                margin-left: 0;
                margin-top: 80px;
            }

            .sidebar-nav a {
                display: inline-block;
                padding: 0.5rem 1rem;
            }
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            font-weight: 600;
            border: none;
            padding: 1.25rem;
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 4px solid var(--primary);
        }

        .stat-card h6 {
            color: #666;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stat-card h3 {
            font-weight: 700;
            color: var(--primary);
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 1.5rem;
        }

        .table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        .table th {
            border: none;
            font-weight: 600;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            border-color: #eee;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
            font-weight: 500;
        }

        .footer {
            background: #f8f9fa;
            border-top: 1px solid #ddd;
            padding: 2rem;
            margin-top: 4rem;
            text-align: center;
            color: #666;
        }

        .form-control,
        .form-select {
            border-radius: 6px;
            border: 1px solid #ddd;
            padding: 0.75rem 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-qrcode"></i> Absensi QR Code
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="fas fa-user-circle"></i> <?= session()->get('user_name') ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <?php if (session()->get('user_role') === 'admin'): ?>
        <aside class="sidebar">
            <ul class="sidebar-nav">
                <li><a href="/admin/dashboard" class="<?= $title === 'Dashboard Admin' ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a></li>
                <li><a href="/admin/siswa" class="<?= strpos($title, 'Siswa') !== false ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Kelola Siswa
                </a></li>
                <li><a href="/admin/qr-location" class="<?= strpos($title, 'Lokasi Absensi|Lokasi QR') !== false ? 'active' : '' ?>">
                    <i class="fas fa-map-marker-alt"></i> Lokasi Absensi
                </a></li>
                <li><a href="/admin/absensi" class="<?= strpos($title, 'Laporan') !== false ? 'active' : '' ?>">
                    <i class="fas fa-file-chart-line"></i> Laporan Absensi
                </a></li>
                <li><a href="/admin/izin-sakit" class="<?= strpos($title, 'Izin|Sakit') !== false ? 'active' : '' ?>">
                    <i class="fas fa-file-medical-alt"></i> Manajemen Izin & Sakit
                </a></li>
            </ul>
        </aside>
    <?php elseif (session()->get('user_role') === 'siswa'): ?>
        <aside class="sidebar">
            <ul class="sidebar-nav">
                <li><a href="/siswa/dashboard" class="<?= $title === 'Dashboard Siswa' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a></li>
                <li><a href="/siswa/scan-masuk" class="<?= strpos($title, 'Absen Masuk') !== false ? 'active' : '' ?>">
                    <i class="fas fa-camera"></i> Scan Masuk
                </a></li>
                <li><a href="/siswa/absen-pulang" class="<?= strpos($title, 'Absen Pulang') !== false ? 'active' : '' ?>">
                    <i class="fas fa-sign-out-alt"></i> Absen Pulang
                </a></li>
                <li><a href="/siswa/riwayat" class="<?= strpos($title, 'Riwayat Absensi') !== false ? 'active' : '' ?>">
                    <i class="fas fa-history"></i> Riwayat Absensi
                </a></li>
                <li><a href="/siswa/izin-sakit-create" class="<?= strpos($title, 'Pengajuan Izin|Riwayat Pengajuan') !== false ? 'active' : '' ?>">
                    <i class="fas fa-file-medical-alt"></i> Izin / Sakit
                </a></li>
            </ul>
        </aside>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Alert Messages -->
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Page Content -->
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Sistem Informasi Absensi Siswa QR Code | PKL SMK</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Auto dismiss alerts -->
    <script>
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                new bootstrap.Alert(alert).close();
            }, 5000);
        });
    </script>

    <!-- Mobile Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar');
            const sidebar = document.querySelector('.sidebar');
            
            if (navbar && sidebar) {
                const toggler = document.createElement('button');
                toggler.className = 'btn btn-outline-light ms-auto';
                toggler.style.display = 'none';
                toggler.innerHTML = '<i class="fas fa-bars"></i>';
                toggler.id = 'sidebarToggle';
                
                // Show toggle on mobile
                function updateSidebarToggle() {
                    if (window.innerWidth <= 991) {
                        toggler.style.display = 'inline-block';
                    } else {
                        toggler.style.display = 'none';
                        sidebar.classList.remove('show');
                    }
                }
                
                toggler.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.toggle('show');
                });
                
                // Add toggle to navbar
                const navContent = navbar.querySelector('.navbar-collapse');
                if (navContent) {
                    navContent.parentElement.insertBefore(toggler, navContent);
                }
                
                window.addEventListener('resize', updateSidebarToggle);
                updateSidebarToggle();
                
                // Close sidebar when link clicked
                sidebar.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 991) {
                            sidebar.classList.remove('show');
                        }
                    });
                });
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });

        // Auto-dismiss alerts after 5 seconds
        document.querySelectorAll('.alert:not(.alert-permanent)').forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Add animation to elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-slide-up');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card, .list-group-item').forEach(el => {
            observer.observe(el);
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
