-- Create qr_location table
CREATE TABLE IF NOT EXISTS qr_location (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lokasi VARCHAR(100) NOT NULL,
    keterangan TEXT,
    aktif BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create qr_daily table
CREATE TABLE IF NOT EXISTS qr_daily (
    id INT AUTO_INCREMENT PRIMARY KEY,
    location_id INT NOT NULL,
    tanggal DATE NOT NULL,
    token VARCHAR(255) NOT NULL COMMENT 'Hash token untuk validasi QR',
    qr_content VARCHAR(500) NOT NULL COMMENT 'Isi QR: location_id|tanggal|token',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (location_id) REFERENCES qr_location(id) ON DELETE CASCADE ON UPDATE CASCADE,
    KEY idx_location_tanggal (location_id, tanggal)
);

-- Update absensi table to add location_id if not exists
ALTER TABLE absensi ADD COLUMN IF NOT EXISTS location_id INT AFTER siswa_id COMMENT 'Lokasi QR Code yang di-scan';

-- Add foreign key constraint if not exists
ALTER TABLE absensi ADD CONSTRAINT fk_absensi_location FOREIGN KEY (location_id) REFERENCES qr_location(id) ON DELETE SET NULL ON UPDATE CASCADE;

-- Insert sample QR locations
INSERT INTO qr_location (nama_lokasi, keterangan, aktif) VALUES
('Gerbang Utama', 'Lokasi di gerbang masuk utama sekolah', TRUE),
('Aula', 'Lokasi di aula sekolah', TRUE),
('Perpustakaan', 'Lokasi di ruang perpustakaan', TRUE),
('Kantor', 'Lokasi di kantor tata usaha', TRUE)
ON DUPLICATE KEY UPDATE nama_lokasi=VALUES(nama_lokasi);

SELECT 'QR Location tables created successfully!' AS status;
