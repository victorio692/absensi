# Dokumentasi: Alpha Otomatis Sistem QR Dinamis

## Deskripsi

Fitur **Alpha Otomatis** adalah mekanisme sistem untuk memberikan status "Alpha" (Tidak Hadir) kepada siswa yang:
- Tidak melakukan check-in (scan QR masuk)
- Tidak mengajukan izin/sakit
- Tidak memiliki izin/sakit yang disetujui

Status Alpha ini otomatis di-generate oleh sistem setiap hari untuk memastikan semua siswa aktif memiliki data absensi.

---

## Cara Menggunakan

### 1. **Manual Testing (Dari Terminal)**

```bash
cd c:\laragon\www\absensi-ci

# Generate alpha untuk hari ini
php spark alpha:generate

# Generate alpha untuk tanggal spesifik
php spark alpha:generate 2026-01-30
```

**Output:**
```
✓ Berhasil generate 5 alpha record
Message: Alpha otomatis berhasil di-generate
```

---

### 2. **Via URL (Direct API Call)**

Jika Anda ingin membuat endpoint HTTP untuk trigger alpha:

```
GET /admin/alpha/generate
GET /admin/alpha/generate?tanggal=2026-01-30
```

Tambahkan ke Routes.php:
```php
$routes->get('admin/alpha/generate', 'AlphaController::generate');
```

Buat file `app/Controllers/AlphaController.php`:
```php
<?php

namespace App\Controllers;

use App\Helpers\AlphaAutomaticHelper;

class AlphaController extends BaseController
{
    public function generate()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }

        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $result = AlphaAutomaticHelper::generateAlpha($tanggal);

        return redirect()->back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message'] . ' (' . $result['generated'] . ' records)'
        );
    }
}
```

---

### 3. **Cronjob (Windows Task Scheduler)**

#### **Step 1: Buat Batch File**

Buat file: `C:\cronjobs\generate_alpha.bat`

```batch
@echo off
cd /d C:\laragon\www\absensi-ci
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe spark alpha:generate >> C:\cronjobs\logs\alpha_generate.log 2>&1
exit /b 0
```

#### **Step 2: Setup Windows Task Scheduler**

1. Buka **Task Scheduler**
2. Klik **Create Basic Task**
3. Beri nama: "Alpha Otomatis Absensi"
4. **Trigger**: Harian, jam 23:59 (setengah menit sebelum tengah malam)
5. **Action**: Start a program
   - Program: `C:\cronjobs\generate_alpha.bat`
6. Klik **Finish**

---

### 4. **Cronjob (Linux/macOS - cPanel/Hosting)**

Jika server di hosting Linux dengan cPanel:

```bash
# Edit crontab
crontab -e

# Tambahkan baris ini (jalankan setiap hari jam 23:59)
59 23 * * * cd /home/username/public_html/absensi-ci && php spark alpha:generate >> /var/log/alpha_generate.log 2>&1

# Atau setiap hari jam 00:00 (tengah malam)
0 0 * * * cd /home/username/public_html/absensi-ci && php spark alpha:generate >> /var/log/alpha_generate.log 2>&1
```

---

## Logic & Flow

### Flow Proses Alpha Otomatis:

```
START
  ↓
Ambil semua siswa dengan status='aktif'
  ↓
FOR EACH siswa:
  ↓
  Cek: Apakah ada absensi untuk tanggal ini?
  ├─ YA → Skip ke siswa berikutnya
  └─ TIDAK → Lanjut
  ↓
  Cek: Apakah ada izin/sakit yang disetujui (status='approved')?
  ├─ YA → Skip ke siswa berikutnya
  └─ TIDAK → Lanjut
  ↓
  INSERT ke table absensi:
  ├─ siswa_id: [ID siswa]
  ├─ tanggal: [tanggal hari ini]
  ├─ status: 'alpha'
  ├─ source: 'system'
  ├─ keterangan: 'Alpha otomatis - tidak absen dan tidak ada izin/sakit'
  └─ created_at: NOW()
  ↓
  Increment counter
  ↓
END LOOP
  ↓
Log hasil: "Generated X alpha records for 2026-01-30"
  ↓
END
```

---

## Database Records

### Contoh Data Absensi dengan Alpha:

**Table: `absensi`**

| id | siswa_id | tanggal    | jam_masuk | jam_pulang | status     | source | keterangan |
|---|---|---|---|---|---|---|---|
| 1 | 1 | 2026-01-30 | 06:45:00  | 15:30:00   | hadir      | qr     | Scan QR masuk |
| 2 | 2 | 2026-01-30 | NULL      | NULL       | izin       | izin   | Izin disetujui oleh admin |
| 3 | 3 | 2026-01-30 | NULL      | NULL       | alpha      | system | Alpha otomatis - tidak absen dan tidak ada izin/sakit |
| 4 | 4 | 2026-01-30 | 07:15:00  | NULL       | terlambat  | qr     | Scan QR masuk |

