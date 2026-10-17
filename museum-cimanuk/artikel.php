<?php
require_once __DIR__ . '/data.php';
$articleItems = visitor_content('artikel');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel | Museum Bandar Cimanuk</title>
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/dashboard.css">
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/visitor-content.css">
</head>
<body>
<div class="website-wrapper">
    <?php $activePage = 'artikel.php'; require __DIR__ . '/navbar.php'; ?>
    <main class="home-content article-list">
        <div class="section-heading"><h2>Artikel Museum</h2></div>
        <div class="article-card-grid">
            <?php foreach ($articleItems as $item): ?>
                <article class="article-card">
                    <a class="article-link" href="artikel-detail.php?id=<?= (int) $item['id'] ?>">
                        <img src="<?= visitor_escape(visitor_image($item['gambar'] ?? '') ?: 'assets/images/museum-illustration.png') ?>" alt="<?= visitor_escape($item['judul']) ?>">
                        <div class="article-card-content">
                            <h2><?= visitor_escape($item['judul']) ?></h2>
                            <p class="article-summary"><?= visitor_escape($item['deskripsi']) ?></p>
                            <small>
                                <?= !empty($item['tanggal']) ? visitor_escape(date('d M Y', strtotime($item['tanggal']))) : '' ?>
                                <?= !empty($item['penulis']) ? ' · ' . visitor_escape($item['penulis']) : '' ?>
                            </small>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </main>
    <footer class="museum-footer">
        <div class="footer-content">
            <div class="footer-brand"><h3>MUSEUM BANDAR CIMANUK</h3><p>Indramayu, Tanah Sejarah, Lautan Cerita.</p></div>
            <div class="footer-copy">© 2026 Museum Bandar Cimanuk</div>
        </div>
    </footer>
</div>
</body>
</html>