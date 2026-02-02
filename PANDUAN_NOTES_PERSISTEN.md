# 📌 Panduan Sistem Catatan Persisten (Notes)

## 🎯 Tujuan

Sistem notes ini dirancang untuk mengganti flash data yang hilang setelah 5 detik dengan catatan penting yang **tetap persisten** dan dapat disimpan di database.

## 📊 Fitur Utama

### ✅ Catatan Persisten
- Catatan disimpan di database, tidak hilang setelah page refresh
- User dapat melihat semua catatan mereka kapan saja
- Status "read/unread" untuk tracking

### ✅ Auto-Dismiss Fleksibel
- Catatan penting bisa dipilih untuk tetap ditampilkan
- Catatan normal bisa auto-dismiss setelah X detik
- User bisa manual dismiss kapan saja

### ✅ Tipe Catatan Berbeda
- `success` - Catatan operasi berhasil (hijau)
- `error` - Catatan error penting (merah)
- `warning` - Peringatan (kuning)
- `info` - Informasi umum (biru)

---

## 🚀 Cara Penggunaan

### 1. Tambah Catatan Success (Otomatis Dismiss 5 Detik)

```php
// Di Controller
addSuccessNote('Lokasi QR berhasil ditambahkan!');

// Atau lengkap
addNote(
    message: 'Data berhasil disimpan',
    type: 'success',
    title: 'Sukses',
    isPermanent: false  // Auto-dismiss setelah 5 detik
);
```

**Output di View:**
```
✓ Sukses: Data berhasil disimpan
```

---

### 2. Tambah Catatan Error (Tetap Ditampilkan)

```php
// Di Controller
addErrorNote('Username sudah terdaftar!');

// Atau lengkap
addNote(
    message: 'File upload gagal, ukuran terlalu besar',
    type: 'error',
    title: 'Error Upload',
    isPermanent: true  // TETAP ditampilkan sampai user dismiss
);
```

**Output di View:**
```
✗ Error Upload: File upload gagal, ukuran terlalu besar
[X] (tombol close)
```

---

### 3. Tambah Warning (Auto-Dismiss 5 Detik)

```php
addWarningNote('Anda tidak dapat edit form ini');
```

---

### 4. Tambah Info (Auto-Dismiss 5 Detik)

```php
addInfoNote('Sistem akan maintenance jam 22:00');
```

---

## 📝 Contoh Implementasi di Controller

### Contoh 1: Controller QR Location

```php
<?php

namespace App\Controllers;

use App\Models\QrLocationModel;

class QrLocationController extends BaseController
{
    public function store()
    {
        $data = $this->request->getPost();

        // Validasi
        if (!$this->validate([
            'nama_lokasi' => 'required|min_length[3]',
            'keterangan'  => 'permit_empty|max_length[500]',
        ])) {
            // Error penting - tetap ditampilkan sampai user dismiss
            addErrorNote(
                'Validasi gagal: ' . implode(', ', $this->validator->getErrors()),
                'Validasi Error',
                isPermanent: true
            );
            return redirect()->back()->withInput();
        }

        // Simpan ke database
        $model = model('QrLocationModel');
        if ($model->insert($data)) {
            // Sukses - auto dismiss setelah 5 detik
            addSuccessNote('Lokasi QR berhasil ditambahkan!');
            return redirect()->to('/admin/qr-location');
        } else {
            // Error database - tetap ditampilkan
            addErrorNote(
                'Gagal menyimpan ke database: ' . $model->errors(),
                'Database Error',
                isPermanent: true
            );
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        $model = model('QrLocationModel');
        $item = $model->find($id);

        if (!$item) {
            addWarningNote('Data tidak ditemukan');
            return redirect()->to('/admin/qr-location');
        }

        if ($model->delete($id)) {
            addSuccessNote('Lokasi QR berhasil dihapus');
        } else {
            addErrorNote('Gagal menghapus data', isPermanent: true);
        }

        return redirect()->to('/admin/qr-location');
    }
}
?>
```

---

### Contoh 2: Izin Sakit Controller

