CREATE DATABASE IF NOT EXISTS mbc;

USE mbc;


-- ==========================================
-- TABEL BERANDA
-- ==========================================

CREATE TABLE konten_beranda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT NOT NULL,
    gambar VARCHAR(255),
    jam_operasional VARCHAR(100),
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ==========================================
-- TABEL JELAJAH
-- ==========================================

CREATE TABLE jelajah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT NOT NULL,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ==========================================
-- TABEL KOLEKSI
-- ==========================================

CREATE TABLE koleksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_koleksi VARCHAR(150) NOT NULL,
    deskripsi TEXT NOT NULL,
    gambar VARCHAR(255),
    tahun VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ==========================================
-- TABEL GALERI
-- ==========================================

CREATE TABLE galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ==========================================
-- TABEL ARTIKEL
-- ==========================================

CREATE TABLE artikel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    isi TEXT NOT NULL,
    gambar VARCHAR(255),
    tanggal DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ==========================================
-- TABEL KONTAK
-- ==========================================

CREATE TABLE kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alamat TEXT,
    telepon VARCHAR(30),
    email VARCHAR(100),
    instagram VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);