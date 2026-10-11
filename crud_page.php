```php
<?php
require_once 'auth.php';
require_once 'layout.php';

$message = '';
$error = '';

// Proses tambah, edit, dan hapus data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    // =========================
    // HAPUS DATA
    // =========================
    if ($action === 'delete') {

        $id = (int) ($_POST['id'] ?? 0);

        $stmt = $conn->prepare(
            "DELETE FROM `$table` WHERE id = ?"
        );
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            $message = 'Data berhasil dihapus.';
        } else {
            $error = 'Data gagal dihapus.';
        }

    // =========================
    // TAMBAH DAN EDIT DATA
    // =========================
    } elseif ($action === 'save') {

        $id = (int) ($_POST['id'] ?? 0);
        $values = [];

        foreach ($fields as $key => $field) {

            // KHUSUS KOLOM GAMBAR
            if ($key === 'gambar') {

                // Pertahankan gambar lama saat edit
                $values[$key] = trim(
                    $_POST['gambar_lama'] ?? ''
                );

                // Periksa apakah pengguna memilih gambar
                if (
                    isset($_FILES['gambar']) &&
                    $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE
                ) {

                    if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
                        $error = 'Upload gambar gagal.';

                    } elseif (
                        $_FILES['gambar']['size'] > 5 * 1024 * 1024
                    ) {
                        $error = 'Ukuran gambar maksimal 5 MB.';

                    } else {

                        // Periksa tipe gambar
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $mime = $finfo->file(
                            $_FILES['gambar']['tmp_name']
                        );

                        $allowed = [
                            'image/jpeg' => 'jpg',
                            'image/png'  => 'png',
                            'image/gif'  => 'gif',
                            'image/webp' => 'webp'
                        ];

                        if (!isset($allowed[$mime])) {

                            $error =
                                'Format gambar harus JPG, PNG, GIF, atau WEBP.';

                        } else {

                            // Folder penyimpanan gambar
                            $folder = __DIR__ . '/assets/uploads/';

                            if (!is_dir($folder)) {
                                mkdir($folder, 0755, true);
                            }

                            // Nama file dibuat unik
                            $namaFile = bin2hex(random_bytes(16))
                                      . '.'
                                      . $allowed[$mime];

                            // Simpan gambar ke folder uploads
                            if (
                                move_uploaded_file(
                                    $_FILES['gambar']['tmp_name'],
                                    $folder . $namaFile
                                )
                            ) {
                                $values[$key] = $namaFile;

                            } else {
                                $error = 'Gambar gagal disimpan.';
                            }
                        }
                    }
                }

            } else {

                // Kolom selain gambar
                $values[$key] = trim(
                    $_POST[$key] ?? ''
                );
            }
        }

        // Validasi judul
        if (empty($values['judul'])) {
            $error = 'Judul atau nama wajib diisi.';
        }

        // Simpan hanya jika tidak ada kesalahan
        if ($error === '') {

            if ($id > 0) {

                // UPDATE DATA
                $sets = [];

                foreach ($fields as $key => $field) {
                    $sets[] = "`$key` = ?";
                }

                $sql = "UPDATE `$table` SET "
                     . implode(', ', $sets)
                     . ", updated_at = NOW() WHERE id = ?";

                $stmt = $conn->prepare($sql);

                $types = str_repeat('s', count($fields)) . 'i';

                $params = array_values($values);
                $params[] = $id;

                $stmt->bind_param($types, ...$params);

            } else {

                // INSERT DATA
                $columns = array_keys($fields);

                $placeholders = array_fill(
                    0,
                    count($columns),
                    '?'
                );

                $sql = "INSERT INTO `$table` (`"
                     . implode('`, `', $columns)
                     . "`) VALUES ("
                     . implode(', ', $placeholders)
                     . ")";

                $stmt = $conn->prepare($sql);

                $types = str_repeat('s', count($fields));
                $params = array_values($values);

                $stmt->bind_param($types, ...$params);
            }

            if ($stmt->execute()) {
                $message = 'Data berhasil disimpan.';
            } else {
                $error = 'Data gagal disimpan: ' . $stmt->error;
            }
        }
    }
}

// Ambil data yang akan diedit
$edit = null;

if (isset($_GET['edit'])) {

    $id = (int) $_GET['edit'];

    $stmt = $conn->prepare(
        "SELECT * FROM `$table` WHERE id = ?"
    );
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $edit = $stmt->get_result()->fetch_assoc();
}

// Ambil semua data
$result = $conn->query(
    "SELECT * FROM `$table` ORDER BY id DESC"
);

render_header($active_file, $label);
?>

<?php if ($message !== ''): ?>
    <div class="alert success">
        <?= e($message) ?>
    </div>
<?php endif; ?>

<?php if ($error !== ''): ?>
    <div class="alert error">
        <?= e($error) ?>
    </div>
<?php endif; ?>

