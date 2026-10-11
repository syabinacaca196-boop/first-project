<?php
require_once __DIR__ . '/koneksi.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>