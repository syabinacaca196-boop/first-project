<?php
require_once 'auth.php';
$categoryOptions = [];
$categoryResult = $conn->query('SELECT id, nama FROM kategori_koleksi ORDER BY sort_order ASC, nama ASC');
while ($category = $categoryResult->fetch_assoc()) {
	$categoryOptions[(string) $category['id']] = $category['nama'];
}

$table = 'koleksi';
$label = 'Koleksi Museum';
$active_file = 'koleksi.php';
$fields = [
	'judul' => ['label' => 'Nama koleksi', 'type' => 'text', 'required' => true],
	'kategori_id' => ['label' => 'Kategori', 'type' => 'select', 'options' => $categoryOptions, 'required' => true, 'value_type' => 'int'],
	'deskripsi' => ['label' => 'Deskripsi', 'type' => 'textarea', 'rows' => 4],
	'sejarah' => ['label' => 'Informasi sejarah', 'type' => 'textarea', 'rows' => 5],
	'filosofi' => ['label' => 'Filosofi atau makna', 'type' => 'textarea', 'rows' => 4],
	'gambar' => ['label' => 'Foto koleksi', 'type' => 'image'],
	'sort_order' => ['label' => 'Urutan tampil', 'type' => 'number', 'min' => 0],
	'is_featured' => ['label' => 'Koleksi unggulan beranda', 'type' => 'checkbox'],
	'is_published' => ['label' => 'Tampilkan', 'type' => 'checkbox', 'default' => 1],
];
require 'crud_page.php';
?>