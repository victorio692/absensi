/**
 * Absensi Controller
 * Menangani absensi siswa (scan QR, absen pulang)
 */

// ============================================
// GET DASHBOARD SISWA
// ============================================
exports.dashboard = async (req, res) => {
    try {
        const siswa = req.session.user;

        // Get status absensi hari ini dari API
        const response = await req.apiClient.get(`/absensi/status?siswa_id=${siswa.id}`);

        const data = {
            siswa,
            absensi: response.data.data || {},
            message: null
        };

        res.render('dashboard_siswa', data);
    } catch (error) {
        console.error('Dashboard Error:', error.message);
        res.render('dashboard_siswa', {
            siswa: req.session.user,
            absensi: {},
            message: 'Error mengambil data absensi'
        });
    }
};

// ============================================
// GET HALAMAN SCAN QR
// ============================================
exports.scanQr = (req, res) => {
    const siswa = req.session.user;
    res.render('scan_qr', { siswa });
};

// ============================================
// POST SCAN QR - ABSEN MASUK
// ============================================
exports.scanQrAbsenMasuk = async (req, res) => {
    try {
        const { qr_code } = req.body;
        const siswa = req.session.user;

        // Validasi QR code
        if (!qr_code || qr_code.trim() === '') {
            return res.status(400).json({
                success: false,
                message: 'QR Code tidak valid'
            });
        }

        // Send ke API CI4
        const response = await req.apiClient.post('/absensi/scan', {
            siswa_id: siswa.id,
            qr_code: qr_code.trim()
        });

        if (response.data.success) {
            return res.json({
                success: true,
                message: 'Absen masuk berhasil!',
                data: response.data.data
            });
        } else {
            return res.status(400).json({
                success: false,
                message: response.data.message || 'Absen gagal'
            });
        }
    } catch (error) {
        console.error('Scan QR Error:', error.message);
        
        let errorMessage = 'Terjadi kesalahan server';
        if (error.response?.data?.message) {
            errorMessage = error.response.data.message;
        }

        res.status(500).json({
            success: false,
            message: errorMessage
        });
    }
};

// ============================================
// GET HALAMAN ABSEN PULANG
// ============================================
exports.absenPulang = async (req, res) => {
    try {
        const siswa = req.session.user;

        // Check apakah sudah absen masuk hari ini
        const response = await req.apiClient.get(`/absensi/status?siswa_id=${siswa.id}`);

        res.render('absen_pulang', {
            siswa,
            absensi: response.data.data || {}
        });
    } catch (error) {
        console.error('Absen Pulang Error:', error.message);
        res.render('absen_pulang', {
            siswa: req.session.user,
            absensi: {},
            message: 'Error mengambil data absensi'
        });
    }
};

// ============================================
// POST ABSEN PULANG
// ============================================
exports.postAbsenPulang = async (req, res) => {
    try {
        const { qr_code } = req.body;
        const siswa = req.session.user;

        // Validasi QR code
        if (!qr_code || qr_code.trim() === '') {
            return res.status(400).json({
                success: false,
                message: 'QR Code tidak valid'
            });
        }

        // Send ke API CI4
        const response = await req.apiClient.post('/absensi/pulang', {
            siswa_id: siswa.id,
            qr_code: qr_code.trim()
        });

        if (response.data.success) {
            return res.json({
                success: true,
                message: 'Absen pulang berhasil!',
                data: response.data.data
            });
        } else {
            return res.status(400).json({
                success: false,
                message: response.data.message || 'Absen pulang gagal'
            });
        }
    } catch (error) {
        console.error('Absen Pulang Error:', error.message);
        
        let errorMessage = 'Terjadi kesalahan server';
        if (error.response?.data?.message) {
            errorMessage = error.response.data.message;
        }

        res.status(500).json({
            success: false,
            message: errorMessage
        });
    }
};

// ============================================
// GET STATUS ABSENSI SISWA (AJAX)
// ============================================
exports.getStatus = async (req, res) => {
    try {
        const siswa = req.session.user;

        const response = await req.apiClient.get(`/absensi/status?siswa_id=${siswa.id}`);

        res.json({
            success: true,
            data: response.data.data || {}
        });
    } catch (error) {
        console.error('Get Status Error:', error.message);
        
        res.status(500).json({
            success: false,
            message: 'Error mengambil status'
        });
    }
};
