<?php
require_once 'auth.php';
require_once 'layout.php';

$csrfToken = $_SESSION['message_csrf'] ??= bin2hex(random_bytes(32));
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($csrfToken, $_POST['csrf_token'] ?? '')) {
        $error = 'Sesi formulir tidak valid. Muat ulang halaman lalu coba lagi.';
    } else {
        $id = (int) ($_POST['id'] ?? 0);
        $action = $_POST['action'] ?? '';

        if ($action === 'delete') {
            $stmt = $conn->prepare('DELETE FROM pesan_kontak WHERE id = ?');
            $stmt->bind_param('i', $id);
            $message = $stmt->execute() && $stmt->affected_rows > 0
                ? 'Pesan berhasil dihapus.'
                : '';
            if ($message === '') {
                $error = 'Pesan tidak ditemukan atau gagal dihapus.';
            }
        } elseif ($action === 'status') {
            $status = ($_POST['status'] ?? '') === 'dibaca' ? 'dibaca' : 'baru';
            $stmt = $conn->prepare('UPDATE pesan_kontak SET status = ? WHERE id = ?');
            $stmt->bind_param('si', $status, $id);
            $message = $stmt->execute() ? 'Status pesan diperbarui.' : '';
            if ($message === '') {
                $error = 'Status pesan gagal diperbarui.';
            }
        }
    }
}

$result = $conn->query("SELECT id, nama, email, subjek, status, created_at FROM pesan_kontak ORDER BY (status = 'baru') DESC, created_at DESC");
render_header('pesan-admin.php', 'Pesan Pengunjung', 'Kelola pertanyaan dan pesan yang dikirim melalui website.');
?>

<?php if ($message !== ''): ?><div class="alert success"><?= e($message) ?></div><?php endif; ?>
<?php if ($error !== ''): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>

<div class="panel">
    <div class="panel-head">
        <div>
            <h2>Inbox Pengunjung</h2>
            <p><?= (int) $result->num_rows ?> pesan tersimpan.</p>
        </div>
    </div>
    <div class="table-wrap">
        <table class="crud-table">
            <thead><tr><th>Status</th><th>Nama</th><th>Email</th><th>Subjek</th><th>Diterima</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['status'] === 'baru' ? 'Belum dibaca' : 'Sudah dibaca' ?></td>
                            <td><?= e($row['nama']) ?></td>
                            <td><?= e($row['email']) ?></td>
                            <td><?= e($row['subjek']) ?></td>
                            <td><?= e($row['created_at']) ?></td>
                            <td class="action-cell">
                                <div class="actions">
                                    <a class="btn small primary" href="pesan-detail.php?id=<?= (int) $row['id'] ?>">Baca</a>
                                    <form method="post">
                                        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                        <input type="hidden" name="action" value="status">
                                        <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                        <input type="hidden" name="status" value="<?= $row['status'] === 'baru' ? 'dibaca' : 'baru' ?>">
                                        <button class="btn small muted" type="submit"><?= $row['status'] === 'baru' ? 'Tandai dibaca' : 'Tandai belum dibaca' ?></button>
                                    </form>
                                    <form method="post" onsubmit="return confirm('Hapus pesan ini?')">
                                        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                        <button class="btn small danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="empty">Belum ada pesan pengunjung.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php render_footer(); ?>