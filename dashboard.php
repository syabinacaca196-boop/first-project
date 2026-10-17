
<?php

require_once 'auth.php';
require_once 'layout.php';

// Tampilkan header dan sidebar dashboard.
render_header(
    'dashboard.php',
    'Dashboard',
    'Kelola informasi website Museum Bandar Cimanuk dengan mudah.'
);

// Daftar tabel dan nama menu yang ditampilkan.
$tables = [
    'beranda' => 'Beranda',
    'jelajah' => 'Jelajah',
    'koleksi' => 'Koleksi',
    'kategori_koleksi' => 'Kategori Koleksi',
    'jam_operasional' => 'Jam Operasional',
    'galeri'  => 'Galeri',
    'artikel' => 'Artikel',
    'kontak'  => 'Kontak',
    'pesan_kontak' => 'Pesan Pengunjung',
];

$urls = [
    'kategori_koleksi' => 'kategori-koleksi.php',
    'jam_operasional' => 'jam-operasional.php',
    'pesan_kontak' => 'pesan-admin.php',
];

// Warna dan ikon setiap kartu.
$colors = [
    'blue',
    'green',
    'orange',
    'purple',
    'red',
    'teal',
    'teal',
    'blue',
    'orange'
];

$icons = [
    '⌂',
    '◉',
    '⬡',
    '▧',
    '▤',
    '☎',
    '▦',
    '◷',
    '✉'
];

// Menghitung jumlah data di setiap tabel.
$counts = [];

foreach ($tables as $table => $label) {
    $result = $conn->query(
        "SELECT COUNT(*) AS total FROM `$table`"
    );

    $counts[$table] = $result
        ? (int) $result->fetch_assoc()['total']
        : 0;
}

?>

<!-- KARTU STATISTIK -->
<div class="stats-grid">

    <?php
    $i = 0;

    foreach ($tables as $table => $label):
        $url = $urls[$table] ?? ($table . '.php');
        $color = $colors[$i];
        $icon = $icons[$i];
    ?>

        <a
            class="stat-card <?= e($color) ?>"
            href="<?= e($url) ?>"
        >
            <div class="stat-icon">
                <?= e($icon) ?>
            </div>

            <div class="stat-label">
                <?= e($label) ?>
            </div>

            <strong><?= $counts[$table] ?></strong>

            <small>Data tersimpan</small>
        </a>

    <?php
        $i++;
    endforeach;
    ?>

</div>

<!-- PANEL DATA -->
<div class="panel">

    <div class="panel-head">

        <div>
            <h2>Ringkasan Data</h2>

            <p>
                Jumlah konten yang tersimpan di setiap bagian website.
            </p>
        </div>

        <a class="btn primary" href="beranda.php">
            + Kelola Data
        </a>

    </div>

    <!-- TABEL JUMLAH DATA -->
    <div class="table-wrap">

        <table>
            <thead>
                <tr>
                    <th>Bagian</th>
                    <th>Jumlah Data</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($tables as $table => $label): ?>

                    <tr>
                        <td><?= e($label) ?></td>

                        <td><?= $counts[$table] ?></td>

                        <td>
                            <a
                                class="btn small primary"
                                href="<?= e($urls[$table] ?? ($table . '.php')) ?>"
                            >
                                Kelola
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

    </div>

</div>

<?php

// Tampilkan footer dan tutup halaman.
render_footer();

?>
