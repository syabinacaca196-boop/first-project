<?php
$table = 'jam_operasional';
$label = 'Jam Operasional';
$active_file = 'jam-operasional.php';
$fields = [
    'hari' => ['label' => 'Hari', 'type' => 'select', 'options' => ['Senin' => 'Senin', 'Selasa' => 'Selasa', 'Rabu' => 'Rabu', 'Kamis' => 'Kamis', 'Jumat' => 'Jumat', 'Sabtu' => 'Sabtu', 'Minggu' => 'Minggu'], 'required' => true],
    'jam_buka' => ['label' => 'Jam buka', 'type' => 'time'],
    'jam_tutup' => ['label' => 'Jam tutup', 'type' => 'time'],
    'tutup' => ['label' => 'Status', 'type' => 'checkbox', 'checked_label' => 'Museum tutup'],
    'sort_order' => ['label' => 'Urutan hari', 'type' => 'number', 'min' => 0, 'default' => 0],
];
require 'crud_page.php';
?>