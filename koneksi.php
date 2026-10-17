<?php
// Koneksi database Museum Bandar Cimanuk
$host = "localhost";
$user = "root";
$pass = "";
$db   = "museum-cimanuk";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
if (session_status() === PHP_SESSION_NONE) session_start();
?>