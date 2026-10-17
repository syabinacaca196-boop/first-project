<?php
require_once __DIR__ . '/config/koneksi.php';

function visitor_content($table)
{
    global $conn;

    $allowedTables = ['beranda', 'jelajah', 'koleksi', 'galeri', 'artikel', 'kontak', 'kategori_koleksi', 'jam_operasional'];

    if (!in_array($table, $allowedTables, true)) {
        return [];
    }

    $publishedTables = ['beranda', 'jelajah', 'koleksi', 'galeri', 'artikel', 'kontak', 'kategori_koleksi'];
    $where = in_array($table, $publishedTables, true) ? ' WHERE is_published = 1' : '';
    $order = $table === 'artikel'
        ? ' ORDER BY tanggal DESC, sort_order ASC, id DESC'
        : ' ORDER BY sort_order ASC, id DESC';
    $result = $conn->query("SELECT * FROM `$table`$where$order");

    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function visitor_featured_collections()
{
    global $conn;
    $result = $conn->query('SELECT * FROM koleksi WHERE is_published = 1 AND is_featured = 1 ORDER BY sort_order ASC, id DESC LIMIT 4');
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function visitor_collection_category($id)
{
    global $conn;
    $stmt = $conn->prepare('SELECT * FROM kategori_koleksi WHERE id = ? AND is_published = 1 LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

function visitor_category_collections($categoryId)
{
    global $conn;
    $stmt = $conn->prepare('SELECT * FROM koleksi WHERE kategori_id = ? AND is_published = 1 ORDER BY sort_order ASC, id DESC');
    $stmt->bind_param('i', $categoryId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function visitor_collection($id)
{
    global $conn;
    $stmt = $conn->prepare('SELECT * FROM koleksi WHERE id = ? AND is_published = 1 LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

function visitor_article($id)
{
    global $conn;
    $stmt = $conn->prepare('SELECT * FROM artikel WHERE id = ? AND is_published = 1 LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

function visitor_search($keyword)
{
    global $conn;
    $term = '%' . $keyword . '%';
    $items = [];

    $stmt = $conn->prepare('SELECT id, judul, deskripsi, gambar FROM koleksi WHERE is_published = 1 AND (judul LIKE ? OR deskripsi LIKE ? OR sejarah LIKE ? OR filosofi LIKE ?) ORDER BY sort_order ASC LIMIT 20');
    $stmt->bind_param('ssss', $term, $term, $term, $term);
    $stmt->execute();
    foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $row) {
        $items[] = ['type' => 'Koleksi', 'url' => 'koleksi-detail.php?id=' . (int) $row['id']] + $row;
    }

    $stmt = $conn->prepare('SELECT id, judul, deskripsi, gambar, tanggal FROM artikel WHERE is_published = 1 AND (judul LIKE ? OR deskripsi LIKE ? OR isi LIKE ?) ORDER BY tanggal DESC LIMIT 20');
    $stmt->bind_param('sss', $term, $term, $term);
    $stmt->execute();
    foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $row) {
        $items[] = ['type' => 'Artikel', 'url' => 'artikel-detail.php?id=' . (int) $row['id']] + $row;
    }

    return $items;
}

function visitor_escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function visitor_image($filename)
{
    if (!$filename) {
        return '';
    }

    $filename = basename($filename);

    if (!is_file(__DIR__ . '/../assets/uploads/' . $filename)) {
        return '';
    }

    return '/admin/assets/uploads/' . rawurlencode($filename);
}
?>