<?php
require_once __DIR__ . '/data.php';
$collectionItems = visitor_content('koleksi');
$categoryItems = visitor_content('kategori_koleksi');
$collectionIntro = $collectionItems[0] ?? [];
$contactContent = visitor_content('kontak')[0] ?? [];
$whatsappNumber = preg_replace('/\D/', '', $contactContent['telepon'] ?? '');
$categoriesById = [];
foreach ($categoryItems as $category) {
    $categoriesById[(int) $category['id']] = $category;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Koleksi | Museum Bandar Cimanuk</title>

    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/dashboard.css">
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/koleksi.css">
    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/visitor-content.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >
</head>

<body>

<div class="website-wrapper">

    <?php $activePage = 'koleksi.php'; require __DIR__ . '/navbar.php'; ?>


    <!-- =========================
         INTRO KOLEKSI
    ========================== -->

    <section class="collection-intro-hero">

        <div class="intro-text">

            <div class="breadcrumb">

                BERANDA

                <span>›</span>

                KOLEKSI

            </div>


            <div class="intro-label">

                KOLEKSI

                <span></span>

            </div>


            <h1>

                Koleksi

                <strong>
                    Museum
                </strong>

            </h1>


            <p>

                <?= nl2br(visitor_escape($collectionIntro['deskripsi'] ?? 'Koleksi Museum Bandar Cimanuk merekam perjalanan sejarah dan budaya Indramayu.')) ?>

            </p>

        </div>


        <div class="intro-art">

            <div class="art-glow"></div>

            <img
                src="assets/images/museum-illustration.png"
                alt="Ilustrasi Koleksi Museum Bandar Cimanuk"
            >

        </div>

    </section>


    <!-- =========================
         KOLEKSI
    ========================== -->

    <main class="collection-page">

        <section>
            <div class="collection-heading">
                <div><span>KATEGORI MUSEUM</span><h2>Jelajahi berdasarkan kategori</h2></div>
            </div>
            <div class="category-grid">
                <?php foreach ($categoryItems as $category): ?>
                    <a class="category-card" href="kategori.php?id=<?= (int) $category['id'] ?>">
                        <img src="<?= visitor_escape(visitor_image($category['gambar'] ?? '') ?: 'assets/images/museum-illustration.png') ?>" alt="<?= visitor_escape($category['nama']) ?>">
                        <div>
                            <h3><?= visitor_escape($category['nama']) ?></h3>
                            <p><?= visitor_escape($category['deskripsi'] ?? '') ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>


        <div class="collection-heading">

            <div>

                <span>
                    JELAJAHI WARISAN
                </span>

                <h2>
                    Temukan Koleksi
                </h2>

            </div>

            <div class="heading-decoration"></div>

        </div>


        <!-- FILTER -->

        <div class="filter-buttons">

            <button
                class="filter-btn active"
                data-filter="semua"
            >
                Semua
            </button>

            <?php foreach ($categoryItems as $category): ?>
                <button class="filter-btn" data-filter="<?= visitor_escape($category['slug']) ?>">
                    <?= visitor_escape($category['nama']) ?>
                </button>
            <?php endforeach; ?>

        </div>


        <!-- GRID -->

        <div class="all-collection-grid">
            <?php foreach ($collectionItems as $index => $item): ?>
                <?php $itemCategory = $categoriesById[(int) ($item['kategori_id'] ?? 0)] ?? []; ?>
                <?php $categoryText = strtolower(($itemCategory['slug'] ?? '') . ' ' . ($itemCategory['nama'] ?? '') . ' ' . $item['judul'] . ' ' . $item['deskripsi']); ?>
                <article class="museum-collection-card" data-category="<?= visitor_escape($categoryText) ?>">
                    <div class="museum-collection-image">
                        <img
                            src="<?= visitor_escape(visitor_image($item['gambar'] ?? '') ?: 'assets/images/museum-illustration.png') ?>"
                            alt="<?= visitor_escape($item['judul']) ?>"
                        >
                    </div>
                    <div class="museum-collection-info">
                        <span class="collection-number">KOLEKSI <?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
                        <h3><a href="koleksi-detail.php?id=<?= (int) $item['id'] ?>"><?= visitor_escape($item['judul']) ?></a></h3>
                        <p><?= nl2br(visitor_escape($item['deskripsi'])) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

    </main>


    <!-- =========================
         FOOTER
    ========================== -->

    <footer class="museum-footer" id="kontak">

        <div class="footer-content">


            <div class="footer-brand">

                <h3>
                    MUSEUM BANDAR CIMANUK
                </h3>

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


<!-- =========================================
     FILTER + HASIL SEARCH DARI NAVBAR
========================================== -->

<script>

const filterButtons =
    document.querySelectorAll('.filter-btn');

const collectionCards =
    document.querySelectorAll('.museum-collection-card');


/* Ambil ?q= dari URL */

const urlParams =
    new URLSearchParams(window.location.search);

const searchKeyword =
    (urlParams.get('q') || '')
        .toLowerCase()
        .trim();


/* =========================================
   KALAU DATANG DARI SEARCH
========================================= */

if (searchKeyword !== '') {

    collectionCards.forEach(card => {

        const category =
            (card.dataset.category || '')
                .toLowerCase();

        const content =
            card.innerText
                .toLowerCase();

        const cocok =
            category.includes(searchKeyword) ||
            content.includes(searchKeyword);

        card.style.display =
            cocok ? 'block' : 'none';

    });


    /* tombol filter otomatis aktif */

    let categoryMatched = false;

    filterButtons.forEach(button => {

        button.classList.remove('active');

        if (
            button.dataset.filter === searchKeyword
        ) {

            button.classList.add('active');

            categoryMatched = true;

        }

    });


    if (!categoryMatched) {

        const semuaButton =
            document.querySelector(
                '[data-filter="semua"]'
            );

        if (semuaButton) {
            semuaButton.classList.add('active');
        }

    }


    /* scroll otomatis ke hasil koleksi */

    setTimeout(() => {

        document
            .querySelector('.collection-page')
            .scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

    }, 150);

}


/* =========================================
   FILTER BUTTON
========================================= */

filterButtons.forEach(button => {

    button.addEventListener(
        'click',
        function () {

            const filter =
                this.dataset.filter;


            filterButtons.forEach(btn => {
                btn.classList.remove('active');
            });


            this.classList.add('active');


            collectionCards.forEach(card => {

                const category =
                    card.dataset.category;

                if (
                    filter === 'semua' ||
                    category.includes(filter)
                ) {

                    card.style.display = 'block';

                } else {

                    card.style.display = 'none';

                }

            });


            /* Hilangkan ?q= setelah klik filter */

            window.history.replaceState(
                {},
                document.title,
                'koleksi.php'
            );

        }
    );

});

</script>


</body>
</html>