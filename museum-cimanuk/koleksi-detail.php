<?php
require_once __DIR__ . '/data.php';
$item = visitor_collection((int) ($_GET['id'] ?? 0));
$category = $item ? visitor_collection_category((int) $item['kategori_id']) : null;
$activePage = 'koleksi.php';

if (!$item) {
    http_response_code(404);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= visitor_escape($item['judul'] ?? 'Koleksi tidak ditemukan') ?> | Museum Bandar Cimanuk</title>
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/dashboard.css">
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/visitor-content.css">
</head>
<body>
<div class="website-wrapper">
    <?php require __DIR__ . '/navbar.php'; ?>
    <main class="home-content article-list">
        <?php if (!$item): ?>
            <h1>Koleksi tidak ditemukan</h1>
            <p>Koleksi ini tidak tersedia atau belum dipublikasikan.</p>
        <?php else: ?>
            <p><a href="koleksi.php">Koleksi</a> / <?= visitor_escape($category['nama'] ?? 'Koleksi Umum') ?></p>
            <?php if (visitor_image($item['gambar'] ?? '') !== ''): ?><img class="detail-image" src="<?= visitor_escape(visitor_image($item['gambar'])) ?>" alt="<?= visitor_escape($item['judul']) ?>"><?php endif; ?>
            <h1><?= visitor_escape($item['judul']) ?></h1>
            <?php if (!empty($item['deskripsi'])): ?><p><?= nl2br(visitor_escape($item['deskripsi'])) ?></p><?php endif; ?>
            <?php if (!empty($item['sejarah'])): ?><section class="detail-section"><h2>Informasi Sejarah</h2><p><?= nl2br(visitor_escape($item['sejarah'])) ?></p></section><?php endif; ?>
            <?php if (!empty($item['filosofi'])): ?><section class="detail-section"><h2>Filosofi dan Makna</h2><p><?= nl2br(visitor_escape($item['filosofi'])) ?></p></section><?php endif; ?>
        <?php endif; ?>
    </main>
    <footer class="museum-footer"><div class="footer-content"><div class="footer-brand"><h3>MUSEUM BANDAR CIMANUK</h3><p>Indramayu, Tanah Sejarah, Lautan Cerita.</p></div><div class="footer-copy">© 2026 Museum Bandar Cimanuk</div></div></footer>
</div>
</body>
</html>