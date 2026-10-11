<?php
require_once 'koneksi.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Pastikan koneksi dan session start aman
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: dashboard.php'); 
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - Museum Bandar Cimanuk</title>
    <!-- Hubungkan ke CSS yang sudah diperbaiki -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-split-body">

    <div class="login-container">
        
        <!-- SISI KIRI: Banner Gambar dan Tipografi Promosi Estetis -->
        <div class="login-left">
            <div class="overlay-content">
                <div class="meta-tag">MUSEUM INDRAMAYU</div>
                <h2 class="hero-title">MUSEUM<br>BANDAR CIMANUK</h2>
                <p class="hero-desc">MENELUSURI JEJAK SEJARAH DAN BUDAYA INDRAMAYU</p>
                
                <div class="quote-wrapper">
                    <p class="quote-text">"Sejarah bukan hanya untuk dikenang, tetapi untuk dipelajari dan diwariskan."</p>
                    <div class="quote-footer">INDRAMAYU<br>TANAH SEJARAH,<br>LAUTAN CERITA.</div>
                </div>
            </div>
        </div>
        
        <!-- SISI KANAN: Form Autentikasi Pengguna & Kredensial -->
        <div class="login-right">
            <div class="form-wrapper">
                
                <!-- Logo Identitas Instansi Resmi Museum -->
                <div class="logo-box">
                    <img src="assets/img/logo-mbc.png" alt="Logo Museum Bandar Cimanuk" class="museum-logo-img">
                </div>
                
                <h1 class="form-title">Masuk ke Akun Admin</h1>
                <p class="form-subtitle">Kelola informasi, koleksi, dan cerita Museum Bandar Cimanuk.</p>
                
                <!-- Manajemen Alert Pesan Kesalahan Validasi -->
                <?php if ($error): ?>
                    <div class="alert error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="post" class="crud-form">
                    <div class="input-group">
                        <label for="username">Email / Username</label>
                        <input type="text" id="username" name="username" required autocomplete="username" placeholder="Masukkan email atau username">
                    </div>
                    
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Masukkan password">
                    </div>
                    
                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember"> Ingatkan saya
                        </label>
                        <a href="#" class="forgot-pass">Lupa password?</a>
                    </div>
                    
                    <button type="submit" class="btn primary full btn-login-submit">masuk</button>
                </form>
                
                <div class="restriction-notice">
                    <span class="lock-icon">🔒</span>
                    <div class="notice-text">
                        <strong>Akses Terbatas</strong>
                        <p>Halaman ini hanya untuk administrator Museum Bandar Cimanuk.</p>
                    </div>
                </div>
                
                <div class="demo-credential-hint">
                    <small>Akun demo awal dev: <b>admin</b> / <b>admin123</b></small>
                </div>
                
            </div>
        </div>
        
    </div>

</body>
</html>
