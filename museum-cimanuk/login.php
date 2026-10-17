<?php
session_start();
include 'koneksi.php';

$message = "";

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identity = trim($_POST['identity']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, nama, username, email, password
         FROM admins
         WHERE username = ? OR email = ?
         LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, "ss", $identity, $identity);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nama'] = $admin['nama'];
        $_SESSION['admin_username'] = $admin['username'];

        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Email, username, atau password tidak sesuai.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin | Museum Bandar Cimanuk</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-page">

    <section class="auth-image">
        <div class="auth-image-content">
            <div>
                <div class="museum-small">Museum Indramayu</div>

                <div class="museum-title">
                    Museum<br>
                    Bandar Cimanuk
                </div>

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
                <h1>Masuk ke Akun Admin</h1>
                <p>Kelola informasi, koleksi, dan cerita Museum Bandar Cimanuk.</p>
            </div>

            <div class="auth-tabs">
                <a href="login.php" class="active">Masuk</a>
                <a href="register.php">Daftar</a>
            </div>

            <?php if ($message !== ""): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>Email / Username</label>
                    <div class="input-wrap">
                        <span class="input-icon">♙</span>

                        <input
                            type="text"
                            name="identity"
                            class="form-control"
                            placeholder="Masukkan email atau username"
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
                            placeholder="Masukkan password"
                            required>

                        <button
                            type="button"
                            class="toggle-password"
                            onclick="togglePassword()">
                            ◉
                        </button>
                    </div>
                </div>

                <div class="form-row">

                    <label class="remember">
                        <input type="checkbox" name="remember">
                        Ingatkan saya
                    </label>

                    <a href="#">Lupa password?</a>

                </div>

                <button type="submit" class="btn-main">
                    Masuk
                </button>

            </form>

            <div class="access-card">
                <div class="access-icon">◇</div>

                <div>
                    <strong>Akses Terbatas</strong>
                    <small>
                        Halaman ini hanya untuk administrator
                        Museum Bandar Cimanuk.
                    </small>
                </div>
            </div>

            <div class="auth-footer">
                Belum punya akun?
                <a href="register.php">Buat akun admin</a>
            </div>

        </div>

    </main>

</div>

<script>
function togglePassword() {
    const input = document.getElementById("password");
    const button = document.querySelector(".toggle-password");

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