**Penjelasan:**
- Siswa 1: Hadir (scan sebelum 07:00)
- Siswa 2: Izin (sudah submit izin dan disetujui admin)
- Siswa 3: Alpha (tidak absen dan tidak ada izin)
- Siswa 4: Terlambat (scan setelah 07:00)

---

## Fitur Izin/Sakit

### Alur Pengajuan Izin/Sakit:

```
SISWA
  ↓
Menu: /siswa/izin-sakit-create
  ↓
Form: Pilih Jenis (Izin/Sakit) + Tanggal + Alasan + Bukti (opsional)
  ↓
Validasi:
  ├─ Hanya bisa submit sebelum jam 07:00 untuk hari ini
  ├─ Maksimal 1 pengajuan per hari
  ├─ Tidak boleh ada absensi di tanggal yang sama
  └─ File upload: JPG, PNG, PDF (max 5 MB)
  ↓
Submit ke Database
  ├─ status: 'pending'
  ├─ bukti_file: [nama file]
  └─ created_at: NOW()
  ↓
Siswa TIDAK BISA absen QR di tanggal tersebut
  ↓
ADMIN
  ↓
Menu: /admin/izin-sakit
  ↓
Admin melihat list pengajuan (pending, approved, rejected)
  ↓
Action:
  ├─ Setujui → INSERT ke table absensi, status='izin' atau 'sakit'
  ├─ Tolak → Siswa wajib absen, jika tidak akan dapat Alpha
  └─ Lihat Detail → Download bukti file
  ↓
END
```

---

## Testing Checklist

- [ ] Test pengajuan izin (form validation)
- [ ] Test pengajuan sakit dengan bukti file
- [ ] Test upload file > 5 MB (harus ditolak)
- [ ] Test submit izin setelah jam 07:00 (harus ditolak)
- [ ] Test double submit izin di hari yang sama (harus ditolak)
- [ ] Test admin approve izin → otomatis insert ke absensi dengan status 'izin'
- [ ] Test admin reject izin → siswa harus absen, jika tidak akan Alpha
- [ ] Test alpha generate dari command line
- [ ] Test alpha generate via Windows Task Scheduler
- [ ] Test alpha tidak di-generate untuk siswa yang sudah absen
- [ ] Test alpha tidak di-generate untuk siswa yang punya izin approved
- [ ] Test laporan absensi menampilkan Hadir, Terlambat, Izin, Sakit, Alpha
- [ ] Test export PDF/Excel menampilkan semua status

---

## Troubleshooting

### Problem: Alpha tidak ter-generate

**Solusi:**
1. Cek apakah PHP CLI bisa akses database
2. Cek file `writable/logs/log-*.log` untuk error message
3. Test manual: `php spark alpha:generate 2026-01-30`
4. Pastikan semua siswa punya `status='aktif'` di table siswa

### Problem: Alpha ter-generate double

**Solusi:**
1. Gunakan helper `AlphaAutomaticHelper::isAlphaAlreadyGenerated($tanggal)` untuk cek
2. Jangan jalankan command lebih dari 1x per hari
3. Setup cronjob untuk jalan hanya 1x per hari

### Problem: File upload tidak tersimpan

**Solusi:**
1. Pastikan folder `writable/uploads/izin_sakit/` ada dan writable
2. Create folder jika belum ada:
   ```bash
   mkdir -p writable/uploads/izin_sakit
   chmod 755 writable/uploads/izin_sakit
   ```
3. Check file permissions

---

## Helpful Commands

```bash
# Generate alpha untuk hari ini
php spark alpha:generate

# Generate alpha untuk tanggal spesifik
php spark alpha:generate 2026-01-29

# Check database
php spark db:seed DatabaseSeeder

# View logs
tail -f writable/logs/log-2026-01-30.log

# Test model
php spark tinker
>>> $alpha = new \App\Helpers\AlphaAutomaticHelper();
>>> $result = $alpha->generateAlpha('2026-01-30');
>>> print_r($result);
```

---

## Summary

Fitur Izin/Sakit & Alpha Otomatis **production-ready** dengan:
✅ Validasi lengkap di siswa dan admin side
✅ Upload file dengan validasi tipe & ukuran
✅ Alpha otomatis tanpa intervensi admin
✅ Cronjob support (command line)
✅ Export PDF/Excel menampilkan semua status
✅ Responsive mobile-first design
✅ Modern professional UI
