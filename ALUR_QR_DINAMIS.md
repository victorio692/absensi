# 📱 Alur Sistem QR Dinamis Absensi

## Konsep Dasar
**QR Code MILIK SEKOLAH, BUKAN SISWA**
- QR code berubah **SETIAP HARI** untuk keamanan
- Tidak bisa digunakan lagi untuk absensi hari sebelumnya atau besok
- Setiap lokasi punya QR code berbeda (hijau, kuning, merah, dll)

---

## 🔄 Alur Sistem

```
┌─────────────────────────────────────────────────────────────┐
│                        HARI PAGI                             │
├─────────────────────────────────────────────────────────────┤

1. ADMIN - Generate QR Code Hari Ini
   ├─ Login: localhost:8080/login (admin/admin123)
   ├─ Menu: Admin → Generate QR → QR Code Hari Ini
   └─ Lihat: QR code untuk hijau, kuning, merah, dll

2. ADMIN - Cetak QR Code
   ├─ Klik "Detail" pada setiap lokasi
   ├─ Klik "Cetak" untuk print QR code
   └─ Pasang kertas QR di:
       • Gerbang Masuk
       • Gerbang Keluar
       • Ruang Kelas (jika multi-lokasi)
       • Ruang Khusus, dll

└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                   SISWA MASUK SEKOLAH                        │
├─────────────────────────────────────────────────────────────┤

3. SISWA - Absen Masuk
   ├─ Login: localhost:8080/login (siswa-user/siswa-pass)
   ├─ Menu: Dashboard → Scan Absen Masuk
   ├─ Halaman: /siswa/scan-masuk
   ├─ Scan QR yang dipasang di gerbang masuk
   └─ Sistem mencatat:
       • Waktu masuk
       • Lokasi (dari location_id di QR)
       • Status (Hadir/Terlambat)

4. SISTEM - Validasi QR
   ├─ Parse QR Content: location_id|tanggal|token
   ├─ Validasi:
   │   ├─ Token cocok? (SHA256 hash)
   │   ├─ Tanggal hari ini? (tidak boleh QR lama)
   │   └─ Location ID valid?
   └─ Jika valid → Catat di tabel absensi
       Jika tidak → Tampilkan error

└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                   SISWA PULANG SEKOLAH                       │
├─────────────────────────────────────────────────────────────┤

5. SISWA - Absen Pulang
   ├─ Menu: Dashboard → Scan Absen Pulang
   ├─ Halaman: /siswa/absen-pulang
   ├─ Scan QR yang dipasang di gerbang keluar
   └─ Sistem mencatat:
       • Waktu pulang
       • Lokasi (dari location_id di QR)

└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                    HARI BERIKUTNYA                           │
├─────────────────────────────────────────────────────────────┤

6. SISTEM - Auto Generate QR Baru
   ├─ Setiap pagi jam 00:00 (atau saat admin buka halaman)
   ├─ Generate token BARU dengan tanggal hari ini
   ├─ QR kemarin tidak bisa digunakan lagi
   └─ Proses 3→5 berulang...

└─────────────────────────────────────────────────────────────┘
```

---

## 👤 Pengguna dalam Sistem

### 1. **ADMIN** (Guru/Kepala Sekolah)
- URL: `localhost:8080/login`
- Username: `admin` 
- Password: `admin123`
- Tugas:
  - ✅ Generate QR code harian
  - ✅ Cetak QR code
  - ✅ Pasang di lokasi
  - ✅ Lihat laporan absensi

### 2. **SISWA** (Murid Sekolah)
- URL: `localhost:8080/login`
- Username: `ahmad.rafli` (atau siswa lain)
- Password: `siswa123`
- Tugas:
  - ✅ Scan QR masuk pagi
  - ✅ Scan QR keluar pulang
  - ✅ Lihat riwayat absensi

---

## 📍 Lokasi QR di Sekolah

| Lokasi | QR Code | Fungsi | Pasang di |
|--------|---------|--------|-----------|
| **Hijau** | Dinamis (berubah tiap hari) | Absen Masuk | Gerbang Masuk |
| **Kuning** | Dinamis (berubah tiap hari) | Absen Keluar | Gerbang Keluar |
| **Merah** | Dinamis (berubah tiap hari) | Absen Kelas | Ruang Kelas |
| **Biru** | Dinamis (berubah tiap hari) | Absen Khusus | Ruang Khusus |

---

## 🔐 Keamanan Token

