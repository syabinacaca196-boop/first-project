<?php
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function render_header($active, $title, $subtitle='Kelola konten website Museum Bandar Cimanuk dengan mudah.') {
    $menu = [
      'dashboard.php'=>['▦','Dashboard'],
      'beranda.php'=>['⌂','Beranda'],
      'jelajah.php'=>['◉','Jelajah'],
      'koleksi.php'=>['⬡','Koleksi'],
      'galeri.php'=>['▧','Galeri'],
      'artikel.php'=>['▤','Artikel'],
      'kontak.php'=>['☎','Kontak'],
    ];
    $user = $_SESSION['admin_username'] ?? 'Admin';
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.e($title).' | Museum Bandar Cimanuk</title><link rel="stylesheet" href="assets/style.css"></head><body>';
    echo '<aside class="sidebar"><a class="brand" href="dashboard.php"><span class="brand-icon">🏛</span><span>Museum<br>Bandar Cimanuk</span></a><nav>';
    foreach($menu as $url=>$item) echo '<a class="nav-link '.($active===$url?'active':'').'" href="'.e($url).'"><span class="nav-icon">'.$item[0].'</span>'.$item[1].'</a>';
    echo '</nav><div class="sidebar-bottom"><a class="nav-link" href="pengaturan.php">⚙️ &nbsp; Pengaturan</a><a class="nav-link" href="logout.php">⇥ &nbsp; Logout</a></div></aside>';
    echo '<main class="main"><header class="topbar"><div><div>Selamat datang, <b>'.e($user).'!</b></div><small>'.e($subtitle).'</small></div><div class="top-actions"><a href="../index.php" target="_blank">↗ Lihat Website</a><div class="avatar">👤</div><div>'.e($user).'<small>Administrator</small></div></div></header><section class="content"><div class="page-heading"><div><h1>'.e($title).'</h1><p>Ringkasan data dan pengelolaan konten Museum Bandar Cimanuk</p></div></div>';
}
function render_footer() {
    echo '</section><footer class="footer">© 2025 Museum Bandar Cimanuk. All rights reserved. <span>Sistem Informasi Museum Bandar Cimanuk</span></footer></main></body></html>';
}
?>