<!-- FORM TAMBAH DAN EDIT -->
<div class="two-col">

    <div class="panel">

        <div class="panel-head">
            <div>
                <h2>
                    <?= $edit ? 'Edit' : 'Tambah' ?>
                    <?= e($label) ?>
                </h2>

                <p>Isi informasi konten museum.</p>
            </div>
        </div>

        <form
            method="post"
            enctype="multipart/form-data"
            class="crud-form"
        >

            <input
                type="hidden"
                name="action"
                value="save"
            >

            <input
                type="hidden"
                name="id"
                value="<?= e($edit['id'] ?? '') ?>"
            >

            <?php foreach ($fields as $key => $field): ?>

                <label>
                    <?= e($field) ?>
                    <?= $key === 'judul' ? '*' : '' ?>
                </label>

                <?php if ($key === 'gambar'): ?>

                    <!-- PREVIEW GAMBAR LAMA -->
                    <?php if (!empty($edit['gambar'])): ?>

                        <div class="current-image">

                            <img
                                class="content-image"
                                src="assets/uploads/<?= e($edit['gambar']) ?>"
                                alt="Gambar saat ini"
                            >

                            <span>
                                Gambar saat ini.
                                Pilih gambar baru jika ingin menggantinya.
                            </span>

                        </div>

                    <?php endif; ?>

                    <!-- PILIH GAMBAR DARI KOMPUTER -->
                    <input
                        type="file"
                        name="gambar"
                        accept=".jpg,.jpeg,.png,.gif,.webp"
                    >

                    <input
                        type="hidden"
                        name="gambar_lama"
                        value="<?= e($edit['gambar'] ?? '') ?>"
                    >

                    <small>
                        Klik Browse untuk memilih gambar dari komputer.
                        Format JPG, PNG, GIF, atau WEBP.
                        Maksimal 5 MB.
                    </small>

                <?php elseif (
                    $key === 'isi' ||
                    $key === 'deskripsi' ||
                    $key === 'alamat'
                ): ?>

                    <textarea
                        name="<?= e($key) ?>"
                        rows="4"
                    ><?= e($edit[$key] ?? '') ?></textarea>

                <?php else: ?>

                    <input
                        type="text"
                        name="<?= e($key) ?>"
                        value="<?= e($edit[$key] ?? '') ?>"
                        <?= $key === 'judul' ? 'required' : '' ?>
                        placeholder="Masukkan <?= e(strtolower($field)) ?>"
                    >

                <?php endif; ?>

            <?php endforeach; ?>

            <div class="form-actions">

                <button
                    type="submit"
                    class="btn primary"
                >
                    <?= $edit ? 'Simpan Perubahan' : '+ Simpan Data' ?>
                </button>

                <?php if ($edit): ?>

                    <a
                        href="<?= e($active_file) ?>"
                        class="btn muted"
                    >
                        Batal
                    </a>

                <?php endif; ?>

            </div>

        </form>
    </div>

    <!-- PETUNJUK -->
    <div class="panel">

        <div class="panel-head">
            <div>
                <h2>Petunjuk</h2>
                <p>Pengelolaan konten museum</p>
            </div>
        </div>

        <ul class="help-list">
            <li>Isi judul atau nama konten.</li>
            <li>Pilih gambar menggunakan Browse.</li>
            <li>Klik Simpan Data untuk menambah konten.</li>
            <li>Klik Edit untuk memperbarui konten.</li>
            <li>Klik Hapus untuk menghapus konten.</li>
        </ul>

    </div>

</div>

<!-- TABEL DATA -->
<div class="panel">

    <div class="panel-head">
        <div>
            <h2>Daftar <?= e($label) ?></h2>

            <p>
                Total data:
                <?= $result ? $result->num_rows : 0 ?>
            </p>
        </div>
    </div>

    <div class="table-wrap">

        <table>

            <thead>
                <tr>
                    <th>No</th>

                    <?php foreach ($fields as $key => $field): ?>
                        <th><?= e($field) ?></th>
                    <?php endforeach; ?>

                    <th>Tanggal Update</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php if ($result && $result->num_rows > 0): ?>

                    <?php $no = 1; ?>

                    <?php while ($row = $result->fetch_assoc()): ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <?php foreach ($fields as $key => $field): ?>

                                <td>

                                    <?php if (
                                        $key === 'gambar' &&
                                        !empty($row['gambar'])
                                    ): ?>

                                        <img
                                            class="content-image"
                                            src="assets/uploads/<?= e($row['gambar']) ?>"
                                            alt="Gambar konten"
                                        >

                                    <?php elseif ($key === 'gambar'): ?>

                                        Belum ada gambar

                                    <?php else: ?>

                                        <?= e(
                                            mb_strimwidth(
                                                $row[$key] ?? '',
                                                0,
                                                70,
                                                '...'
                                            )
                                        ) ?>

                                    <?php endif; ?>

                                </td>

                            <?php endforeach; ?>

                            <td>
                                <?= e(
                                    $row['updated_at']
                                    ?? $row['created_at']
                                    ?? '-'
                                ) ?>
                            </td>

                            <td>
                                <div class="actions">

                                    <a
                                        class="btn small primary"
                                        href="<?= e($active_file) ?>?edit=<?= (int) $row['id'] ?>"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="post"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                    >

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="delete"
                                        >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int) $row['id'] ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn small danger"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td
                            colspan="<?= count($fields) + 3 ?>"
                            class="empty"
                        >
                            Belum ada data.
                            Silakan tambahkan melalui formulir di atas.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?php render_footer(); ?>
```