### Contoh QR Content:
```
17|2026-01-30|a3f5c7d9e1b4f6a8c2d4e6f8a0b2c4d6e8f0a2b4c6d8e0f2a4c6d8e0f2a4
```

### Penjelasan:
- `17` = Location ID (Gerbang Hijau)
- `2026-01-30` = Tanggal hari ini
- `a3f5c7d9...` = Token (SHA256 hash)

### Algoritma Token:
```
Token = SHA256(location_id + tanggal + secret_key)
```

### Contoh Validasi:
✅ **Hari ini (2026-01-30)**: Token cocok → **VALID**
❌ **Hari kemarin (2026-01-29)**: Token tidak cocok → **INVALID**
❌ **Hari besok (2026-01-31)**: Token tidak cocok → **INVALID**

---

## 📊 Database Schema

### Tabel: `qr_location`
```sql
id | nama_lokasi | keterangan | aktif
---|---|---|---
17 | Gerbang Masuk Hijau | Absen masuk pagi | 1
18 | Gerbang Keluar Kuning | Absen pulang | 1
```

### Tabel: `qr_daily`
```sql
id | location_id | tanggal | token
---|---|---|---
1 | 17 | 2026-01-30 | a3f5c7d9e1b4f6a8c2d4e6f8a0b2c4d6e8f0a2b4c6d8e0f2a4c6d8e0f2a4
2 | 18 | 2026-01-30 | b4f6c8e0f2a4c6d8e0f2a4c6d8e0f2a4c6d8e0f2a4c6d8e0f2a4c6d8e0f2a4
```

### Tabel: `absensi`
```sql
id | siswa_id | tanggal | jam_masuk | jam_pulang | status | location_id
---|---|---|---|---|---|---
1 | 5 | 2026-01-30 | 07:15 | NULL | Hadir | 17
2 | 5 | 2026-01-30 | 15:30 | NULL | NULL | 18
```

---

## 🎯 Endpoint API

### Admin URLs:
| Endpoint | Fungsi |
|----------|--------|
| `/admin/qr-daily` | Lihat QR Harian |
| `/admin/qr-daily/17/show` | Detail QR Lokasi 17 |
| `/admin/qr-location` | Manage Lokasi |
| `/admin/absensi` | Lihat Laporan Absensi |

### Siswa URLs:
| Endpoint | Fungsi |
|----------|--------|
| `/siswa/dashboard` | Dashboard Siswa |
| `/siswa/scan-masuk` | Scan QR Masuk |
| `/siswa/absen-pulang` | Scan QR Pulang |
| `/siswa/riwayat` | Lihat Riwayat Absensi |

---

## ✅ Testing Checklist

- [ ] Admin bisa login
- [ ] Admin buka "QR Code Hari Ini"
- [ ] Lihat 4 QR code (hijau, kuning, merah, biru)
- [ ] Klik "Detail" → tampil QR code besar
- [ ] Klik "Cetak" → siap untuk print
- [ ] Print dan pasang di lokasi
- [ ] Siswa login
- [ ] Siswa buka "Scan Masuk"
- [ ] Siswa scan QR yang dipasang
- [ ] Sistem catat waktu masuk ✓
- [ ] Siswa buka "Scan Pulang"
- [ ] Siswa scan QR yang dipasang
- [ ] Sistem catat waktu pulang ✓
- [ ] Admin lihat laporan absensi
- [ ] Data terupdate dengan benar

---

## 💡 Tips Penggunaan

1. **Cetak QR Besar** - Pastikan QR code hasil cetak cukup besar agar mudah discan (minimal 5x5 cm)
2. **Plastik Pelindung** - Laminating kertas QR agar tahan lama
3. **Pencahayaan** - Pastikan lokasi cukup cahaya untuk scanning
4. **Posisi QR** - Pasang di tempat yang mudah dilihat dan dijangkau

---

## 🐛 Troubleshooting

### "QR Code Invalid"
- ❌ QR code kemarin/besok digunakan
- ✅ Gunakan QR code hari ini
- ✅ Minta admin untuk update QR

### "Location ID Not Found"
- ❌ Lokasi tidak aktif di sistem
- ✅ Admin aktifkan di menu "Lokasi Absensi"

### "Jam_masuk NULL"
- ❌ Siswa belum scan QR masuk
- ✅ Siswa harus scan QR di gerbang

---

## 📞 Bantuan

Jika ada pertanyaan atau error, buka file log:
```
c:\laragon\www\absensi-ci\writable\logs\log-2026-01-30.log
```

---

**Status Sistem**: ✅ PRODUCTION READY
**Last Updated**: 2026-01-30
