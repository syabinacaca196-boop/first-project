<?php
require_once __DIR__ . '/data.php';
$homeItems = visitor_content('beranda');
$homeContent = $homeItems[0] ?? [];
$featuredCollections = visitor_featured_collections();
$contactContent = visitor_content('kontak')[0] ?? [];
$whatsappNumber = preg_replace('/\D/', '', $contactContent['telepon'] ?? '');
$operatingHours = visitor_content('jam_operasional');
$mapUrl = $contactContent['maps_url'] ?? '';
if (!preg_match('~^https?://~i', $mapUrl)) {
    $mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($contactContent['alamat'] ?? 'Museum Bandar Cimanuk');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Beranda | Museum Bandar Cimanuk</title>

    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/dashboard.css">
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/visitor-content.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="website-wrapper">

    <?php $activePage = 'dashboard.php'; require __DIR__ . '/navbar.php'; ?>


    <!-- HERO -->
    <div class="hero-slider" aria-label="Informasi utama museum">
        <?php foreach ($homeItems as $slideIndex => $slide): ?>
            <?php
            $slideImage = visitor_image($slide['gambar'] ?? '');
            $slidePosition = in_array($slide['image_position'] ?? 'center', ['center', 'top', 'bottom', 'left', 'right'], true)
                ? $slide['image_position']
                : 'center';
            ?>
            <section class="hero hero-slide <?= $slideIndex === 0 ? 'is-active' : '' ?>" data-slide="<?= $slideIndex ?>" aria-hidden="<?= $slideIndex === 0 ? 'false' : 'true' ?>"<?= $slideImage !== '' ? ' style="background-image: linear-gradient(90deg, rgba(57,31,19,.75), rgba(57,31,19,.38) 50%, rgba(57,31,19,.08)), url(\'' . visitor_escape($slideImage) . '\'); background-position: ' . visitor_escape($slidePosition) . ';"' : '' ?>>
                <div class="hero-content">
                    <h1><span><?= visitor_escape($slide['judul']) ?></span></h1>
                    <p><?= nl2br(visitor_escape($slide['deskripsi'] ?? '')) ?></p>
                    <div class="hero-buttons">
                        <a href="#koleksi-unggulan" class="hero-btn">Jelajahi &nbsp;→</a>
                        <a href="koleksi.php" class="hero-btn">Lihat Koleksi &nbsp;→</a>
                    </div>
                </div>
            </section>
        <?php endforeach; ?>
        <?php if (count($homeItems) > 1): ?>
            <div class="hero-controls">
                <button type="button" data-slide-previous aria-label="Informasi sebelumnya">‹</button>
                <span data-slide-count>1 / <?= count($homeItems) ?></span>
                <button type="button" data-slide-next aria-label="Informasi berikutnya">›</button>
            </div>
        <?php endif; ?>
    </div>


    <!-- CONTENT -->
    <main class="home-content" id="koleksi-unggulan">

        <div class="section-heading">

            <h2>Koleksi Unggulan</h2>

            <span class="heading-line"></span>

            <a href="koleksi.php">
                Lihat Semua Koleksi →
            </a>

        </div>


        <div class="collection-grid">
            <?php foreach ($featuredCollections as $collection): ?>
                <?php $collectionImage = visitor_image($collection['gambar'] ?? '') ?: 'assets/images/museum-illustration.png'; ?>
                <a href="koleksi-detail.php?id=<?= (int) $collection['id'] ?>" class="collection-card">
                    <img
                        src="<?= visitor_escape($collectionImage) ?>"
                        alt="<?= visitor_escape($collection['judul']) ?>"
                    >
                    <div class="collection-overlay">
                        <?= visitor_escape($collection['judul']) ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>


        <!-- INFO -->
        <div class="info-grid">

            <div class="info-card">

                <div class="info-icon">
                    <i class="fa-solid fa-location-dot"></i>
                </div>

                <div class="info-content">
                    <h3>Lokasi Museum</h3>

                    <p>
                        <a
                            href="<?= visitor_escape($mapUrl) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <?= visitor_escape($contactContent['alamat'] ?? 'Alamat museum belum diatur.') ?>
                        </a>
                    </p>
                </div>

            </div>


            <div class="info-card">

                <div class="info-icon">
                    <i class="fa-regular fa-clock"></i>
                </div>

                <div class="info-content">
                    <h3>Jam Operasional</h3>

                    <ul class="hours-list">
                        <?php foreach ($operatingHours as $schedule): ?>
                            <li>
                                <span><?= visitor_escape($schedule['hari']) ?></span>
                                <span><?= !empty($schedule['tutup']) ? 'Tutup' : visitor_escape($schedule['jam_buka'] . '–' . $schedule['jam_tutup']) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>

        </div>

    </main>


    <!-- FOOTER -->
    <footer class="museum-footer" id="kontak">

        <div class="footer-content">

            <div class="footer-brand">
                <h3>MUSEUM BANDAR CIMANUK</h3>
                <p><?= visitor_escape($contactContent['deskripsi'] ?? 'Indramayu, Tanah Sejarah, Lautan Cerita.') ?></p>
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
                    href="mailto:<?= visitor_escape($contactContent['email'] ?? 'museum.bandarcimanuk@gmail.com') ?>"
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

<script>
const heroSlides = [...document.querySelectorAll('.hero-slide')];
const slideCount = document.querySelector('[data-slide-count]');
let activeSlide = 0;

function showSlide(nextIndex) {
    activeSlide = (nextIndex + heroSlides.length) % heroSlides.length;
    heroSlides.forEach((slide, index) => {
        const active = index === activeSlide;
        slide.classList.toggle('is-active', active);
        slide.setAttribute('aria-hidden', String(!active));
    });
    if (slideCount) slideCount.textContent = `${activeSlide + 1} / ${heroSlides.length}`;
}

document.querySelector('[data-slide-previous]')?.addEventListener('click', () => showSlide(activeSlide - 1));
document.querySelector('[data-slide-next]')?.addEventListener('click', () => showSlide(activeSlide + 1));
</script>
</body>
</html>