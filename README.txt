MUSEUM BANDAR CIMANUK - ADMIN CRUD
==================================
Kebutuhan: Laragon, Apache, MySQL, PHP 8+.

CARA PASANG
1. Ekstrak folder museum_bandar_cimanuk ke C:\laragon\www\ sehingga ada C:\laragon\www\museum_bandar_cimanuk\dashboard.php.
2. Buka Laragon, klik Start All.
3. Klik Database atau buka http://localhost/phpmyadmin.
4. Pilih tab Import, pilih file database.sql dari folder project, lalu klik Import/Go. Alternatif: buka tab SQL, salin seluruh isi database.sql, lalu jalankan.
5. Pastikan database museum_cimanuk dan tabel admin, beranda, jelajah, koleksi, galeri, artikel, kontak terbentuk.
6. Buka http://localhost/museum_bandar_cimanuk/login.php
7. Login demo: username admin, password admin123.
8. Dashboard lalu klik Beranda/Jelajah/Koleksi/Galeri/Artikel/Kontak. Setiap halaman memiliki tambah, edit, hapus.
9. Untuk keluar klik Logout.

CATATAN
- Koneksi default Laragon: user root, password kosong. Jika MySQL Anda memakai password, ubah koneksi.php.
- Field gambar pada versi ini menyimpan NAMA FILE saja, belum mengunggah file gambar. Taruh gambar website di folder assets/images jika nanti fitur upload ditambahkan.
- Tautan "Lihat Website" diarahkan ke ../index.php. Sesuaikan jika halaman publik Anda ada di lokasi lain.
- Ini kerangka CRUD admin dasar. Sebelum dipublikasikan, tambahkan CSRF protection, validasi lebih lengkap, pembatasan upload gambar, dan ganti password demo.
