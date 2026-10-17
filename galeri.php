<?php
$table = 'galeri';
$label = 'Galeri Museum';
$active_file = 'galeri.php';
$fields = [
	'judul' => ['label' => 'Judul foto', 'type' => 'text', 'required' => true],
	'deskripsi' => ['label' => 'Keterangan foto', 'type' => 'textarea', 'rows' => 3],
	'gambar' => ['label' => 'Foto', 'type' => 'image'],
	'image_position' => ['label' => 'Posisi foto', 'type' => 'select', 'options' => ['center' => 'Tengah', 'top' => 'Atas', 'bottom' => 'Bawah', 'left' => 'Kiri', 'right' => 'Kanan']],
	'sort_order' => ['label' => 'Urutan tampil', 'type' => 'number', 'min' => 0],
	'is_published' => ['label' => 'Tampilkan', 'type' => 'checkbox', 'default' => 1],
];
require 'crud_page.php';
?>