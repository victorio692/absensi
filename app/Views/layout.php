<?php 
// Load helpers
helper(['notes_helper', 'form', 'url']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - Absensi QR' : 'Sistem Absensi QR Code' ?></title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Modern Design CSS -->
    <link rel="stylesheet" href="<?= base_url('css/modern-design.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <div class="app-wrapper">
        <!-- Navbar -->
        <nav class="navbar-modern">
            <div class="navbar-brand">
                <i class="fas fa-qrcode"></i> Absensi Siswa
            </div>
            <div class="navbar-right">
                <div class="navbar-user">
                    <span style="color: var(--color-text-secondary); font-size: var(--font-sm);">
                        <?= session()->get('user_name') ?>
                    </span>
                    <div class="navbar-user-avatar">
                        <?= substr(session()->get('user_name'), 0, 1) ?>
                    </div>
                    <a href="/logout" style="color: var(--color-text-secondary); text-decoration: none;">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </nav>

        <div class="app-container">
            <!-- Sidebar -->
            <?php if (session()->get('user_role') === 'admin'): ?>
                <aside class="sidebar-modern">
                    <div class="sidebar-nav-group">
                        <div class="sidebar-nav-title">Main</div>
                        <a href="/admin/dashboard" class="sidebar-nav-item <?= $title === 'Dashboard Admin' ? 'active' : '' ?>">
                            <i class="fas fa-chart-line"></i>
                            <span class="sidebar-label">Dashboard</span>
                        </a>
                    </div>

                    <div class="sidebar-nav-group">
                        <div class="sidebar-nav-title">Management</div>
                        <a href="/admin/siswa" class="sidebar-nav-item <?= strpos($title, 'Siswa|kelola') !== false ? 'active' : '' ?>">
                            <i class="fas fa-users"></i>
                            <span class="sidebar-label">Kelola Siswa</span>
                        </a>
                        <a href="/admin/absensi" class="sidebar-nav-item <?= strpos($title, 'Laporan') !== false ? 'active' : '' ?>">
                            <i class="fas fa-file-chart-line"></i>
                            <span class="sidebar-label">Laporan Absensi</span>
                        </a>
                        <a href="/admin/qr-location" class="sidebar-nav-item <?= strpos($title, 'Lokasi') !== false ? 'active' : '' ?>">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="sidebar-label">Lokasi Absensi</span>
                        </a>
                    </div>

                    <div class="sidebar-nav-group">
                        <div class="sidebar-nav-title">Approvals</div>
                        <a href="/admin/izin-sakit" class="sidebar-nav-item <?= strpos($title, 'Izin|Sakit') !== false ? 'active' : '' ?>">
                            <i class="fas fa-file-medical-alt"></i>
                            <span class="sidebar-label">Izin & Sakit</span>
                        </a>
                    </div>

                    <div class="sidebar-nav-group">
                        <div class="sidebar-nav-title">Settings</div>
                        <a href="/admin/pengaturan" class="sidebar-nav-item <?= strpos($title, 'Pengaturan') !== false ? 'active' : '' ?>">
                            <i class="fas fa-cog"></i>
                            <span class="sidebar-label">Pengaturan</span>
                        </a>
                    </div>
                </aside>
            <?php elseif (session()->get('user_role') === 'siswa'): ?>
                <aside class="sidebar-modern">
                    <div class="sidebar-nav-group">
                        <div class="sidebar-nav-title">Main</div>
                        <a href="/siswa/dashboard" class="sidebar-nav-item <?= $title === 'Dashboard Siswa' ? 'active' : '' ?>">
                            <i class="fas fa-home"></i>
                            <span class="sidebar-label">Dashboard</span>
                        </a>
                    </div>

                    <div class="sidebar-nav-group">
                        <div class="sidebar-nav-title">Attendance</div>
                        <a href="/siswa/scan-masuk" class="sidebar-nav-item <?= strpos($title, 'Absen Masuk') !== false ? 'active' : '' ?>">
                            <i class="fas fa-camera"></i>
                            <span class="sidebar-label">Scan Masuk</span>
                        </a>
                        <a href="/siswa/absen-pulang" class="sidebar-nav-item <?= strpos($title, 'Absen Pulang') !== false ? 'active' : '' ?>">
                            <i class="fas fa-door-open"></i>
                            <span class="sidebar-label">Absen Pulang</span>
                        </a>
                        <a href="/siswa/riwayat" class="sidebar-nav-item <?= strpos($title, 'Riwayat') !== false ? 'active' : '' ?>">
                            <i class="fas fa-history"></i>
                            <span class="sidebar-label">Riwayat Absensi</span>
                        </a>
                    </div>

                    <div class="sidebar-nav-group">
                        <div class="sidebar-nav-title">Requests</div>
                        <a href="/siswa/izin-sakit-create" class="sidebar-nav-item <?= strpos($title, 'Pengajuan|Riwayat Pengajuan') !== false ? 'active' : '' ?>">
                            <i class="fas fa-file-medical-alt"></i>
                            <span class="sidebar-label">Izin / Sakit</span>
                        </a>
                    </div>
                </aside>
            <?php endif; ?>

            <!-- Main Content -->
            <main class="main-content">
                <div class="content-wrapper">
                    <!-- Persistent Notes -->
                    <?php if (session()->has('user_id') && function_exists('getUnreadNotes')): ?>
                        <div id="notes-container">
                            <?php 
                                $unreadNotes = getUnreadNotes();
                                foreach ($unreadNotes as $note): 
                                    $alertClass = match($note['type']) {
                                        'success' => 'alert-success',
                                        'error' => 'alert-danger',
                                        'warning' => 'alert-warning',
                                        'info' => 'alert-info',
                                        default => 'alert-info'
                                    };
                                    
                                    $icon = match($note['type']) {
                                        'success' => 'fa-check-circle',
                                        'error' => 'fa-exclamation-circle',
                                        'warning' => 'fa-exclamation-triangle',
                                        'info' => 'fa-info-circle',
                                        default => 'fa-info-circle'
                                    };
                            ?>
                                <div class="alert-modern <?= $alertClass ?>" 
                                     role="alert" 
                                     data-note-id="<?= $note['id'] ?>"
                                     data-auto-dismiss="<?= $note['auto_dismiss_in'] * 1000 ?>"
                                     data-is-permanent="<?= $note['is_permanent'] ? 'true' : 'false' ?>">
                                    <i class="fas <?= $icon ?>"></i>
                                    <div>
                                        <?php if ($note['title']): ?>
                                            <strong><?= $note['title'] ?>:</strong>
                                        <?php endif; ?>
                                        <p><?= $note['message'] ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Flash Alert Messages -->
                    <?php if (session()->has('success')): ?>
                        <div class="alert-modern alert-success" role="alert">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <p><?= session()->getFlashdata('success') ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('error')): ?>
                        <div class="alert-modern alert-error" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <p><?= session()->getFlashdata('error') ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Page Content -->
                    <?= $this->renderSection('content') ?>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer class="footer-modern">
            <p>&copy; 2025 Sistem Informasi Absensi Siswa QR Code | PKL SMK</p>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        // Manage persistent notes
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('notes-container');
            if (!container) return;

            // Handle note dismissal and mark as read
            container.querySelectorAll('.alert-modern').forEach(alert => {
                const noteId = alert.dataset.noteId;
                const autoDismiss = parseInt(alert.dataset.autoDismiss) || 0;
                const isPermanent = alert.dataset.isPermanent === 'true';

                // Auto dismiss non-permanent notes
                if (!isPermanent && autoDismiss > 0) {
                    setTimeout(() => {
                        alert.remove();
                        markNoteAsRead(noteId);
                    }, autoDismiss);
                }
            });

            // Auto dismiss flash messages after 5 seconds
            const flashAlerts = document.querySelectorAll('.alert-modern:not([data-note-id])');
            flashAlerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.animation = 'fadeOut 300ms ease-in-out forwards';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });

        // Function to mark note as read via AJAX
        function markNoteAsRead(noteId) {
            fetch(`/api/notes/${noteId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).catch(err => console.log('Note marked as read'));
        }

        // Add fade-out animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