```php
<?php

namespace App\Controllers;

use App\Models\IzinSakitModel;

class IzinSakitController extends BaseController
{
    public function create()
    {
        // Cek apakah sudah ada pengajuan hari ini
        $model = model('IzinSakitModel');
        $today = date('Y-m-d');

        if ($model->where('siswa_id', auth()->id())
                  ->where('tanggal_mulai', $today)
                  ->first()) {
            addInfoNote('Anda sudah mengajukan izin/sakit hari ini');
            return redirect()->to('/siswa/izin-sakit-riwayat');
        }

        return view('siswa/izin_sakit/create');
    }

    public function store()
    {
        $data = $this->request->getPost();
        $data['siswa_id'] = auth()->id();

        // Validasi file
        if ($this->request->getFile('bukti_file')) {
            $file = $this->request->getFile('bukti_file');

            if (!$file->isValid()) {
                addErrorNote('File tidak valid', isPermanent: true);
                return redirect()->back()->withInput();
            }

            if ($file->getSize() > 5242880) { // 5MB
                addErrorNote(
                    'Ukuran file terlalu besar (max 5MB)',
                    isPermanent: true
                );
                return redirect()->back()->withInput();
            }

            // Simpan file
            $filename = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/izin', $filename);
            $data['bukti_file'] = $filename;
        }

        $model = model('IzinSakitModel');
        if ($model->insert($data)) {
            addSuccessNote('Pengajuan izin/sakit berhasil dikirim!');
            return redirect()->to('/siswa/izin-sakit-riwayat');
        } else {
            addErrorNote(
                'Gagal menyimpan pengajuan',
                isPermanent: true
            );
            return redirect()->back()->withInput();
        }
    }

    public function approve($id)
    {
        $model = model('IzinSakitModel');
        $item = $model->find($id);

        if (!$item) {
            addWarningNote('Data tidak ditemukan');
            return redirect()->back();
        }

        if ($item['status'] !== 'pending') {
            addWarningNote('Status sudah berubah oleh user lain', isPermanent: true);
            return redirect()->back();
        }

        if ($model->update($id, ['status' => 'approved'])) {
            addSuccessNote('Pengajuan telah disetujui');
        } else {
            addErrorNote('Gagal memperbarui status', isPermanent: true);
        }

        return redirect()->back();
    }
}
?>
```

---

## 🎨 Styling Notes

Notes menggunakan Bootstrap classes standard:

```css
.alert-success { /* Hijau */ }
.alert-danger  { /* Merah */ }
.alert-warning { /* Kuning */ }
.alert-info    { /* Biru */ }
```

Custom CSS sudah ada di `public/css/style.css`:

```css
.alert {
    border: none;
    border-radius: 8px;
    border-left: 4px solid;
    padding: 1.25rem;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
```

---

## 🔧 Database Schema

```sql
CREATE TABLE `notes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED,
    `title` VARCHAR(255),
    `message` LONGTEXT,
    `type` ENUM('success', 'error', 'warning', 'info') DEFAULT 'info',
    `is_read` BOOLEAN DEFAULT FALSE,
    `is_permanent` BOOLEAN DEFAULT FALSE,
    `auto_dismiss_in` INT DEFAULT 0,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `deleted_at` DATETIME,
    KEY `is_read` (`is_read`),
    KEY `is_permanent` (`is_permanent`),
    KEY `created_at` (`created_at`)
);
```

---

## 📱 Frontend Behavior

### Saat Page Load
1. Notes ditampilkan dari database
2. Jika `is_permanent = false` dan ada `auto_dismiss_in`, akan auto-dismiss setelah X ms
3. User bisa manual dismiss dengan klik tombol [X]

### Saat Manual Dismiss
- Note ditandai sebagai `is_read = true`
- Tidak akan ditampilkan di load berikutnya
- Tersimpan di database untuk history

### API Endpoints

```
GET  /api/notes              → Get unread notes
POST /api/notes/{id}/read    → Mark as read
DELETE /api/notes/{id}       → Delete note
```

---

## ⚙️ Konfigurasi

### Di `.env` (Opsional)

```env
# Durasi default auto-dismiss (dalam milliseconds)
notes.auto_dismiss_duration = 5000

# Apakah backup notes lama
notes.archive_old_notes = true
notes.archive_after_days = 30
```

---

## 🔐 Security

✅ **User dapat hanya melihat notes mereka sendiri**
- Notes difilter berdasarkan `user_id` yang login
- API melakukan validasi user_id

✅ **CSRF Protection**
- Semua form POST dilindungi CSRF

✅ **Soft Delete**
- Notes dihapus dengan soft delete (preserved di DB)

---

## 📊 Comparison: Flash Data vs Persistent Notes

| Fitur | Flash Data | Persistent Notes |
|-------|-----------|------------------|
| Durasi | 5 detik | Permanen (sampai user dismiss) |
| Disimpan di DB | ❌ | ✅ |
| History | ❌ | ✅ |
| Read/Unread | ❌ | ✅ |
| Permanent Option | ❌ | ✅ |
| Auto-Dismiss | ✅ | ✅ (Fleksibel) |

---

## 🚀 Next Steps

Setelah implementasi ini, Anda bisa:

1. **Dashboard Notes** - Buat halaman untuk lihat semua notes history
2. **Email Notifications** - Kirim email untuk notes penting
3. **Real-time Updates** - WebSocket untuk live notes update
4. **Notes Categories** - Kategori catatan (System, User, Admin)
5. **Archive Old Notes** - Auto-archive notes lama

---

**Status: ✅ READY TO USE**
