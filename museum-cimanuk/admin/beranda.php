<?php
require_once "../config/koneksi.php";

$query = mysqli_query($conn, "SELECT * FROM konten_beranda ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MBC Admin - Beranda</title>

    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

<div class="admin-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <h1>MBC Admin</h1>

        <nav>

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="beranda.php" class="active">
                Beranda
            </a>

            <a href="jelajah.php">
                Jelajah
            </a>

            <a href="koleksi.php">
                Koleksi
            </a>

            <a href="galeri.php">
                Galeri
            </a>

            <a href="artikel.php">
                Artikel
            </a>

            <a href="kontak.php">
                Kontak
            </a>

            <a href="../index.php" target="_blank">
                Lihat Website
            </a>

            <a href="logout.php">
                Logout
            </a>

        </nav>

    </aside>


    <!-- CONTENT -->
    <main class="content">

        <div class="topbar">

            <h2>Kelola Beranda</h2>

            <a href="beranda_tambah.php" class="btn">
                + Tambah Data
            </a>

        </div>


        <div class="table-card">

            <table>

                <thead>

                    <tr>

                        <th>Judul</th>

                        <th>Deskripsi</th>

                        <th>Gambar</th>

                        <th>Jam Operasional</th>

                        <th>Alamat</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                <?php while($data = mysqli_fetch_assoc($query)): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($data['judul']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['deskripsi']) ?>
                        </td>

                        <td>

                            <?php if($data['gambar'] != ""): ?>

                                <img
                                    src="../uploads/<?= htmlspecialchars($data['gambar']) ?>"
                                    class="gambar"
                                >

                            <?php else: ?>

                                Tidak ada gambar

                            <?php endif; ?>

                        </td>

                        <td>
                            <?= htmlspecialchars($data['jam_operasional']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['alamat']) ?>
                        </td>

                        <td>

                            <a
                                href="beranda_edit.php?id=<?= $data['id'] ?>"
                                class="btn-edit"
                            >
                                Edit
                            </a>

                            <a
                                href="beranda_hapus.php?id=<?= $data['id'] ?>"
                                class="btn-delete"
                                onclick="return confirm('Yakin ingin menghapus data?')"
                            >
                                Hapus
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </main>

</div>

</body>
</html>