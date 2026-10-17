<?php
require_once __DIR__ . '/data.php';
$category = visitor_collection_category((int) ($_GET['id'] ?? 0));

if (!$category) {
    http_response_code(404);
    $categoryItems = [];
} else {
    $categoryItems = visitor_category_collections((int) $category['id']);
}
$activePage = 'koleksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= visitor_escape($category['nama'] ?? 'Kategori tidak ditemukan') ?> | Museum Bandar Cimanuk</title>
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/dashboard.css">
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/koleksi.css">
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/visitor-content.css">
</head>
<body>
<div class="website-wrapper">
    <?php require __DIR__ . '/navbar.php'; ?>
    <main class="home-content article-list">
        <div class="section-heading"><h2><?= visitor_escape($category['nama'] ?? 'Kategori tidak ditemukan') ?></h2></div>
        <?php if (!$category): ?>
            <p>Kategori ini tidak tersedia.</p>
        <?php else: ?>
            <p><?= nl2br(visitor_escape($category['deskripsi'] ?? '')) ?></p>
            <div class="all-collection-grid">
                <?php foreach ($categoryItems as $item): ?>
                    <article class="museum-collection-card">
                        <a href="koleksi-detail.php?id=<?= (int) $item['id'] ?>" class="museum-collection-image">
                            <img src="<?= visitor_escape(visitor_image($item['gambar'] ?? '') ?: 'assets/images/museum-illustration.png') ?>" alt="<?= visitor_escape($item['judul']) ?>">
                        </a>
                        <div class="museum-collection-info">
                            <h3><a href="koleksi-detail.php?id=<?= (int) $item['id'] ?>"><?= visitor_escape($item['judul']) ?></a></h3>
                            <p><?= nl2br(visitor_escape($item['deskripsi'])) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <?php if ($categoryItems === []): ?><p>Belum ada koleksi yang dipublikasikan dalam kategori ini.</p><?php endif; ?>
        <?php endif; ?>
    </main>
    <footer class="museum-footer"><div class="footer-content"><div class="footer-brand"><h3>MUSEUM BANDAR CIMANUK</h3><p>Indramayu, Tanah Sejarah, Lautan Cerita.</p></div><div class="footer-copy">© 2026 Museum Bandar Cimanuk</div></div></footer>
</div>
</body>
</html>