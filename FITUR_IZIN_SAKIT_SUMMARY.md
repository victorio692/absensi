# 📋 FITUR IZIN/SAKIT & ALPHA OTOMATIS - SUMMARY

## ✅ FITUR YANG TELAH DIIMPLEMENTASIKAN

### 1. **SISWA WORKFLOW**

#### Menu Izin / Sakit (`/siswa/izin-sakit-create`)
- Form pengajuan dengan field:
  - Jenis pengajuan: Izin / Sakit (required)
  - Tanggal: default hari ini (required)
  - Alasan: textarea 5-500 karakter (required)
  - Bukti file: upload JPG/PNG/PDF max 5MB (optional)

#### Validasi Siswa Side:
✅ Hanya bisa submit sebelum jam 07:00 untuk hari ini
✅ Maksimal 1 pengajuan per hari per siswa
✅ Tidak boleh ada absensi QR di tanggal yang sama
✅ File upload: validasi tipe MIME dan ukuran
✅ Tanggal tidak boleh di masa lalu (kecuali hari ini)

#### Riwayat Pengajuan (`/siswa/izin-sakit-riwayat`)
- List semua pengajuan siswa dengan status:
  - 🟡 Menunggu (pending)
  - 🟢 Disetujui (approved)
  - 🔴 Ditolak (rejected)
- Action: Lihat Detail, Download Bukti

#### Detail Pengajuan (`/siswa/izin-sakit-detail/:id`)
- Informasi lengkap pengajuan
- Tampil catatan dari admin jika ada
- Download link untuk bukti file

---

### 2. **ADMIN WORKFLOW**

#### Dashboard Manajemen Izin & Sakit (`/admin/izin-sakit`)

**Statistics Cards:**
- 🟡 Menunggu: X pengajuan
- 🟢 Disetujui: X pengajuan
- 🔴 Ditolak: X pengajuan
- Total: X pengajuan

**Filter:**
- Status (Menunggu/Disetujui/Ditolak)
- Jenis (Izin/Sakit)
- Tanggal mulai - tanggal selesai

**Table:**
| No | Nama/Kelas | Jenis | Tanggal | Alasan | Status | Aksi |
|----|---|---|---|---|---|---|
| 1 | Ahmad/12A | Izin | 30/01/26 | Perlu ke dokter | ⏳ | [Lihat] [Setujui] [Tolak] |

#### Action Admin pada Pengajuan Pending:
✅ Setujui → Auto insert ke tabel absensi dengan status 'izin' atau 'sakit'
✅ Tolak → Siswa wajib absen QR, jika tidak akan dapat Alpha
✅ Lihat Detail → Download bukti file

#### Detail Pengajuan Admin (`/admin/izin-sakit-detail/:id`)
- Informasi lengkap siswa
- Tampil bukti file dengan preview
- Form Approval dengan catatan
  - Setujui + catatan → status=approved, auto insert absensi
  - Tolak + catatan → status=rejected
- Jika sudah di-process: tombol Hapus untuk undo

---

### 3. **ALPHA OTOMATIS SYSTEM**

#### Command untuk Generate Alpha:
```bash
php spark alpha:generate              # Generate untuk hari ini
php spark alpha:generate 2026-01-30   # Generate untuk tanggal spesifik
```

#### Logic Alpha:
```
FOR EACH siswa aktif:
  ├─ Apakah ada absensi di hari ini?
  │  ├─ YA → Skip (sudah tercatat)
  │  └─ TIDAK → Lanjut
  │
  ├─ Apakah ada izin/sakit yang disetujui di hari ini?
  │  ├─ YA → Skip (sudah tercatat di absensi)
  │  └─ TIDAK → Lanjut
  │
  └─ INSERT ke absensi dengan status='alpha'
     ├─ siswa_id: [ID]
     ├─ tanggal: [hari ini]
     ├─ status: 'alpha'
     ├─ source: 'system'
     └─ keterangan: 'Alpha otomatis - tidak absen dan tidak ada izin/sakit'
```

#### Autorun via Windows Task Scheduler:
- Create batch file: `C:\cronjobs\generate_alpha.bat`
- Setup task untuk jalan setiap hari jam 23:59
- Output log: `C:\cronjobs\logs\alpha_generate.log`

---

## 📊 DATABASE CHANGES

### Tabel Baru: `izin_sakit`

```sql
CREATE TABLE izin_sakit (
  id INT PRIMARY KEY AUTO_INCREMENT,
  siswa_id INT NOT NULL,
  tanggal DATE NOT NULL,
  jenis ENUM('izin','sakit'),
  alasan TEXT,
  bukti_file VARCHAR(255),
  status ENUM('pending','approved','rejected'),
  catatan_admin TEXT,
  created_at DATETIME,
  updated_at DATETIME,
  FOREIGN KEY (siswa_id) REFERENCES siswa(id)
);
```

### Tabel Update: `absensi`

**Kolom Baru:**
- `source` ENUM('qr', 'manual', 'izin', 'system')
- `keterangan` VARCHAR(255)

**Status Update:**
- Dari: ENUM('hadir', 'terlambat')
- Ke: ENUM('hadir', 'terlambat', 'izin', 'sakit', 'alpha')

---

## 🎨 UI/UX UPDATES

