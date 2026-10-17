<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MBC Admin - Kontak</title>

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
            <a href="galeri.php">Galeri</a>
            <a href="artikel.php">Artikel</a>

            <a href="kontak.php" class="active">
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


    <main class="content">

        <div class="topbar">

            <h2>Kelola Kontak</h2>

            <a href="kontak_edit.php" class="btn">
                + Tambah Data
            </a>

        </div>


        <div class="table-card">

            <table>

                <thead>

                    <tr>

                        <th>Alamat</th>

                        <th>No. Telepon</th>

                        <th>Email</th>

                        <th>Instagram</th>

                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td colspan="5" class="empty">

                            Belum ada data kontak.

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </main>

</div>

</body>

</html>