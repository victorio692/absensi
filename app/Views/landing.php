<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Sekolah Berbasis QR Dinamis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #48bb78;
            --danger: #f56565;
            --warning: #ed8936;
            --info: #4299e1;
            --light: #f7fafc;
            --dark: #2d3748;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2d3748;
            background: #fff;
        }

        /* NAVBAR */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 1.2rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            color: #2d3748 !important;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary) !important;
        }

        /* HERO SECTION */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 150px 20px 100px;
            text-align: center;
            margin-top: 70px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(30px); }
        }

        .hero-content {
            position: relative;
            z-index: 1;
            animation: slideUp 0.8s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-group-hero {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .btn-login {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-login-siswa {
            background: white;
            color: var(--primary);
        }

        .btn-login-siswa:hover {
            background: #f7fafc;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            color: var(--primary);
        }

        .btn-login-admin {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
        }

        .btn-login-admin:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-3px);
        }

        /* SECTION STYLES */
        section {
            padding: 80px 20px;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
            color: var(--dark);
            position: relative;
            padding-bottom: 2rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        /* PROBLEMS SECTION */
        .problems {
            background: #f7fafc;
        }

        .problem-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--danger);
        }

        .problem-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .problem-card i {
            font-size: 3rem;
            color: var(--danger);
            margin-bottom: 1rem;
        }

        .problem-card h4 {
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .problem-card p {
            color: #718096;
            margin-bottom: 0;
        }

        /* SOLUTIONS SECTION */
        .solutions {
            background: white;
        }

        .solution-card {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .solution-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
        }

        .solution-card i {
            font-size: 2.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .solution-card h5 {
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .solution-card p {
            color: #718096;
            margin-bottom: 0;
        }

        /* FEATURES SECTION */
        .features {
            background: #f7fafc;
        }

        .feature-card {
            background: white;
            padding: 2.5rem 2rem;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin: 0 auto 1.5rem;
        }

        .feature-card h5 {
            color: var(--dark);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .feature-card p {
            color: #718096;
            margin-bottom: 0;
        }

        /* HOW IT WORKS SECTION */
        .how-it-works {
            background: white;
        }

        .step {
            position: relative;
            padding: 2rem;
            text-align: center;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .step h5 {
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .step p {
            color: #718096;
            margin-bottom: 0;
        }

        /* ADVANTAGES SECTION */
        .advantages {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        .advantages .section-title,
        .advantages .section-title::after {
            color: white;
        }

        .advantages .section-title::after {
            background: rgba(255, 255, 255, 0.5);
        }

        .advantage-item {
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid white;
            transition: all 0.3s ease;
        }

        .advantage-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(10px);
        }

        .advantage-item i {
            color: white;
            margin-right: 1rem;
            font-size: 1.3rem;
        }

        .advantage-item span {
            font-weight: 500;
        }

        /* CTA SECTION */
        .cta {
            background: white;
            text-align: center;
        }

        .cta h2 {
            color: var(--dark);
            margin-bottom: 1.5rem;
            font-size: 2.5rem;
            font-weight: 700;
        }

        .cta p {
            color: #718096;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* FOOTER */
        footer {
            background: var(--dark);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        footer p {
            margin-bottom: 0.5rem;
        }

        footer a {
            color: var(--primary);
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .hero {
                padding: 120px 20px 80px;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .btn-group-hero {
                flex-direction: column;
            }

            .btn-login {
                width: 100%;
            }

            .section-title {
                font-size: 1.8rem;
            }

            section {
                padding: 60px 20px;
            }

            .feature-card,
            .problem-card,
            .solution-card {
                margin-bottom: 1.5rem;
            }

            .step {
                padding: 1.5rem;
            }

            .cta h2 {
                font-size: 1.8rem;
            }
        }

        /* SCROLL ANIMATION */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-lg">
            <a class="navbar-brand" href="/">
                <i class="fas fa-qrcode"></i> Absensi QR Dinamis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#fitur">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#cara-kerja">Cara Kerja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#keunggulan">Keunggulan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">
                            <strong>Masuk</strong>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="container-lg hero-content">
            <h1>Sistem Absensi Sekolah Berbasis QR Dinamis</h1>
            <p>Absensi cepat, aman, dan real-time dengan QR Code yang berubah setiap hari.</p>
            <div class="btn-group-hero">
                <a href="/login" class="btn-login btn-login-siswa">
                    <i class="fas fa-user-graduate"></i> Login Siswa
                </a>
                <a href="/login" class="btn-login btn-login-admin">
                    <i class="fas fa-user-shield"></i> Login Admin
                </a>
            </div>
        </div>
    </section>

    <!-- MASALAH SECTION -->
    <section class="problems">
        <div class="container-lg">
            <h2 class="section-title">Masalah yang Sering Dihadapi</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="problem-card fade-in">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h4>Absensi Manual</h4>
                        <p>Rawan kecurangan dan memakan waktu</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="problem-card fade-in">
                        <i class="fas fa-lock-open"></i>
                        <h4>QR Statis</h4>
                        <p>Mudah disalahgunakan dan dimanipulasi</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="problem-card fade-in">
                        <i class="fas fa-hourglass"></i>
                        <h4>Rekap Manual</h4>
                        <p>Proses rekapitulasi yang memakan waktu</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="problem-card fade-in">
                        <i class="fas fa-eye-slash"></i>
                        <h4>Tidak Transparan</h4>
                        <p>Data kehadiran kurang transparan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SOLUSI SECTION -->
    <section class="solutions">
        <div class="container-lg">
            <h2 class="section-title">Solusi Sistem Kami</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="solution-card fade-in">
                        <i class="fas fa-lock"></i>
                        <h5>QR Dinamis Aman</h5>
                        <p>QR Code berubah setiap hari mencegah penyalahgunaan</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="solution-card fade-in">
                        <i class="fas fa-map-marker-alt"></i>
                        <h5>Multi Lokasi</h5>
                        <p>Absensi di berbagai lokasi sekolah tersedia</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="solution-card fade-in">
                        <i class="fas fa-check-circle"></i>
                        <h5>Status Otomatis</h5>
                        <p>Hadir, Terlambat, Izin, Sakit, Alpha tercatat otomatis</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="solution-card fade-in">
                        <i class="fas fa-chart-line"></i>
                        <h5>Laporan Real-time</h5>
                        <p>Monitoring dan laporan kehadiran real-time</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FITUR UTAMA SECTION -->
    <section class="features" id="fitur">
        <div class="container-lg">
            <h2 class="section-title">Fitur-Fitur Utama</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h5>QR Dinamis Aman</h5>
                        <p>QR Code yang berubah setiap hari dengan teknologi keamanan tinggi</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon">
                            <i class="fas fa-map-location-dot"></i>
                        </div>
                        <h5>Multi Lokasi Absensi</h5>
                        <p>Setup absensi di berbagai lokasi dan ruangan di sekolah</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-days"></i>
                        </div>
                        <h5>Kalender Absensi</h5>
                        <p>Tampilan kalender bulanan yang mudah dipantau siswa</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon">
                            <i class="fas fa-file-medical-alt"></i>
                        </div>
                        <h5>Izin & Sakit Online</h5>
                        <p>Pengajuan izin dan sakit langsung melalui aplikasi</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon">
                            <i class="fas fa-robot"></i>
                        </div>
                        <h5>Alpha Otomatis</h5>
                        <p>Sistem pencatatan alpha otomatis berdasarkan pengaturan waktu</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon">
                            <i class="fas fa-file-export"></i>
                        </div>
                        <h5>Export Laporan</h5>
                        <p>Export laporan ke PDF & Excel dengan mudah dan cepat</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CARA KERJA SECTION -->
    <section class="how-it-works" id="cara-kerja">
        <div class="container-lg">
            <h2 class="section-title">Cara Kerja Sistem</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="step fade-in">
                        <div class="step-number">1</div>
                        <h5>Admin Setup QR</h5>
                        <p>Admin menyiapkan QR Code di lokasi-lokasi absensi sekolah</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step fade-in">
                        <div class="step-number">2</div>
                        <h5>Siswa Scan QR</h5>
                        <p>Siswa memindai QR Code menggunakan aplikasi saat masuk sekolah</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step fade-in">
                        <div class="step-number">3</div>
                        <h5>Sistem Mencatat</h5>
                        <p>Sistem otomatis mencatat waktu, lokasi, dan status kehadiran</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step fade-in">
                        <div class="step-number">4</div>
                        <h5>Admin Pantau</h5>
                        <p>Admin memantau dan membuat laporan absensi secara real-time</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- KEUNGGULAN SECTION -->
    <section class="advantages" id="keunggulan">
        <div class="container-lg">
            <h2 class="section-title">Keunggulan Sistem Kami</h2>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="advantage-item fade-in">
                        <i class="fas fa-shield-alt"></i>
                        <span>Mengurangi kecurangan absensi hingga 100%</span>
                    </div>
                    <div class="advantage-item fade-in">
                        <i class="fas fa-mobile-alt"></i>
                        <span>Mudah digunakan oleh siswa dan guru</span>
                    </div>
                    <div class="advantage-item fade-in">
                        <i class="fas fa-check-double"></i>
                        <span>Data akurat dan tercatat otomatis</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="advantage-item fade-in">
                        <i class="fas fa-eye"></i>
                        <span>Laporan transparan dan mudah dipantau</span>
                    </div>
                    <div class="advantage-item fade-in">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Cocok untuk lingkungan sekolah modern</span>
                    </div>
                    <div class="advantage-item fade-in">
                        <i class="fas fa-bolt"></i>
                        <span>Kecepatan absensi hanya dalam hitungan detik</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="cta">
        <div class="container-lg">
            <h2>Siap Meningkatkan Sistem Absensi Sekolah Anda?</h2>
            <p>Bergabunglah dengan sekolah-sekolah modern yang telah menggunakan sistem kami.</p>
            <div class="btn-group-hero">
                <a href="/login" class="btn-login btn-login-siswa">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                </a>
                <a href="#keunggulan" class="btn-login" style="background: var(--primary); color: white; text-decoration: none;">
                    <i class="fas fa-arrow-down"></i> Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container-lg">
            <p><strong>Sistem Absensi Sekolah Berbasis QR Dinamis</strong></p>
            <p>&copy; 2026 | Sistem Absensi Sekolah | Dikembangkan untuk Pendidikan Modern</p>
            <p>Hubungi: <a href="mailto:info@absensi-qr.com">info@absensi-qr.com</a></p>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Scroll animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Smooth scroll for navbar links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });

        // Auto-close mobile menu on link click
        const navbarCollapse = document.querySelector('.navbar-collapse');
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (navbarCollapse.classList.contains('show')) {
                    document.querySelector('.navbar-toggler').click();
                }
            });
        });
    </script>
</body>
</html>
