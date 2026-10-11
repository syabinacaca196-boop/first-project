CREATE DATABASE IF NOT EXISTS museum_cimanuk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE museum_cimanuk;

CREATE TABLE IF NOT EXISTS admin (
 id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL
);

-- Password admin123 disimpan dalam bentuk hash PHP password_hash.
INSERT INTO admin (username,password)
SELECT 'admin', '$2y$12$xNYCe3TMQaEFt9nJ.JeNGeSNYvkqb2P5fq5OdWjRINUfvb1pdZp96'
WHERE NOT EXISTS (SELECT 1 FROM admin WHERE username='admin');

CREATE TABLE IF NOT EXISTS beranda (
 id INT AUTO_INCREMENT PRIMARY KEY, judul VARCHAR(200) NOT NULL, deskripsi TEXT,
 gambar VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS jelajah LIKE beranda;
CREATE TABLE IF NOT EXISTS koleksi LIKE beranda;
CREATE TABLE IF NOT EXISTS galeri LIKE beranda;
CREATE TABLE IF NOT EXISTS artikel (
 id INT AUTO_INCREMENT PRIMARY KEY, judul VARCHAR(200) NOT NULL, deskripsi TEXT, isi LONGTEXT,
 gambar VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS kontak (
 id INT AUTO_INCREMENT PRIMARY KEY, judul VARCHAR(200) NOT NULL, deskripsi TEXT, alamat TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO beranda (judul,deskripsi,gambar) SELECT 'Selamat Datang di Museum Bandar Cimanuk','Informasi dan sejarah Museum Bandar Cimanuk','museum.jpg' WHERE NOT EXISTS (SELECT 1 FROM beranda);
INSERT INTO jelajah (judul,deskripsi,gambar) SELECT 'Jelajah Museum','Informasi area dan pengalaman berkunjung','museum.jpg' WHERE NOT EXISTS (SELECT 1 FROM jelajah);
INSERT INTO koleksi (judul,deskripsi,gambar) SELECT 'Koleksi Keramik Cimanuk','Koleksi benda bersejarah Museum Bandar Cimanuk','keramik.jpg' WHERE NOT EXISTS (SELECT 1 FROM koleksi);
INSERT INTO galeri (judul,deskripsi,gambar) SELECT 'Galeri Kegiatan Museum','Dokumentasi kegiatan dan ruang museum','galeri.jpg' WHERE NOT EXISTS (SELECT 1 FROM galeri);
INSERT INTO artikel (judul,deskripsi,isi,gambar) SELECT 'Sejarah Bandar Cimanuk','Ringkasan sejarah Bandar Cimanuk','Tuliskan isi artikel sejarah di sini.','sungai.jpg' WHERE NOT EXISTS (SELECT 1 FROM artikel);
INSERT INTO kontak (judul,deskripsi,alamat) SELECT 'Kontak Museum','Telepon: (0234) 000000 | Email: info@museum.id','Alamat museum, Indramayu, Jawa Barat' WHERE NOT EXISTS (SELECT 1 FROM kontak);
