<?php
$activePage = $activePage ?? '';
$navigation = [
    'dashboard.php' => 'Beranda',
    'jelajah.php' => 'Jelajah',
    'koleksi.php' => 'Koleksi',
    'galeri.php' => 'Galeri',
    'artikel.php' => 'Artikel',
    'kontak.php' => 'Kontak',
];
?>
<nav class="navbar">
    <a href="dashboard.php" class="nav-brand">
        <img src="assets/images/logo-mbcberanda.jpg" class="nav-logo" alt="Logo Museum Bandar Cimanuk">
        <div class="brand-text"><small>MUSEUM</small><strong>BANDAR CIMANUK</strong></div>
    </a>
    <button class="nav-toggle" type="button" aria-controls="visitor-nav-menu" aria-expanded="false">Menu</button>
    <div class="nav-menu" id="visitor-nav-menu">
        <?php foreach ($navigation as $url => $label): ?>
            <a href="<?= visitor_escape($url) ?>" class="<?= $activePage === $url ? 'active' : '' ?>"><?= visitor_escape($label) ?></a>
        <?php endforeach; ?>
    </div>
    <form class="nav-search" action="cari.php" method="get" role="search">
        <input type="search" name="q" value="<?= visitor_escape($_GET['q'] ?? '') ?>" placeholder="Cari koleksi atau artikel" aria-label="Cari koleksi atau artikel" required>
        <button type="submit">Cari</button>
    </form>
</nav>
<script>
document.querySelector('.nav-toggle')?.addEventListener('click', function () {
    const menu = document.getElementById('visitor-nav-menu');
    const expanded = this.getAttribute('aria-expanded') === 'true';
    this.setAttribute('aria-expanded', String(!expanded));
    menu.classList.toggle('is-open', !expanded);
});
</script>