<?php
require_once __DIR__ . '/data.php';
$query = trim((string) ($_GET['q'] ?? ''));
$results = $query === '' ? [] : visitor_search($query);
$activePage = '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $query === '' ? 'Pencarian' : 'Hasil pencarian: ' . visitor_escape($query) ?> | Museum Bandar Cimanuk</title>
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/dashboard.css">
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/visitor-content.css">
</head>
<body>
<div class="website-wrapper">
    <?php require __DIR__ . '/navbar.php'; ?>
    <main class="home-content article-list">
        <div class="section-heading"><h2>Hasil pencarian</h2></div>
        <?php if ($query === ''): ?>
            <p>Masukkan kata kunci koleksi atau artikel.</p>
        <?php elseif ($results === []): ?>
            <p>Tidak ada hasil untuk “<?= visitor_escape($query) ?>”.</p>
        <?php else: ?>
            <p class="search-count"><?= count($results) ?> hasil untuk “<?= visitor_escape($query) ?>”</p>
            <div class="search-list">
                <?php foreach ($results as $item): ?>
                    <a class="search-result-card" href="<?= visitor_escape($item['url']) ?>">
                        <?php if (visitor_image($item['gambar'] ?? '') !== ''): ?>
                            <img src="<?= visitor_escape(visitor_image($item['gambar'])) ?>" alt="<?= visitor_escape($item['judul']) ?>">
                        <?php endif; ?>
                        <span class="search-result-type"><?= visitor_escape($item['type']) ?></span>
                        <h3><?= visitor_escape($item['judul']) ?></h3>
                        <p><?= visitor_escape($item['deskripsi']) ?></p>
                        <?php if (!empty($item['tanggal'])): ?><small><?= visitor_escape($item['tanggal']) ?></small><?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
    <footer class="museum-footer"><div class="footer-content"><div class="footer-brand"><h3>MUSEUM BANDAR CIMANUK</h3><p>Indramayu, Tanah Sejarah, Lautan Cerita.</p></div><div class="footer-copy">© 2026 Museum Bandar Cimanuk</div></div></footer>
</div>
</body>
</html>
