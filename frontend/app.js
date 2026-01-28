const express = require('express');
const session = require('express-session');
const cookieParser = require('cookie-parser');
const axios = require('axios');
const path = require('path');
require('dotenv').config();

const app = express();

// ============================================
// KONFIGURASI
// ============================================
const PORT = process.env.PORT || 3000;
const API_BASE_URL = process.env.API_BASE_URL || 'http://localhost:8080';
const SESSION_SECRET = process.env.SESSION_SECRET || 'your-secret-key-change-in-production';

// ============================================
// MIDDLEWARE
// ============================================

// View engine setup (EJS)
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Static files
app.use(express.static(path.join(__dirname, 'public')));

// Body parser
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// Cookie parser
app.use(cookieParser());

// Session configuration
app.use(session({
    secret: SESSION_SECRET,
    resave: false,
    saveUninitialized: false,
    cookie: {
        secure: false, // Set ke true jika menggunakan HTTPS
        httpOnly: true,
        maxAge: 1000 * 60 * 60 * 24 // 24 jam
    }
}));

// ============================================
// AXIOS INSTANCE (untuk API CI4)
// ============================================
const apiClient = axios.create({
    baseURL: API_BASE_URL,
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json'
    }
});

// Middleware untuk menyimpan api client di request
app.use((req, res, next) => {
    req.apiClient = apiClient;
    next();
});

// ============================================
// MIDDLEWARE AUTHENTICATION
// ============================================

// Check jika user sudah login
const isLoggedIn = (req, res, next) => {
    if (req.session.user) {
        return next();
    }
    res.redirect('/login');
};

// Check jika user adalah siswa
const isSiswa = (req, res, next) => {
    if (req.session.user && req.session.user.role === 'siswa') {
        return next();
    }
    res.redirect('/login');
};

// Check jika user adalah admin
const isAdmin = (req, res, next) => {
    if (req.session.user && req.session.user.role === 'admin') {
        return next();
    }
    res.redirect('/login');
};

// ============================================
// ROUTES
// ============================================

// Route auth (login, logout)
app.use('/auth', require('./routes/auth'));

// Route absensi siswa (scan QR, absen pulang)
app.use('/absensi', isLoggedIn, isSiswa, require('./routes/absensi'));

// Route admin dashboard
app.use('/admin', isLoggedIn, isAdmin, require('./routes/admin'));

// ============================================
// HOME ROUTE
// ============================================
app.get('/', (req, res) => {
    if (req.session.user) {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (req.session.user.role === 'admin') {
            return res.redirect('/admin/dashboard');
        } else if (req.session.user.role === 'siswa') {
            return res.redirect('/absensi/dashboard');
        }
    }
    // Jika belum login, redirect ke login
    res.redirect('/login');
});

// ============================================
// LOGIN ROUTE
// ============================================
app.get('/login', (req, res) => {
    const message = req.query.message || null;
    const type = req.query.type || null;
    res.render('login', { message, type });
});

// ============================================
// LOGOUT ROUTE
// ============================================
app.get('/logout', (req, res) => {
    req.session.destroy((err) => {
        if (err) {
            return res.status(500).json({ success: false, message: 'Logout gagal' });
        }
        res.redirect('/login?message=Anda berhasil logout&type=success');
    });
});

// ============================================
// 404 HANDLER
// ============================================
app.use((req, res) => {
    res.status(404).render('404', {
        message: 'Halaman tidak ditemukan'
    });
});

// ============================================
// ERROR HANDLER
// ============================================
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).render('error', {
        message: 'Terjadi kesalahan server',
        error: process.env.NODE_ENV === 'development' ? err.message : ''
    });
});

// ============================================
// START SERVER
// ============================================
app.listen(PORT, () => {
    console.log(`\n✅ Frontend Server berjalan di http://localhost:${PORT}`);
    console.log(`   Backend API: ${API_BASE_URL}`);
    console.log(`   Environment: ${process.env.NODE_ENV || 'development'}\n`);
});

module.exports = app;
