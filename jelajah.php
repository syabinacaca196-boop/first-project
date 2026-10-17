<?php
$table = 'jelajah';
$label = 'Section Jelajah';
$active_file = 'jelajah.php';
$fields = [
	'judul' => ['label' => 'Judul section', 'type' => 'text', 'required' => true],
	'deskripsi' => ['label' => 'Isi section', 'type' => 'textarea', 'rows' => 6],
	'gambar' => ['label' => 'Foto dokumentasi', 'type' => 'image'],
	'sort_order' => ['label' => 'Urutan tampil', 'type' => 'number', 'min' => 0],
	'is_published' => ['label' => 'Tampilkan', 'type' => 'checkbox', 'default' => 1],
];
require 'crud_page.php';
?>