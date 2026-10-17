<?php
require_once __DIR__ . '/data.php';
$exploreItems = visitor_content('jelajah');
$exploreIntro = $exploreItems[0] ?? [];
$contactContent = visitor_content('kontak')[0] ?? [];
$whatsappNumber = preg_replace('/\D/', '', $contactContent['telepon'] ?? '');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Jelajah | Museum Bandar Cimanuk</title>

    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/dashboard.css">
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/jelajah.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >
</head>

<body>

<div class="website-wrapper">

    <?php $activePage = 'jelajah.php'; require __DIR__ . '/navbar.php'; ?>


    <!-- HERO -->
    <section class="explore-hero">

        <div class="explore-text">

            <div class="breadcrumb">
                BERANDA
                <span>›</span>
                JELAJAH
            </div>

            <span class="explore-label">
                JELAJAH MUSEUM
            </span>

            <h1>
                <?= visitor_escape($exploreIntro['judul'] ?? 'Jelajah Museum Bandar Cimanuk') ?>
            </h1>

            <p>
                <?= nl2br(visitor_escape($exploreIntro['deskripsi'] ?? 'Temukan cerita, sejarah, tokoh, dan jejak budaya yang membentuk Museum Bandar Cimanuk.')) ?>
            </p>

        </div>

        <div class="explore-art">
            <img
                src="assets/images/museum-illustration.png"
                alt="Ilustrasi Museum Bandar Cimanuk"
            >
        </div>

    </section>


    <!-- PILIHAN JELAJAH -->
    <main class="explore-page">

        <div class="explore-heading">
            <span>SEJARAH DAN INFORMASI</span>
            <h2>Mengenal Museum Bandar Cimanuk</h2>
        </div>

        <div class="explore-sections">
            <?php foreach ($exploreItems as $item): ?>
                <section class="explore-section">
                    <?php if (visitor_image($item['gambar'] ?? '') !== ''): ?>
                        <img src="<?= visitor_escape(visitor_image($item['gambar'])) ?>" alt="<?= visitor_escape($item['judul']) ?>">
                    <?php endif; ?>
                    <div><h3><?= visitor_escape($item['judul']) ?></h3><p><?= nl2br(visitor_escape($item['deskripsi'])) ?></p></div>
                </section>
            <?php endforeach; ?>
        </div>

    </main>


    <!-- FOOTER -->
    <footer class="museum-footer" id="kontak">

        <div class="footer-content">

            <div class="footer-brand">
                <h3>MUSEUM BANDAR CIMANUK</h3>
                <p>
                    Indramayu, Tanah Sejarah, Lautan Cerita.
                </p>
            </div>

            <div class="footer-social">

                <a
                    href="https://www.instagram.com/museum_mbc"
                    target="_blank"
                    rel="noopener noreferrer"
                    title="Instagram"
                >
                    <i class="fa-brands fa-instagram"></i>
                </a>

                <a
                    href="https://www.facebook.com/MBCmuseum"
                    target="_blank"
                    rel="noopener noreferrer"
                    title="Facebook"
                >
                    <i class="fa-brands fa-facebook-f"></i>
                </a>

                <a
                    href="https://wa.me/<?= visitor_escape($whatsappNumber) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    title="WhatsApp"
                >
                    <i class="fa-brands fa-whatsapp"></i>
                </a>

                <a
                    href="mailto:<?= visitor_escape($contactContent['email'] ?? '') ?>"
                    title="Email"
                >
                    <i class="fa-regular fa-envelope"></i>
                </a>

            </div>

            <div class="footer-copy">
                © 2026 Museum Bandar Cimanuk
            </div>

        </div>

    </footer>

</div>

</body>
</html>