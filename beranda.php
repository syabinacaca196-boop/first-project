<?php
$table = 'beranda';
$label = 'Beranda';
$active_file = 'beranda.php';
$fields = [
	'judul' => ['label' => 'Judul halaman', 'type' => 'text', 'required' => true],
	'deskripsi' => ['label' => 'Deskripsi', 'type' => 'textarea', 'rows' => 4],
	'gambar' => ['label' => 'Foto utama', 'type' => 'image'],
	'image_position' => ['label' => 'Posisi foto', 'type' => 'select', 'options' => ['center' => 'Tengah', 'top' => 'Atas', 'bottom' => 'Bawah', 'left' => 'Kiri', 'right' => 'Kanan']],
	'sort_order' => ['label' => 'Urutan tampil', 'type' => 'number', 'min' => 0],
	'is_published' => ['label' => 'Tampilkan', 'type' => 'checkbox', 'default' => 1],
];
require 'crud_page.php';
?>