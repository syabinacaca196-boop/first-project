<?php
require_once __DIR__ . '/data.php';
$article = visitor_article((int) ($_GET['id'] ?? 0));
$activePage = 'artikel.php';

if (!$article) {
    http_response_code(404);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= visitor_escape($article['judul'] ?? 'Artikel tidak ditemukan') ?> | Museum Bandar Cimanuk</title>
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/dashboard.css">
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/visitor-content.css">
</head>
<body>
<div class="website-wrapper">
    <?php require __DIR__ . '/navbar.php'; ?>
    <main class="home-content article-list">
        <?php if (!$article): ?>
            <h1>Artikel tidak ditemukan</h1>
            <p>Artikel ini tidak tersedia atau belum dipublikasikan.</p>
        <?php else: ?>
            <article class="article-detail">
                <p><a href="artikel.php">Artikel</a></p>
                <h1><?= visitor_escape($article['judul']) ?></h1>
                <p class="article-meta">
                    <?= !empty($article['tanggal']) ? visitor_escape(date('d M Y', strtotime($article['tanggal']))) : '' ?>
                    <?= !empty($article['penulis']) ? ' · ' . visitor_escape($article['penulis']) : '' ?>
                </p>
                <?php if (visitor_image($article['gambar'] ?? '') !== ''): ?><img class="detail-image" src="<?= visitor_escape(visitor_image($article['gambar'])) ?>" alt="<?= visitor_escape($article['judul']) ?>"><?php endif; ?>
                <?php if (!empty($article['deskripsi'])): ?><p class="article-summary"><?= nl2br(visitor_escape($article['deskripsi'])) ?></p><?php endif; ?>
                <div class="article-body"><?= nl2br(visitor_escape($article['isi'])) ?></div>
            </article>
        <?php endif; ?>
    </main>
    <footer class="museum-footer"><div class="footer-content"><div class="footer-brand"><h3>MUSEUM BANDAR CIMANUK</h3><p>Indramayu, Tanah Sejarah, Lautan Cerita.</p></div><div class="footer-copy">© 2026 Museum Bandar Cimanuk</div></div></footer>
</div>
</body>
</html>