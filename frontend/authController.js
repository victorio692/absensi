/**
 * Auth Controller
 * Menangani login siswa dan admin
 */

// ============================================
// POST LOGIN - SISWA (NIS/NISN)
// ============================================
exports.loginSiswa = async (req, res) => {
    try {
        const { nisn, nis } = req.body;

        // Validasi input
        if (!nisn || !nis) {
            return res.status(400).render('login', {
                message: 'NISN dan NIS harus diisi',
                type: 'error'
            });
        }

        // Send login request ke CI4 API
        const response = await req.apiClient.post('/login', {
            username: nisn, // API CI4 menggunakan username untuk NISN
            password: nis   // API CI4 menggunakan password untuk NIS
        });

        // Check response dari API
        if (response.data.success) {
            // Set session
            req.session.user = {
                id: response.data.data.id,
                nisn: response.data.data.nisn || nisn,
                nis: response.data.data.nis || nis,
                nama: response.data.data.nama,
                kelas: response.data.data.kelas,
                role: 'siswa'
            };

            // Save session
            req.session.save((err) => {
                if (err) {
                    return res.status(500).render('login', {
                        message: 'Error menyimpan session',
                        type: 'error'
                    });
                }
                res.redirect('/absensi/dashboard');
            });
        } else {
            res.status(401).render('login', {
                message: response.data.message || 'Login gagal. NISN/NIS tidak valid',
                type: 'error'
            });
        }
    } catch (error) {
        console.error('Login Siswa Error:', error.message);
        
        let errorMessage = 'Terjadi kesalahan server';
        if (error.response?.data?.message) {
            errorMessage = error.response.data.message;
        } else if (error.code === 'ECONNREFUSED') {
            errorMessage = 'Backend API tidak dapat diakses';
        }

        res.status(500).render('login', {
            message: errorMessage,
            type: 'error'
        });
    }
};

// ============================================
// POST LOGIN - ADMIN
// ============================================
exports.loginAdmin = async (req, res) => {
    try {
        const { username, password } = req.body;

        // Validasi input
        if (!username || !password) {
            return res.status(400).render('login', {
                message: 'Username dan password harus diisi',
                type: 'error'
            });
        }

        // Send login request ke CI4 API
        const response = await req.apiClient.post('/admin/login', {
            username,
            password
        });

        // Check response dari API
        if (response.data.success) {
            // Set session
            req.session.user = {
                id: response.data.data.id,
                username: response.data.data.username,
                nama: response.data.data.nama || username,
                role: 'admin'
            };

            // Save session
            req.session.save((err) => {
                if (err) {
                    return res.status(500).render('login', {
                        message: 'Error menyimpan session',
                        type: 'error'
                    });
                }
                res.redirect('/admin/dashboard');
            });
        } else {
            res.status(401).render('login', {
                message: response.data.message || 'Login gagal. Username/Password tidak valid',
                type: 'error'
            });
        }
    } catch (error) {
        console.error('Login Admin Error:', error.message);
        
        let errorMessage = 'Terjadi kesalahan server';
        if (error.response?.data?.message) {
            errorMessage = error.response.data.message;
        } else if (error.code === 'ECONNREFUSED') {
            errorMessage = 'Backend API tidak dapat diakses';
        }

        res.status(500).render('login', {
            message: errorMessage,
            type: 'error'
        });
    }
};

// ============================================
// GET LOGOUT
// ============================================
exports.logout = (req, res) => {
    req.session.destroy((err) => {
        if (err) {
            return res.status(500).json({
                success: false,
                message: 'Logout gagal'
            });
        }
        res.redirect('/login?message=Anda berhasil logout&type=success');
    });
};
