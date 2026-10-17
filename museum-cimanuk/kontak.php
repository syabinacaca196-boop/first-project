<?php
require_once __DIR__ . '/data.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$contactItems = visitor_content('kontak');
$contactItem = $contactItems[0] ?? [];
$operatingHours = visitor_content('jam_operasional');
$mapUrl = $contactItem['maps_url'] ?? '';
if (!preg_match('~^https?://~i', $mapUrl)) {
    $mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($contactItem['alamat'] ?? 'Museum Bandar Cimanuk');
}
$phone = $contactItem['telepon'] ?? '';
$phoneLink = preg_replace('/[^0-9+]/', '', $phone);
$contactCsrf = $_SESSION['contact_csrf'] ??= bin2hex(random_bytes(32));

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    include 'koneksi.php';
    $messageConn = $conn;

    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subjek = trim($_POST['subjek'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');

    if (!hash_equals($contactCsrf, $_POST['csrf_token'] ?? '')) {
        $error = 'Sesi formulir berakhir. Muat ulang halaman lalu coba lagi.';
    } elseif (!empty($_POST['website'])) {
        $error = 'Pesan tidak dapat diproses.';
    } elseif (
        $nama === "" ||
        $email === "" ||
        $subjek === "" ||
        $pesan === ""
    ) {

        $error = "Semua kolom wajib diisi.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Format email tidak valid.";

    } elseif (mb_strlen($nama) > 150 || mb_strlen($email) > 150 || mb_strlen($subjek) > 200) {

        $error = 'Nama, email, atau subjek melebihi batas karakter.';

    } else {

        $stmt = mysqli_prepare(
            $messageConn,
            "INSERT INTO pesan_kontak
            (nama, email, subjek, pesan)
            VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $nama,
            $email,
            $subjek,
            $pesan
        );

        if (mysqli_stmt_execute($stmt)) {

            $success =
                "Pesan berhasil dikirim. Terima kasih telah menghubungi Museum Bandar Cimanuk.";

        } else {

            $error =
                "Pesan belum berhasil dikirim. Silakan coba kembali.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Kontak | Museum Bandar Cimanuk</title>

    <link
        rel="stylesheet"
        href="/admin/museum-cimanuk/assets/css/dashboard.css"
    >

    <link
        rel="stylesheet"
        href="/admin/museum-cimanuk/assets/css/kontak.css"
    >

    <link rel="stylesheet" href="/admin/museum-cimanuk/assets/css/visitor-content.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

</head>

<body>

<div class="website-wrapper">


    <!-- =====================================
         NAVBAR
    ====================================== -->

    <?php $activePage = 'kontak.php'; require __DIR__ . '/navbar.php'; ?>



    <!-- =====================================
         HERO KONTAK
    ====================================== -->

    <section class="contact-hero">

        <div class="contact-hero-text">

            <div class="contact-breadcrumb">

                BERANDA

                <span>›</span>

                KONTAK

            </div>


            <span class="contact-label">
                HUBUNGI KAMI
            </span>


            <h1>

                Mari Terhubung dengan

                <strong>
                    Museum Bandar Cimanuk
                </strong>

            </h1>


            <p>

                Punya pertanyaan tentang koleksi,
                kunjungan museum, sejarah, atau kegiatan
                Museum Bandar Cimanuk?

                Kirim pesan melalui formulir berikut.

            </p>

        </div>


        <div class="contact-hero-decoration">

            <div class="contact-circle">

                <i class="fa-regular fa-envelope"></i>

            </div>

            <span class="decoration-line"></span>

            <small>
                INFORMASI • SEJARAH • BUDAYA
            </small>

        </div>

    </section>



    <!-- =====================================
         KONTAK CONTENT
    ====================================== -->

    <main class="contact-section">


        <!-- KIRI -->
        <div class="contact-info-area">

            <span class="section-mini">
                INFORMASI MUSEUM
            </span>

            <h2>
                Kunjungi dan Hubungi Kami
            </h2>

            <p class="info-intro">

                Kami terbuka untuk pertanyaan,
                informasi kunjungan, kegiatan edukasi,
                maupun kebutuhan informasi mengenai
                Museum Bandar Cimanuk.

            </p>


            <?php foreach ($contactItems as $contactItem): ?>
                <a
                    class="contact-info-card"
                    href="<?= visitor_escape($mapUrl) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <div class="contact-info-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <span><?= visitor_escape($contactItem['judul']) ?></span>
                        <h3><?= visitor_escape($contactItem['judul']) ?></h3>
                        <p>
                            <?= nl2br(visitor_escape($contactItem['deskripsi'])) ?>
                            <?php if (!empty($contactItem['alamat'])): ?><br><?= nl2br(visitor_escape($contactItem['alamat'])) ?><?php endif; ?>
                        </p>
                    </div>
                    <i class="fa-solid fa-arrow-up-right-from-square card-arrow"></i>
                </a>
            <?php endforeach; ?>


            <!-- JAM -->
            <div class="contact-info-card">

                <div class="contact-info-icon">

                    <i class="fa-regular fa-clock"></i>

                </div>

                <div>

                    <span>JAM OPERASIONAL</span>

                    <ul class="hours-list">
                        <?php foreach ($operatingHours as $schedule): ?>
                            <li><span><?= visitor_escape($schedule['hari']) ?></span><span><?= !empty($schedule['tutup']) ? 'Tutup' : visitor_escape($schedule['jam_buka'] . '–' . $schedule['jam_tutup']) ?></span></li>
                        <?php endforeach; ?>
                    </ul>

                </div>

            </div>


            <!-- WHATSAPP -->
            <a
                class="contact-info-card"
                href="https://wa.me/<?= visitor_escape(preg_replace('/\D/', '', $phone)) ?>"
                target="_blank"
            >

                <div class="contact-info-icon">

                    <i class="fa-brands fa-whatsapp"></i>

                </div>

                <div>

                    <span>WHATSAPP</span>

                    <h3>
                        <?= visitor_escape($phone ?: 'Nomor telepon belum diatur') ?>
                    </h3>

                    <p>
                        Klik untuk menghubungi museum
                        melalui WhatsApp.
                    </p>

                </div>

                <i class="fa-solid fa-arrow-up-right-from-square card-arrow"></i>

            </a>


            <!-- EMAIL -->
            <a
                class="contact-info-card"
                href="mailto:<?= visitor_escape($contactItem['email'] ?? '') ?>"
            >

                <div class="contact-info-icon">

                    <i class="fa-regular fa-envelope"></i>

                </div>

                <div>

                    <span>EMAIL</span>

                    <h3>
                        <?= visitor_escape($contactItem['email'] ?? 'Email belum diatur') ?>
                    </h3>

                    <p>
                        Kirim pertanyaan melalui email.
                    </p>

                </div>

                <i class="fa-solid fa-arrow-up-right-from-square card-arrow"></i>

            </a>


            <!-- SOCIAL -->
            <div class="contact-social">

                <span>
                    Ikuti Museum Bandar Cimanuk
                </span>


                <div>

                    <a
                        href="https://www.instagram.com/museum_mbc"
                        target="_blank"
                    >
                        <i class="fa-brands fa-instagram"></i>
                    </a>


                    <a
                        href="https://www.facebook.com/MBCmuseum"
                        target="_blank"
                    >
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>


                    <a
                        href="https://wa.me/<?= visitor_escape(preg_replace('/\D/', '', $phone)) ?>"
                        target="_blank"
                    >
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>

                </div>

            </div>

        </div>



        <!-- KANAN -->
        <div class="contact-form-card">

            <div class="form-heading">

                <span>
                    KIRIM PESAN
                </span>

                <h2>
                    Ada yang ingin ditanyakan?
                </h2>

                <p>
                    Isi formulir di bawah dan pesan Anda
                    akan masuk ke halaman admin museum.
                </p>

            </div>


            <?php if ($success !== ""): ?>

                <div class="contact-alert success">

                    <i class="fa-solid fa-circle-check"></i>

                    <?= htmlspecialchars($success); ?>

                </div>

            <?php endif; ?>


            <?php if ($error !== ""): ?>

                <div class="contact-alert error">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <?= htmlspecialchars($error); ?>

                </div>

            <?php endif; ?>


            <form method="POST">

                <input type="hidden" name="csrf_token" value="<?= visitor_escape($contactCsrf) ?>">
                <div class="contact-honeypot" aria-hidden="true">
                    <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>


                <div class="contact-form-row">


                    <div class="contact-form-group">

                        <label>
                            Nama
                        </label>

                        <div class="contact-input">

                            <i class="fa-regular fa-user"></i>

                            <input
                                type="text"
                                name="nama"
                                maxlength="150"
                                placeholder="Masukkan nama"
                                required
                            >

                        </div>

                    </div>



                    <div class="contact-form-group">

                        <label>
                            Email
                        </label>

                        <div class="contact-input">

                            <i class="fa-regular fa-envelope"></i>

                            <input
                                type="email"
                                name="email"
                                maxlength="150"
                                placeholder="Masukkan email"
                                required
                            >

                        </div>

                    </div>

                </div>


                <div class="contact-form-group">

                    <label>
                        Subjek
                    </label>

                    <div class="contact-input">

                        <i class="fa-regular fa-message"></i>

                        <input
                            type="text"
                            name="subjek"
                            maxlength="200"
                            placeholder="Contoh: Informasi kunjungan museum"
                            required
                        >

                    </div>

                </div>


                <div class="contact-form-group">

                    <label>
                        Pesan
                    </label>

                    <textarea
                        name="pesan"
                        maxlength="5000"
                        placeholder="Tuliskan pesan Anda..."
                        required
                    ></textarea>

                </div>


                <button
                    type="submit"
                    class="contact-submit"
                >

                    Kirim Pesan

                    <i class="fa-solid fa-paper-plane"></i>

                </button>


                <p class="privacy-text">

                    <i class="fa-solid fa-lock"></i>

                    Informasi yang dikirim hanya digunakan
                    untuk keperluan komunikasi Museum Bandar Cimanuk.

                </p>

            </form>

        </div>

    </main>



    <!-- FOOTER -->
    <footer class="museum-footer">

        <div class="footer-content">

            <div class="footer-brand">

                <h3>
                    MUSEUM BANDAR CIMANUK
                </h3>

                <p>
                    Indramayu, Tanah Sejarah,
                    Lautan Cerita.
                </p>

            </div>


            <div class="footer-social">

                <a
                    href="https://www.instagram.com/museum_mbc"
                    target="_blank"
                >
                    <i class="fa-brands fa-instagram"></i>
                </a>

                <a
                    href="https://www.facebook.com/MBCmuseum"
                    target="_blank"
                >
                    <i class="fa-brands fa-facebook-f"></i>
                </a>

                <a
                    href="https://wa.me/<?= visitor_escape(preg_replace('/\D/', '', $phone)) ?>"
                    target="_blank"
                >
                    <i class="fa-brands fa-whatsapp"></i>
                </a>

                <a
                    href="mailto:<?= visitor_escape($contactItem['email'] ?? '') ?>"
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