CREATE DATABASE IF NOT EXISTS `museum-cimanuk` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `museum-cimanuk`;

CREATE TABLE IF NOT EXISTS admin (
 id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL
);

-- Password admin123 disimpan dalam bentuk hash PHP password_hash.
INSERT INTO admin (username,password)
SELECT 'admin', '$2y$12$xNYCe3TMQaEFt9nJ.JeNGeSNYvkqb2P5fq5OdWjRINUfvb1pdZp96'
WHERE NOT EXISTS (SELECT 1 FROM admin WHERE username='admin');

CREATE TABLE IF NOT EXISTS kategori_koleksi (
 id INT AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(150) NOT NULL, slug VARCHAR(180) NOT NULL UNIQUE,
 deskripsi TEXT, gambar VARCHAR(255), sort_order INT NOT NULL DEFAULT 0,
 is_published TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS beranda (
 id INT AUTO_INCREMENT PRIMARY KEY, judul VARCHAR(200) NOT NULL, deskripsi TEXT,
 gambar VARCHAR(255), sort_order INT NOT NULL DEFAULT 0,
 is_published TINYINT(1) NOT NULL DEFAULT 1, image_position VARCHAR(30) NOT NULL DEFAULT 'center',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS jelajah LIKE beranda;
CREATE TABLE IF NOT EXISTS koleksi (
 id INT AUTO_INCREMENT PRIMARY KEY, judul VARCHAR(200) NOT NULL, kategori_id INT NULL,
 deskripsi TEXT, sejarah TEXT, filosofi TEXT, gambar VARCHAR(255),
 sort_order INT NOT NULL DEFAULT 0, is_featured TINYINT(1) NOT NULL DEFAULT 0,
 is_published TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_koleksi_kategori FOREIGN KEY (kategori_id) REFERENCES kategori_koleksi(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS galeri (
 id INT AUTO_INCREMENT PRIMARY KEY, judul VARCHAR(200) NOT NULL, deskripsi TEXT,
 gambar VARCHAR(255), sort_order INT NOT NULL DEFAULT 0,
 image_position VARCHAR(30) NOT NULL DEFAULT 'center', is_published TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS artikel (
 id INT AUTO_INCREMENT PRIMARY KEY, judul VARCHAR(200) NOT NULL, deskripsi TEXT, isi LONGTEXT,
 gambar VARCHAR(255), tanggal DATE NULL, penulis VARCHAR(150) NOT NULL DEFAULT '',
 sort_order INT NOT NULL DEFAULT 0, is_published TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS kontak (
 id INT AUTO_INCREMENT PRIMARY KEY, judul VARCHAR(200) NOT NULL, deskripsi TEXT, alamat TEXT,
 telepon VARCHAR(50) NOT NULL DEFAULT '', email VARCHAR(150) NOT NULL DEFAULT '',
 maps_url VARCHAR(500) NOT NULL DEFAULT '', sort_order INT NOT NULL DEFAULT 0,
 is_published TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pesan_kontak (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nama VARCHAR(150) NOT NULL,
 email VARCHAR(150) NOT NULL,
 subjek VARCHAR(200) NOT NULL,
 pesan TEXT NOT NULL,
 status VARCHAR(20) NOT NULL DEFAULT 'baru',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS jam_operasional (
 id INT AUTO_INCREMENT PRIMARY KEY, hari VARCHAR(30) NOT NULL,
 jam_buka VARCHAR(10) NOT NULL DEFAULT '', jam_tutup VARCHAR(10) NOT NULL DEFAULT '',
 tutup TINYINT(1) NOT NULL DEFAULT 0, sort_order INT NOT NULL DEFAULT 0,
 UNIQUE KEY uq_jam_operasional_hari (hari),
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO kategori_koleksi (nama,slug,deskripsi,sort_order)
VALUES
 ('Alat Dapur Tradisional','alat-dapur-tradisional','Peralatan rumah tangga dan dapur tradisional.',1),
 ('Alat Komunikasi','alat-komunikasi','Peralatan komunikasi dari berbagai masa.',2),
 ('Surat dan Dokumen Bersejarah','surat-dokumen-bersejarah','Surat, arsip, dan dokumen sejarah.',3),
 ('Keris dan Senjata Tradisional','keris-senjata-tradisional','Senjata tradisional dan benda terkait.',4),
 ('Prangko','prangko','Koleksi prangko dan filateli.',5),
 ('Koleksi Umum','koleksi-umum','Koleksi museum yang belum dimasukkan ke kategori lain.',999)
ON DUPLICATE KEY UPDATE slug=VALUES(slug);

UPDATE koleksi
SET is_featured=1
WHERE is_published=1 AND NOT EXISTS (
 SELECT id FROM (SELECT id FROM koleksi WHERE is_published=1 AND is_featured=1) AS featured_items
)
ORDER BY sort_order ASC, id DESC
LIMIT 1;

INSERT INTO beranda (judul,deskripsi,gambar,sort_order)
SELECT 'Selamat Datang di Museum Bandar Cimanuk','Informasi dan sejarah Museum Bandar Cimanuk','museum.jpg',0
WHERE NOT EXISTS (SELECT 1 FROM beranda);
INSERT INTO jelajah (judul,deskripsi,gambar,sort_order)
SELECT 'Jelajah Museum','Informasi area dan pengalaman berkunjung','museum.jpg',0
WHERE NOT EXISTS (SELECT 1 FROM jelajah);
INSERT INTO koleksi (judul,kategori_id,deskripsi,gambar,sort_order,is_featured)
SELECT 'Koleksi Keramik Cimanuk',(SELECT id FROM kategori_koleksi WHERE slug='koleksi-umum' LIMIT 1),'Koleksi benda bersejarah Museum Bandar Cimanuk','keramik.jpg',0,1
WHERE NOT EXISTS (SELECT 1 FROM koleksi);
INSERT INTO galeri (judul,deskripsi,gambar,sort_order)
SELECT 'Galeri Kegiatan Museum','Dokumentasi kegiatan dan ruang museum','galeri.jpg',0
WHERE NOT EXISTS (SELECT 1 FROM galeri);
INSERT INTO artikel (judul,deskripsi,isi,gambar,tanggal)
SELECT 'Sejarah Bandar Cimanuk','Ringkasan sejarah Bandar Cimanuk','Tuliskan isi artikel sejarah di sini.','sungai.jpg',CURRENT_DATE
WHERE NOT EXISTS (SELECT 1 FROM artikel);
INSERT INTO kontak (judul,deskripsi,alamat,telepon,email,maps_url)
SELECT 'Kontak Museum','Informasi kunjungan Museum Bandar Cimanuk','Jl. Veteran No. 5 Lemahabang, Indramayu','0819-4694-0740','museum.bandarcimanuk@gmail.com','https://maps.google.com/?q=Jl.+Veteran+No.+5+Lemahabang+Indramayu'
WHERE NOT EXISTS (SELECT 1 FROM kontak);

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
