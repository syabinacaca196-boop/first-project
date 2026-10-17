<?php
$table = 'artikel';
$label = 'Artikel';
$active_file = 'artikel.php';
$fields = [
	'judul' => ['label' => 'Judul artikel', 'type' => 'text', 'required' => true],
	'deskripsi' => ['label' => 'Ringkasan', 'type' => 'textarea', 'rows' => 3],
	'isi' => ['label' => 'Isi artikel', 'type' => 'textarea', 'rows' => 9],
	'gambar' => ['label' => 'Gambar utama', 'type' => 'image'],
	'tanggal' => ['label' => 'Tanggal publikasi', 'type' => 'date', 'required' => true],
	'penulis' => ['label' => 'Penulis', 'type' => 'text'],
	'sort_order' => ['label' => 'Urutan tampil', 'type' => 'number', 'min' => 0],
	'is_published' => ['label' => 'Publikasikan', 'type' => 'checkbox', 'default' => 1],
];
require 'crud_page.php';
?>