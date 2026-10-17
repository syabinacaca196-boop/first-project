USE `museum-cimanuk`;

CREATE TABLE IF NOT EXISTS kategori_koleksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    deskripsi TEXT,
    gambar VARCHAR(255),
    sort_order INT NOT NULL DEFAULT 0,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS jam_operasional (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hari VARCHAR(30) NOT NULL,
    jam_buka VARCHAR(10) NOT NULL DEFAULT '',
    jam_tutup VARCHAR(10) NOT NULL DEFAULT '',
    tutup TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @has_jam_hari_unique = (
    SELECT COUNT(*)
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'jam_operasional'
      AND INDEX_NAME = 'uq_jam_operasional_hari'
);
SET @add_jam_hari_unique = IF(
    @has_jam_hari_unique = 0,
    'ALTER TABLE jam_operasional ADD UNIQUE KEY uq_jam_operasional_hari (hari)',
    'SELECT 1'
);
PREPARE add_jam_hari_unique FROM @add_jam_hari_unique;
EXECUTE add_jam_hari_unique;
DEALLOCATE PREPARE add_jam_hari_unique;

ALTER TABLE beranda
    ADD COLUMN IF NOT EXISTS sort_order INT NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS is_published TINYINT(1) NOT NULL DEFAULT 1,
    ADD COLUMN IF NOT EXISTS image_position VARCHAR(30) NOT NULL DEFAULT 'center';

ALTER TABLE jelajah
    ADD COLUMN IF NOT EXISTS sort_order INT NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS is_published TINYINT(1) NOT NULL DEFAULT 1;

ALTER TABLE koleksi
    ADD COLUMN IF NOT EXISTS kategori_id INT NULL,
    ADD COLUMN IF NOT EXISTS sejarah TEXT NULL,
    ADD COLUMN IF NOT EXISTS filosofi TEXT NULL,
    ADD COLUMN IF NOT EXISTS sort_order INT NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS is_featured TINYINT(1) NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS is_published TINYINT(1) NOT NULL DEFAULT 1;

ALTER TABLE galeri
    ADD COLUMN IF NOT EXISTS sort_order INT NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS image_position VARCHAR(30) NOT NULL DEFAULT 'center',
    ADD COLUMN IF NOT EXISTS is_published TINYINT(1) NOT NULL DEFAULT 1;

ALTER TABLE artikel
    ADD COLUMN IF NOT EXISTS tanggal DATE NULL,
    ADD COLUMN IF NOT EXISTS penulis VARCHAR(150) NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS sort_order INT NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS is_published TINYINT(1) NOT NULL DEFAULT 1;

ALTER TABLE kontak
    ADD COLUMN IF NOT EXISTS telepon VARCHAR(50) NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS email VARCHAR(150) NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS maps_url VARCHAR(500) NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS sort_order INT NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS is_published TINYINT(1) NOT NULL DEFAULT 1;

CREATE TABLE IF NOT EXISTS pesan_kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subjek VARCHAR(200) NOT NULL,
    pesan TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'baru',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO kategori_koleksi (nama, slug, deskripsi, sort_order)
VALUES
    ('Alat Dapur Tradisional', 'alat-dapur-tradisional', 'Peralatan rumah tangga dan dapur tradisional.', 1),
    ('Alat Komunikasi', 'alat-komunikasi', 'Peralatan komunikasi dari berbagai masa.', 2),
    ('Surat dan Dokumen Bersejarah', 'surat-dokumen-bersejarah', 'Surat, arsip, dan dokumen sejarah.', 3),
    ('Keris dan Senjata Tradisional', 'keris-senjata-tradisional', 'Senjata tradisional dan benda terkait.', 4),
    ('Prangko', 'prangko', 'Koleksi prangko dan filateli.', 5),
    ('Koleksi Umum', 'koleksi-umum', 'Koleksi museum yang belum dimasukkan ke kategori lain.', 999)
ON DUPLICATE KEY UPDATE slug = VALUES(slug);

UPDATE koleksi
SET kategori_id = (SELECT id FROM kategori_koleksi WHERE slug = 'koleksi-umum' LIMIT 1)
WHERE kategori_id IS NULL;

UPDATE koleksi SET sort_order = id WHERE sort_order = 0;
UPDATE koleksi
SET is_featured = 1
WHERE is_published = 1
    AND NOT EXISTS (SELECT id FROM (SELECT id FROM koleksi WHERE is_published = 1 AND is_featured = 1) AS featured_items)
ORDER BY sort_order ASC, id DESC
LIMIT 1;
UPDATE artikel SET tanggal = DATE(created_at) WHERE tanggal IS NULL;
UPDATE kontak SET telepon = '0819-4694-0740' WHERE telepon = '';
UPDATE kontak SET email = 'museum.bandarcimanuk@gmail.com' WHERE email = '';
UPDATE kontak SET maps_url = 'https://maps.google.com/?q=Jl.+Veteran+No.+5+Lemahabang+Indramayu' WHERE maps_url = '';
UPDATE kontak
SET alamat = 'Jl. Veteran No. 5 Lemahabang, Indramayu'
WHERE alamat LIKE 'Alamat museum%';
UPDATE kontak
SET deskripsi = 'Indramayu, Tanah Sejarah, Lautan Cerita.'
WHERE deskripsi LIKE 'Telepon:%' OR deskripsi = 'Informasi kontak Museum Bandar Cimanuk';

INSERT INTO jam_operasional (hari,jam_buka,jam_tutup,tutup,sort_order)
SELECT 'Senin','','',1,1 WHERE NOT EXISTS (SELECT 1 FROM jam_operasional WHERE hari='Senin');
INSERT INTO jam_operasional (hari,jam_buka,jam_tutup,tutup,sort_order)
SELECT 'Selasa','08:00','16:00',0,2 WHERE NOT EXISTS (SELECT 1 FROM jam_operasional WHERE hari='Selasa');
INSERT INTO jam_operasional (hari,jam_buka,jam_tutup,tutup,sort_order)
SELECT 'Rabu','08:00','16:00',0,3 WHERE NOT EXISTS (SELECT 1 FROM jam_operasional WHERE hari='Rabu');
INSERT INTO jam_operasional (hari,jam_buka,jam_tutup,tutup,sort_order)
SELECT 'Kamis','08:00','16:00',0,4 WHERE NOT EXISTS (SELECT 1 FROM jam_operasional WHERE hari='Kamis');
INSERT INTO jam_operasional (hari,jam_buka,jam_tutup,tutup,sort_order)
SELECT 'Jumat','08:00','16:00',0,5 WHERE NOT EXISTS (SELECT 1 FROM jam_operasional WHERE hari='Jumat');
INSERT INTO jam_operasional (hari,jam_buka,jam_tutup,tutup,sort_order)
SELECT 'Sabtu','08:00','16:00',0,6 WHERE NOT EXISTS (SELECT 1 FROM jam_operasional WHERE hari='Sabtu');
INSERT INTO jam_operasional (hari,jam_buka,jam_tutup,tutup,sort_order)
SELECT 'Minggu','08:00','16:00',0,7 WHERE NOT EXISTS (SELECT 1 FROM jam_operasional WHERE hari='Minggu');

SET @has_koleksi_category_fk = (
    SELECT COUNT(*)
    FROM information_schema.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = DATABASE()
      AND TABLE_NAME = 'koleksi'
      AND CONSTRAINT_NAME = 'fk_koleksi_kategori'
);
SET @add_koleksi_category_fk = IF(
    @has_koleksi_category_fk = 0,
    'ALTER TABLE koleksi ADD CONSTRAINT fk_koleksi_kategori FOREIGN KEY (kategori_id) REFERENCES kategori_koleksi(id) ON DELETE SET NULL',
    'SELECT 1'
);
PREPARE add_koleksi_category_fk FROM @add_koleksi_category_fk;
EXECUTE add_koleksi_category_fk;
DEALLOCATE PREPARE add_koleksi_category_fk;
