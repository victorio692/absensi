/**
 * Admin Controller
 * Menangani dashboard admin dan laporan absensi
 */

// ============================================
// GET DASHBOARD ADMIN
// ============================================
exports.dashboard = async (req, res) => {
    try {
        const admin = req.session.user;
        const { tanggal, kelas } = req.query;

        // Build params
        let params = {};
        if (tanggal) params.tanggal = tanggal;
        if (kelas) params.kelas = kelas;

        // Get laporan dari API
        const response = await req.apiClient.get('/absensi/laporan', { params });

        res.render('dashboard_admin', {
            admin,
            data: response.data.data || [],
            filter: { tanggal, kelas },
            message: null
        });
    } catch (error) {
        console.error('Dashboard Admin Error:', error.message);
        res.render('dashboard_admin', {
            admin: req.session.user,
            data: [],
            filter: {},
            message: 'Error mengambil data laporan'
        });
    }
};

// ============================================
// GET LAPORAN ABSENSI (JSON)
// ============================================
exports.getLaporan = async (req, res) => {
    try {
        const { tanggal, kelas, page = 1 } = req.query;

        // Build params
        let params = { page };
        if (tanggal) params.tanggal = tanggal;
        if (kelas) params.kelas = kelas;

        // Get dari API
        const response = await req.apiClient.get('/absensi/laporan', { params });

        res.json({
            success: true,
            data: response.data.data || [],
            pagination: response.data.pagination || {}
        });
    } catch (error) {
        console.error('Get Laporan Error:', error.message);
        
        res.status(500).json({
            success: false,
            message: 'Error mengambil laporan'
        });
    }
};

// ============================================
// GET DETAIL SISWA
// ============================================
exports.detailSiswa = async (req, res) => {
    try {
        const { siswa_id } = req.params;
        const { tanggal_awal, tanggal_akhir } = req.query;

        // Build params
        let params = {};
        if (tanggal_awal) params.tanggal_awal = tanggal_awal;
        if (tanggal_akhir) params.tanggal_akhir = tanggal_akhir;

        // Get detail siswa dari API
        const response = await req.apiClient.get(`/absensi/siswa/${siswa_id}`, { params });

        res.render('detail_siswa', {
            admin: req.session.user,
            siswa: response.data.data || {},
            riwayat: response.data.riwayat || []
        });
    } catch (error) {
        console.error('Detail Siswa Error:', error.message);
        res.status(404).render('error', {
            message: 'Data siswa tidak ditemukan'
        });
    }
};

// ============================================
// GET EXPORT LAPORAN (PDF/EXCEL)
// ============================================
exports.exportLaporan = async (req, res) => {
    try {
        const { tanggal, kelas, format = 'excel' } = req.query;

        // Build params
        let params = { format };
        if (tanggal) params.tanggal = tanggal;
        if (kelas) params.kelas = kelas;

        // Get dari API
        const response = await req.apiClient.get('/absensi/export', {
            params,
            responseType: 'arraybuffer'
        });

        // Set response headers
        const contentType = format === 'pdf' 
            ? 'application/pdf' 
            : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        
        res.setHeader('Content-Type', contentType);
        res.setHeader('Content-Disposition', `attachment; filename="laporan-absensi.${format === 'pdf' ? 'pdf' : 'xlsx'}"`);
        
        res.send(Buffer.from(response.data));
    } catch (error) {
        console.error('Export Laporan Error:', error.message);
        res.status(500).json({
            success: false,
            message: 'Error export laporan'
        });
    }
};

// ============================================
// GET DAFTAR LOKASI QR
// ============================================
exports.lokasiQr = async (req, res) => {
    try {
        const response = await req.apiClient.get('/qr-location');

        res.render('lokasi_qr', {
            admin: req.session.user,
            locations: response.data.data || [],
            message: null
        });
    } catch (error) {
        console.error('Lokasi QR Error:', error.message);
        res.render('lokasi_qr', {
            admin: req.session.user,
            locations: [],
            message: 'Error mengambil data lokasi'
        });
    }
};
