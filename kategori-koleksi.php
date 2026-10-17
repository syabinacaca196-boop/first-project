<?php
$table = 'kategori_koleksi';
$label = 'Kategori Koleksi';
$active_file = 'kategori-koleksi.php';
$fields = [
    'nama' => ['label' => 'Nama kategori', 'type' => 'text', 'required' => true],
    'slug' => ['label' => 'URL slug', 'type' => 'text', 'required' => true],
    'deskripsi' => ['label' => 'Deskripsi kategori', 'type' => 'textarea', 'rows' => 4],
    'gambar' => ['label' => 'Gambar kategori', 'type' => 'image'],
    'sort_order' => ['label' => 'Urutan tampil', 'type' => 'number', 'min' => 0],
    'is_published' => ['label' => 'Tampilkan', 'type' => 'checkbox', 'default' => 1],
];
require 'crud_page.php';
?>