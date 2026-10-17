<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}


/* TANDAI SUDAH DIBACA */
if (isset($_GET['baca'])) {

    $id = intval($_GET['baca']);

    mysqli_query(
        $conn,
        "UPDATE pesan_kontak
         SET status='dibaca'
         WHERE id=$id"
    );

    header("Location: pesan-admin.php");
    exit;
}


/* HAPUS PESAN */
if (isset($_GET['hapus'])) {

    $id = intval($_GET['hapus']);

    mysqli_query(
        $conn,
        "DELETE FROM pesan_kontak
         WHERE id=$id"
    );

    header("Location: pesan-admin.php");
    exit;
}


$result = mysqli_query(
    $conn,
    "SELECT *
     FROM pesan_kontak
     ORDER BY created_at DESC"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Pesan Masuk | Admin Museum</title>

    <link
        rel="stylesheet"
        href="assets/css/dashboard.css"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <style>

        body {
            background: #fff4ed;
        }

        .message-page {
            padding: 40px 6%;
        }

        .message-page h1 {
            font-family:
                'Cormorant Garamond',
                serif;

            font-size: 38px;

            color: #543420;
        }

        .message-page > p {
            margin-top: 5px;

            font-size: 10px;

            color: #856d5d;
        }

        .message-list {
            display: grid;

            gap: 15px;

            margin-top: 28px;
        }

        .message-card {
            padding: 20px;

            border-radius: 17px;

            background: #fffaf7;

            border: 1px solid #ead1bf;

            box-shadow:
                0 6px 18px
                rgba(70,40,25,.06);
        }

        .message-card.baru {
            border-left:
                5px solid #76503a;
        }

        .message-top {
            display: flex;

            align-items: flex-start;
            justify-content: space-between;
        }

        .message-name {
            font-family:
                'Cormorant Garamond',
                serif;

            font-size: 21px;

            color: #543420;
        }

        .message-email {
            margin-top: 3px;

            font-size: 8px;

            color: #977c6b;
        }

        .message-status {
            padding: 5px 10px;

            border-radius: 999px;

            font-size: 7px;

            background: #edd8c7;

            color: #684630;
        }

        .message-subject {
            margin-top: 14px;

            font-size: 10px;
            font-weight: 600;

            color: #684630;
        }

        .message-text {
            margin-top: 7px;

            font-size: 9px;
            line-height: 1.7;

            color: #80685a;
        }

        .message-date {
            margin-top: 12px;

            font-size: 7px;

            color: #ab9486;
        }

        .message-actions {
            display: flex;

            gap: 8px;

            margin-top: 14px;
        }

        .message-actions a {
            padding: 7px 12px;

            border-radius: 999px;

            background: #6b4834;

            color: white;

            font-size: 8px;
        }

        .message-actions a.delete {
            background: #a86658;
        }

        .empty-message {
            padding: 50px;

            text-align: center;

            border-radius: 18px;

            background: #fffaf7;

            color: #907667;
        }

    </style>

</head>


<body>


<nav class="navbar">

    <a
        href="dashboard.php"
        class="nav-brand"
    >

        <img
            src="assets/images/logo-mbcberanda.jpg"
            class="nav-logo"
            alt="Logo Museum Bandar Cimanuk"
        >

        <div class="brand-text">

            <small>MUSEUM</small>

            <strong>
                BANDAR CIMANUK
            </strong>

        </div>

    </a>


    <div class="nav-menu">

        <a href="dashboard.php">
            Beranda
        </a>

        <a
            href="pesan-admin.php"
            class="active"
        >
            Pesan Masuk
        </a>

        <a href="kontak.php">
            Kontak
        </a>

    </div>

</nav>


<main class="message-page">


    <h1>
        Pesan Masuk
    </h1>

    <p>
        Pesan yang dikirim melalui halaman kontak Museum Bandar Cimanuk.
    </p>


    <div class="message-list">


        <?php if (mysqli_num_rows($result) > 0): ?>


            <?php while ($row = mysqli_fetch_assoc($result)): ?>


                <div
                    class="message-card <?= $row['status'] === 'baru' ? 'baru' : ''; ?>"
                >


                    <div class="message-top">


                        <div>

                            <div class="message-name">

                                <?= htmlspecialchars($row['nama']); ?>

                            </div>


                            <div class="message-email">

                                <?= htmlspecialchars($row['email']); ?>

                            </div>

                        </div>


                        <span class="message-status">

                            <?= strtoupper($row['status']); ?>

                        </span>


                    </div>


                    <div class="message-subject">

                        <?= htmlspecialchars($row['subjek']); ?>

                    </div>


                    <div class="message-text">

                        <?= nl2br(
                            htmlspecialchars(
                                $row['pesan']
                            )
                        ); ?>

                    </div>


                    <div class="message-date">

                        <?= $row['created_at']; ?>

                    </div>


                    <div class="message-actions">


                        <?php if ($row['status'] === 'baru'): ?>

                            <a
                                href="pesan-admin.php?baca=<?= $row['id']; ?>"
                            >
                                Tandai Dibaca
                            </a>

                        <?php endif; ?>


                        <a
                            href="mailto:<?= htmlspecialchars($row['email']); ?>"
                        >
                            Balas Email
                        </a>


                        <a
                            href="pesan-admin.php?hapus=<?= $row['id']; ?>"
                            class="delete"
                            onclick="return confirm('Hapus pesan ini?')"
                        >
                            Hapus
                        </a>


                    </div>


                </div>


            <?php endwhile; ?>


        <?php else: ?>


            <div class="empty-message">

                <i class="fa-regular fa-envelope-open"></i>

                <p>
                    Belum ada pesan masuk.
                </p>

            </div>


        <?php endif; ?>


    </div>


</main>


</body>
</html>