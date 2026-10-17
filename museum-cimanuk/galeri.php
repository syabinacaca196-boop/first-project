<?php
require_once __DIR__ . '/data.php';
$galleryItems = visitor_content('galeri');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri | Museum Bandar Cimanuk</title>
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/dashboard.css">
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/visitor-content.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<div class="website-wrapper">
    <?php $activePage = 'galeri.php'; require __DIR__ . '/navbar.php'; ?>
    <main class="home-content">
        <div class="section-heading"><h2>Galeri Museum</h2></div>
        <div class="gallery-grid">
            <?php foreach ($galleryItems as $item): ?>
                <?php $imagePosition = in_array($item['image_position'] ?? 'center', ['center', 'top', 'bottom', 'left', 'right'], true) ? $item['image_position'] : 'center'; ?>
                <figure class="gallery-item">
                    <img
                        src="<?= visitor_escape(visitor_image($item['gambar'] ?? '') ?: 'assets/images/museum-illustration.png') ?>"
                        alt="<?= visitor_escape($item['judul']) ?>"
                        style="object-position: <?= visitor_escape($imagePosition) ?>"
                    >
                    <figcaption><?= visitor_escape($item['judul']) ?></figcaption>
                </figure>
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