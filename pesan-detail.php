<?php
require_once 'auth.php';
require_once 'layout.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM pesan_kontak WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$message = $stmt->get_result()->fetch_assoc();

if (!$message) {
    http_response_code(404);
    render_header('pesan-admin.php', 'Pesan Tidak Ditemukan');
    echo '<div class="alert error">Pesan tidak ditemukan.</div><a class="btn muted" href="pesan-admin.php">Kembali ke inbox</a>';
    render_footer();
    exit;
}

if ($message['status'] === 'baru') {
    $update = $conn->prepare("UPDATE pesan_kontak SET status = 'dibaca' WHERE id = ?");
    $update->bind_param('i', $id);
    $update->execute();
    $message['status'] = 'dibaca';
}

render_header('pesan-admin.php', 'Detail Pesan');
?>

<div class="panel">
    <div class="panel-head">
        <div><h2><?= e($message['subjek']) ?></h2><p>Diterima <?= e($message['created_at']) ?> · <?= e($message['status']) ?></p></div>
    </div>
    <p><strong><?= e($message['nama']) ?></strong> &lt;<?= e($message['email']) ?>&gt;</p>
    <div class="message-body"><?= nl2br(e($message['pesan'])) ?></div>
    <div class="form-actions">
        <a class="btn primary" href="mailto:<?= e($message['email']) ?>?subject=<?= rawurlencode('Re: ' . $message['subjek']) ?>">Balas melalui email</a>
        <a class="btn muted" href="pesan-admin.php">Kembali ke inbox</a>
    </div>
</div>

<?php render_footer(); ?>