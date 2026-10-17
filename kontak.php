<?php
$table = 'kontak';
$label = 'Informasi Kontak';
$active_file = 'kontak.php';
$fields = [
	'judul' => ['label' => 'Nama kontak', 'type' => 'text', 'required' => true],
	'deskripsi' => ['label' => 'Informasi tambahan', 'type' => 'textarea', 'rows' => 3],
	'telepon' => ['label' => 'Nomor telepon', 'type' => 'text'],
	'email' => ['label' => 'Alamat email', 'type' => 'email'],
	'alamat' => ['label' => 'Alamat lengkap', 'type' => 'textarea', 'rows' => 3],
	'maps_url' => ['label' => 'Tautan Google Maps', 'type' => 'url'],
	'sort_order' => ['label' => 'Urutan tampil', 'type' => 'number', 'min' => 0],
	'is_published' => ['label' => 'Tampilkan', 'type' => 'checkbox', 'default' => 1],
];
require 'crud_page.php';
?>