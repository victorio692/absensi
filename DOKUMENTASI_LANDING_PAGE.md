# Dokumentasi Landing Page & Sistem Absensi QR Dinamis

## 📋 Ringkasan Perubahan

Sistem absensi QR dinamis sekolah telah diupdate dengan landing page profesional dan sistem catatan persistensi yang sempurna.

---

## 🚀 Fitur-Fitur Utama

### 1. **Landing Page Profesional** (`app/Views/landing.php`)
- **Responsif**: Optimal di desktop, tablet, dan mobile
- **8 Sections Utama**:
  1. Hero - Pengenalan sistem dengan CTA
  2. Masalah - 4 problems yang dihadapi sekolah
  3. Solusi - 4 solutions yang ditawarkan
  4. Fitur Utama - 6 feature cards
  5. Cara Kerja - 4 step process
  6. Keunggulan - 6 benefits
  7. CTA Section - Call-to-action akhir
  8. Footer - Informasi kontak

- **Design Features**:
  - Gradient modern (#667eea ke #764ba2)
  - Smooth scroll animations
  - Hover effects di semua elemen
  - Floating animations di background
  - Mobile-friendly hamburger menu
  - Intersection Observer untuk lazy animations

### 2. **Sistem Notes Persistensi** (Sudah terimplementasi)
- **Database**: Table `notes` dengan 11 columns
- **Model**: `app/Models/NotesModel.php`
- **Helper**: `app/Helpers/notes_helper.php` (8 functions)
- **API**: `app/Controllers/Api/NotesController.php` (3 endpoints)
- **Frontend**: Terintegrasi di `app/Views/layout.php`

---

## 📍 Struktur Routes

```php
// Home / Landing Page
GET / → view('landing')

// Auth Routes
GET /login → AuthController::index
POST /login → AuthController::login
GET /logout → AuthController::logout

// API Routes
GET /api/notes → NotesController::index (get unread notes)
POST /api/notes/{id}/read → NotesController::markRead
DELETE /api/notes/{id} → NotesController::delete

// Admin Routes (dengan auth filter)
GET /admin → AdminController::index
GET /admin/attendance → AdminController::attendance
// ... dll

// Student Routes (dengan auth filter)
GET /student → StudentController::index
GET /student/calendar → StudentController::calendar
// ... dll
```

---

## 🔧 Implementasi Teknis

### Helper Functions Yang Tersedia

```php
// Dalam controller atau view, gunakan:
addSuccessNote('Pesan sukses');
addErrorNote('Pesan error');
addWarningNote('Pesan warning');
addInfoNote('Pesan info');

// Retrieve
$notes = getUserNotes();
$unread = getUnreadNotes();

// Update
markNoteAsRead($note_id);
deleteNote($note_id);
```

### API Endpoints

```javascript
// Get unread notes
GET /api/notes

// Mark as read
POST /api/notes/123/read

// Delete note
DELETE /api/notes/123
```

### Session-Based Authentication

Sistem menggunakan **session-based authentication**, BUKAN `auth()` helper:

```php
// Cek user login
if (session()->has('user_id')) {
    $userId = session()->get('user_id');
}

// Set user login
session()->set(['user_id' => 1, 'user_name' => 'John']);

// Logout
session()->destroy();
```

---

## 🌐 Mengakses Aplikasi

### 1. **Landing Page** (Publik, tanpa login)
```
http://localhost:8080/
```
- Dapat diakses siapa saja
- 8 sections pengenalan sistem
- Login buttons ke `/login`

### 2. **Login Page**
```
http://localhost:8080/login
```
- Form login untuk Siswa dan Admin
- Session-based authentication
- Redirect ke dashboard setelah login

### 3. **Admin Dashboard** (Perlu login)
```
http://localhost:8080/admin
```
- Monitoring attendance real-time
- Generate QR codes
- Manage locations
- Export reports

### 4. **Student Dashboard** (Perlu login)
```
http://localhost:8080/student
```
- View attendance calendar
- Scan QR codes
- Submit izin/sakit

---

## 📱 Responsive Design

Landing page 100% responsive dengan breakpoints:
- **Desktop**: Penuh dengan 2-3 kolom
- **Tablet** (768px): 2 kolom untuk cards
- **Mobile** (< 768px): 1 kolom penuh dengan touch-friendly buttons

---

## 🎨 Warna & Styling

```css
Primary: #667eea (Ungu Biru)
Secondary: #764ba2 (Ungu Tua)
Success: #48bb78 (Hijau)
Danger: #f56565 (Merah)
Warning: #ed8936 (Orange)
Info: #4299e1 (Biru)
```

---

## ✅ Checklist Verifikasi

- [x] Landing page created (`app/Views/landing.php`)
- [x] Routes configured untuk "/" → landing page
- [x] Notes system fully implemented dan terintegrasi
- [x] Helper functions working di semua context
- [x] Auth() calls sudah diganti session() calls
- [x] Responsive design untuk semua devices
- [x] Smooth animations dengan Intersection Observer
- [x] Mobile menu hamburger working
- [x] SEO-friendly HTML structure

---

## 🔐 Security Notes

1. **CSRF Protection**: Gunakan `csrf_field()` di semua form
2. **Session Validation**: Check `session()->has('user_id')` sebelum akses data user
3. **SQL Injection**: Gunakan parameterized queries di Model
4. **XSS Prevention**: Gunakan `esc()` untuk output user-generated content

---

## 📊 Database Structure

### Notes Table
```sql
CREATE TABLE notes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT,
    type ENUM('success','error','warning','info'),
    message LONGTEXT,
    is_read BOOLEAN DEFAULT 0,
    is_permanent BOOLEAN DEFAULT 0,
    auto_dismiss_in INT (milliseconds),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
```

---

## 🚦 Next Steps (Optional Enhancements)

1. **Redirect authenticated users dari landing**:
   ```php
   if (session()->has('user_id')) {
       return redirect()->to('/student'); // atau /admin
   }
   ```

2. **Add testimonials section** di landing page

3. **Add screenshot carousel** di features section

4. **Implement contact form** di CTA section

5. **Add language selector** untuk multi-bahasa

6. **Add search functionality** untuk reports

---

## 📞 Support

Untuk pertanyaan atau issue, hubungi:
- Email: admin@absensi-qr.com
- WhatsApp: +62 xxx xxxx xxxx

---

**Dokumentasi Terakhir Update**: 2026-02-03
**Framework**: CodeIgniter 4.6.4
**Database**: MySQL 8.0+
**PHP Version**: 8.3.16+
