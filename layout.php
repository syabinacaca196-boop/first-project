<?php

// Fungsi helper untuk mencegah serangan XSS
function e($value) { 
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); 
}

// Fungsi untuk merendering komponen Header & Sidebar Utama Dashboard
function render_header($active, $title, $subtitle = 'Kelola informasi seputar Museum Bandar Cimanuk.') {
    // Array menu navigasi sidebar [URL => [Ikon, Nama Menu]]
    $menu = [
        'dashboard.php' => ['▦', 'Dashboard'],
        'beranda.php'   => ['⌂', 'Beranda'],
        'jelajah.php'   => ['◉', 'Jelajah'],
        'koleksi.php'   => ['⬡', 'Koleksi'],
        'kategori-koleksi.php' => ['▤', 'Kategori Koleksi'],
        'jam-operasional.php' => ['◷', 'Jam Operasional'],
        'galeri.php'    => ['▧', 'Galeri'],
        'artikel.php'   => ['▤', 'Artikel'],
        'kontak.php'    => ['☎', 'Kontak'],
        'pesan-admin.php' => ['✉', 'Pesan Pengunjung'],
    ];

    $user = $_SESSION['admin_username'] ?? 'Admin';
    ?>
    <!doctype html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= e($title) ?> | Museum Bandar Cimanuk</title>
        <link rel="stylesheet" href="assets/style.css">
    </head>
    <body>

           <!-- ===== SIDEBAR NAVIGATION ===== -->
        <aside class="sidebar">
            <a class="brand" href="dashboard.php">
                <img
                    src="assets/uploads/logo2.png"
                    alt="Logo Museum Bandar Cimanuk"
                    class="brand-logo-img">
    
                <span class="brand-text">
                 Museum<br>Bandar Cimanuk
                </span>
            </a>
            
            <nav>
                <?php foreach ($menu as $url => $item): ?>
                    <?php $isActive = ($active === $url) ? 'active' : ''; ?>
                    <a class="nav-link <?= $isActive ?>" href="<?= e($url) ?>">
                        <span class="nav-icon"><?= $item[0] ?></span>
                        <?= $item[1] ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="sidebar-bottom">
                <a class="nav-link" href="pengaturan.php">⚙️ &nbsp; Pengaturan</a>
                <a class="nav-link" href="logout.php">⇥ &nbsp; Logout</a>
            </div>
        </aside>

        <!-- ===== KONTEN UTAMA ===== -->
        <main class="main">
            
            <!-- Topbar / Header Atas -->
            <header class="topbar">
                <div>
                    <div>Selamat datang, <b><?= e($user) ?>!</b></div>
                    <small><?= e($subtitle) ?></small>
                </div>
                <div class="top-actions">
                    <a href="../museum-cimanuk/" target="_blank">↗ Lihat Website</a>
                    <div class="avatar">👤</div>
                    <div>
                        <?= e($user) ?>
                        <small>Administrator</small>
                    </div>
                </div>
            </header>

            <!-- Wrapper Konten Halaman -->
            <section class="content">
                <div class="page-heading">
                    <div>
                        <h1><?= e($title) ?></h1>
                        <p>Ringkasan data dan pengelolaan konten Museum Bandar Cimanuk</p>
                    </div>
                </div>
    <?php
}

// Fungsi untuk merendering komponen Footer & Penutup Tag HTML
function render_footer() {
    ?>
            </section>

            <!-- ===== FOOTER ===== -->
            <footer class="footer">
                © 2026 Museum Bandar Cimanuk. Tempat untuk menyimpan benda sejarah. 
                <span>Sistem Museum Bandar Cimanuk</span>
            </footer>

        </main>
    </body>
    </html>
    <?php
}
?>
