<?php
session_start();
include 'koneksi.php';

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($nama === "" || $username === "" || $email === "" || $password === "") {
        $message = "Semua data wajib diisi.";
        $messageType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format email tidak valid.";
        $messageType = "error";
    } elseif (strlen($password) < 6) {
        $message = "Password minimal 6 karakter.";
        $messageType = "error";
    } elseif ($password !== $confirmPassword) {
        $message = "Konfirmasi password tidak sama.";
        $messageType = "error";
    } else {
        $cek = mysqli_prepare($conn, "SELECT id FROM admins WHERE username = ? OR email = ?");
        mysqli_stmt_bind_param($cek, "ss", $username, $email);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);

        if (mysqli_stmt_num_rows($cek) > 0) {
            $message = "Username atau email sudah digunakan.";
            $messageType = "error";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($conn, "INSERT INTO admins (nama, username, email, password) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssss", $nama, $username, $email, $hashedPassword);

            if (mysqli_stmt_execute($stmt)) {
                $message = "Akun admin berhasil dibuat. Silakan masuk.";
                $messageType = "success";
            } else {
                $message = "Terjadi kesalahan saat membuat akun.";
                $messageType = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Admin | Museum Bandar Cimanuk</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-page">

    <section class="auth-image">
        <div class="auth-image-content">
            <div>
                <div class="museum-small">Museum Indramayu</div>
                <div class="museum-title">Museum<br>Bandar Cimanuk</div>
                <div class="museum-subtitle">
                    Menelusuri jejak sejarah<br>
                    dan budaya Indramayu.
                </div>
            </div>

            <div class="quote">
                “Sejarah bukan hanya untuk dikenang, tetapi untuk dipelajari dan diwariskan.”
                <span>Indramayu · Tanah Sejarah · Lautan Cerita</span>
            </div>
        </div>
    </section>

    <main class="auth-panel">
        <div class="auth-card">

            <div class="logo-wrap">
                <img src="assets/images/logo-museum.jpg" alt="Logo Museum Bandar Cimanuk">
            </div>

            <div class="auth-heading">
                <h1>Buat Akun Admin</h1>
                <p>Daftarkan akun untuk mengelola informasi Museum Bandar Cimanuk.</p>
            </div>

            <div class="auth-tabs">
                <a href="login.php">Masuk</a>
                <a href="register.php" class="active">Daftar</a>
            </div>

            <?php if ($message !== ""): ?>
                <div class="alert <?= $messageType === 'success' ? 'alert-success' : 'alert-error'; ?>">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <div class="input-wrap">
                        <span class="input-icon">♙</span>
                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            placeholder="Masukkan nama lengkap"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <div class="input-wrap">
                        <span class="input-icon">@</span>
                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            placeholder="Buat username"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrap">
                        <span class="input-icon">✉</span>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Masukkan email"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Minimal 6 karakter"
                            required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password', this)">◉</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input
                            type="password"
                            name="confirm_password"
                            id="confirm_password"
                            class="form-control"
                            placeholder="Ulangi password"
                            required>
                        <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', this)">◉</button>
                    </div>
                </div>

                <button type="submit" class="btn-main">Daftar sebagai Admin</button>
            </form>

            <div class="access-card">
                <div class="access-icon">◇</div>
                <div>
                    <strong>Akses Terbatas</strong>
                    <small>Halaman ini hanya untuk administrator Museum Bandar Cimanuk.</small>
                </div>
            </div>

            <div class="auth-footer">
                Sudah memiliki akun? <a href="login.php">Masuk sekarang</a>
            </div>

        </div>
    </main>
</div>

<script>
function togglePassword(id, button) {
    const input = document.getElementById(id);

    if (input.type === "password") {
        input.type = "text";
        button.textContent = "◎";
    } else {
        input.type = "password";
        button.textContent = "◉";
    }
}
</script>

</body>
</html>