### Badge Status Colors:
| Status | Color | Hex |
|--------|-------|-----|
| Hadir | Green | #28a745 |
| Terlambat | Yellow | #ffc107 |
| Izin | Blue | #667eea |
| Sakit | Purple | #764ba2 |
| Alpha | Red | #dc3545 |

### Navigation Update:
- **Admin Menu**: Tambah "Manajemen Izin & Sakit"
- **Siswa Menu**: Tambah "Izin / Sakit"

### Responsive Design:
- Mobile-first CSS dengan Bootstrap 5.3
- Form validation feedback
- Badge dengan custom styling
- Alert messages yang jelas

---

## 📁 FILES CREATED

### Controllers:
- `app/Controllers/IzinSakit.php` - Siswa features
- `app/Controllers/AdminIzinSakit.php` - Admin features

### Models:
- `app/Models/IzinSakitModel.php`

### Views:
- `app/Views/siswa/izin_sakit/create.php`
- `app/Views/siswa/izin_sakit/riwayat.php`
- `app/Views/siswa/izin_sakit/detail.php`
- `app/Views/admin/izin_sakit/index.php`
- `app/Views/admin/izin_sakit/detail.php`

### Helpers:
- `app/Helpers/AlphaAutomaticHelper.php`

### Commands:
- `app/Commands/AlphaGenerate.php`

### Migrations:
- `app/Database/Migrations/2026-01-30-120001_CreateIzinSakitTable.php`
- `app/Database/Migrations/2026-01-30-120002_UpdateAbsensiTableStatus.php`

### Documentation:
- `DOKUMENTASI_IZIN_SAKIT_ALPHA.md`

---

## 🔒 KEAMANAN & VALIDASI

### Database Level:
✅ Foreign key constraints
✅ ENUM validation
✅ Required fields NOT NULL

### Application Level:
✅ CSRF token validation
✅ Session-based auth
✅ Role-based access control (admin/siswa)
✅ File upload validation (MIME type, size)
✅ Input sanitization dengan htmlspecialchars()

### Business Logic:
✅ Time validation (hanya sebelum 07:00)
✅ Duplicate prevention (1 pengajuan per hari)
✅ Conflict prevention (tidak bisa absen + izin same day)
✅ State management (pending → approved/rejected)

---

## 🧪 TESTING CHECKLIST

- [x] Test pengajuan izin form validation
- [x] Test pengajuan sakit dengan bukti file
- [x] Test upload file > 5 MB (ditolak)
- [x] Test submit izin setelah jam 07:00 (ditolak)
- [x] Test double submit izin (ditolak)
- [x] Test admin approve izin → auto insert absensi
- [x] Test admin reject izin → siswa harus absen
- [x] Test alpha generate dari command
- [x] Test alpha tidak di-generate untuk siswa yang sudah absen
- [x] Test alpha tidak di-generate untuk siswa dengan izin approved
- [x] Test laporan absensi menampilkan semua status
- [x] Test export PDF/Excel menampilkan summary baru

---

## 📝 USAGE EXAMPLES

### Siswa:
1. Go to `/siswa/izin-sakit-create`
2. Fill form: Jenis Izin, Tanggal, Alasan, Bukti (optional)
3. Submit → status: "Menunggu Persetujuan"
4. Admin review → Setujui/Tolak
5. Jika Setujui → Absensi otomatis tercatat sebagai Izin/Sakit
6. Jika Tolak → Siswa harus absen QR

### Admin:
1. Go to `/admin/izin-sakit`
2. See list of pending applications
3. Click "Lihat" → Detail page
4. Add note + "Setujui" → auto insert absensi
5. Or "Tolak" → siswa wajib absen QR

### Alpha Generate:
```bash
# Setiap malam 23:59 otomatis via Task Scheduler
php spark alpha:generate

# Atau manual
php spark alpha:generate 2026-01-29
```

---

## 🎯 HASIL AKHIR

### Status yang mungkin di Absensi:
- ✅ **Hadir**: Scan QR sebelum 07:00
- ⏰ **Terlambat**: Scan QR setelah 07:00
- 📝 **Izin**: Submit izin + disetujui admin
- 🏥 **Sakit**: Submit sakit + disetujui admin
- ❌ **Alpha**: Tidak absen + tidak ada izin/sakit

### Laporan Absensi:
- Menampilkan semua 5 status dengan warna berbeda
- Summary statistics: Hadir, Terlambat, Izin, Sakit, Alpha
- Export Excel/PDF dengan data lengkap

### Production Ready:
✅ Semua fitur terimplementasi
✅ Validasi lengkap di siswa dan admin
✅ Responsive design (mobile-friendly)
✅ Professional UI dengan modern design
✅ Dokumentasi lengkap
✅ Siap deploy ke production

---

## 🚀 NEXT STEPS (OPTIONAL)

Fitur tambahan yang bisa dikembangkan:
- [ ] Notification system (email/SMS saat approval)
- [ ] Dashboard analytics (chart attendance trends)
- [ ] Holiday calendar integration
- [ ] Parent notification system
- [ ] Mobile app (Flutter/React Native)
- [ ] Advanced reports (Hadir rate per class)
- [ ] Permission system untuk multiple admins

---

**Status: ✅ PRODUCTION READY**

Sistem Izin/Sakit & Alpha Otomatis siap digunakan di lingkungan sekolah resmi dengan semua fitur, validasi, dan dokumentasi lengkap.
