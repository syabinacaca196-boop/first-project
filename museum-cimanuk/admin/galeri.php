<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MBC Admin - Galeri</title>

    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>

<div class="admin-wrapper">

    <aside class="sidebar">

        <h1>MBC Admin</h1>

        <nav>

            <a href="dashboard.php">Dashboard</a>

            <a href="beranda.php">Beranda</a>

            <a href="jelajah.php">Jelajah</a>

            <a href="koleksi.php">Koleksi</a>

            <a href="galeri.php" class="active">Galeri</a>

            <a href="artikel.php">Artikel</a>

            <a href="kontak.php">Kontak</a>

            <a href="../index.php" target="_blank">
                Lihat Website
            </a>

            <a href="logout.php">Logout</a>

        </nav>

    </aside>


    <main class="content">

        <div class="topbar">

            <h2>Kelola Galeri</h2>

            <a href="galeri_tambah.php" class="btn">
                + Tambah Data
            </a>

        </div>


        <div class="table-card">

            <table>

                <thead>

                    <tr>

                        <th>Judul</th>

                        <th>Gambar</th>

                        <th>Deskripsi</th>

                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td colspan="4" class="empty">

                            Belum ada data galeri.

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </main>

</div>

</body>

</html>