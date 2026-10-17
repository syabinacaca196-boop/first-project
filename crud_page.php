```php
<?php
require_once 'auth.php';
require_once 'layout.php';

$message = '';
$error = '';
$normalizedFields = [];
$csrfToken = $_SESSION['crud_csrf'] ??= bin2hex(random_bytes(32));

if (!preg_match('/^[a-z_]+$/', $table)) {
    http_response_code(400);
    exit('Tabel tidak valid.');
}

foreach ($fields as $key => $field) {
    if (!preg_match('/^[a-z_]+$/', $key)) {
        http_response_code(400);
        exit('Kolom tidak valid.');
    }

    $normalizedFields[$key] = is_array($field)
        ? $field + ['label' => $key, 'type' => 'text']
        : ['label' => $field, 'type' => $key === 'gambar' ? 'image' : 'text'];
}

$cleanupUpload = static function ($filename) use ($conn) {
    if (!$filename) {
        return;
    }

    $filename = basename($filename);
    $tables = ['beranda', 'jelajah', 'koleksi', 'galeri', 'artikel', 'kategori_koleksi'];

    foreach ($tables as $tableName) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM `$tableName` WHERE gambar = ?");
        if (!$stmt) {
            continue;
        }
        $stmt->bind_param('s', $filename);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            return;
        }
    }

    $path = __DIR__ . '/assets/uploads/' . $filename;
    if (is_file($path)) {
        unlink($path);
    }
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($csrfToken, $_POST['csrf_token'] ?? '')) {
        $error = 'Sesi formulir tidak valid. Muat ulang halaman lalu coba lagi.';
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = $conn->prepare("SELECT gambar FROM `$table` WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $existing = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM `$table` WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $cleanupUpload($existing['gambar'] ?? null);
            $message = 'Data berhasil dihapus.';
        } else {
            $error = 'Data tidak ditemukan atau gagal dihapus.';
        }
    } elseif (($_POST['action'] ?? '') === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $existing = null;

        if ($id > 0) {
            $stmt = $conn->prepare("SELECT * FROM `$table` WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $existing = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$existing) {
                $error = 'Data yang akan diedit tidak ditemukan.';
            }
        }

        $values = [];
        $newUploads = [];
        $oldUploads = [];

        foreach ($normalizedFields as $key => $field) {
            $type = $field['type'];

            if ($type === 'image') {
                $value = $existing[$key] ?? null;
                $upload = $_FILES[$key] ?? null;

                if (!empty($_POST['hapus_' . $key])) {
                    $oldUploads[] = $value;
                    $value = null;
                }

                if ($upload && $upload['error'] !== UPLOAD_ERR_NO_FILE) {
                    if ($upload['error'] !== UPLOAD_ERR_OK || $upload['size'] > 5 * 1024 * 1024) {
                        $error = 'Upload gagal. Ukuran gambar maksimal 5 MB.';
                    } else {
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $mime = $finfo->file($upload['tmp_name']);
                        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];

                        if (!isset($allowed[$mime])) {
                            $error = 'Format gambar harus JPG, PNG, GIF, atau WEBP.';
                        } else {
                            $folder = __DIR__ . '/assets/uploads/';
                            if (!is_dir($folder) && !mkdir($folder, 0755, true) && !is_dir($folder)) {
                                $error = 'Folder upload tidak dapat dibuat.';
                            } else {
                                $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
                                if (move_uploaded_file($upload['tmp_name'], $folder . $filename)) {
                                    $oldUploads[] = $existing[$key] ?? null;
                                    $newUploads[] = $filename;
                                    $value = $filename;
                                } else {
                                    $error = 'Gambar gagal disimpan.';
                                }
                            }
                        }
                    }
                }

                $values[$key] = $value;
                continue;
            }

            if ($type === 'checkbox') {
                $values[$key] = isset($_POST[$key]) ? 1 : 0;
                continue;
            }

            $defaultValue = $field['default'] ?? ($type === 'number' ? 0 : '');
            $value = trim((string) ($_POST[$key] ?? $defaultValue));
            if (!empty($field['required']) && $value === '') {
                $error = ($field['label'] ?? $key) . ' wajib diisi.';
            }

            if ($type === 'select' && $value !== '') {
                $options = $field['options'] ?? [];
                if (!array_key_exists($value, $options)) {
                    $error = 'Pilihan ' . ($field['label'] ?? $key) . ' tidak valid.';
                }
            }

            if ($type === 'number' && $value !== '' && filter_var($value, FILTER_VALIDATE_INT) === false) {
                $error = ($field['label'] ?? $key) . ' harus berupa bilangan bulat.';
            }

            if ($type === 'date' && $value !== '') {
                $date = DateTime::createFromFormat('Y-m-d', $value);
                if (!$date || $date->format('Y-m-d') !== $value) {
                    $error = 'Format tanggal tidak valid.';
                }
            }

            if ($type === 'email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format email tidak valid.';
            }

            if ($type === 'url' && $value !== '') {
                $urlParts = parse_url($value);
                if (!filter_var($value, FILTER_VALIDATE_URL) || !in_array(strtolower($urlParts['scheme'] ?? ''), ['http', 'https'], true)) {
                    $error = 'Tautan harus menggunakan URL HTTP atau HTTPS yang valid.';
                }
            }

            if ($key === 'slug' && $value !== '' && !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value)) {
                $error = 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung.';
            }

            if ($type === 'time' && $value !== '' && !preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $value)) {
                $error = 'Format jam harus HH:MM.';
            }

            if ($type === 'number' && $value !== '') {
                $minimum = (int) ($field['min'] ?? 0);
                if ((int) $value < $minimum) {
                    $error = ($field['label'] ?? $key) . ' tidak boleh kurang dari ' . $minimum . '.';
                }
                $value = (int) $value;
            }

            if ($type === 'select' && ($field['value_type'] ?? '') === 'int' && $value !== '') {
                $value = (int) $value;
            }

            if (($type === 'date' || $type === 'number' || ($type === 'select' && ($field['value_type'] ?? '') === 'int')) && $value === '') {
                $value = null;
            }

            $values[$key] = $value;
        }

        if ($error === '') {
            if ($id > 0) {
                $sets = array_map(static fn($key) => "`$key` = ?", array_keys($values));
                $sql = "UPDATE `$table` SET " . implode(', ', $sets) . ', updated_at = NOW() WHERE id = ?';
                $params = array_values($values);
                $params[] = $id;
            } else {
                $columns = array_keys($values);
                $sql = "INSERT INTO `$table` (`" . implode('`, `', $columns) . '`) VALUES (' . implode(', ', array_fill(0, count($columns), '?')) . ')';
                $params = array_values($values);
            }

            $types = '';
            foreach (array_keys($values) as $key) {
                $fieldType = $normalizedFields[$key]['type'];
                $types .= $fieldType === 'number' || $fieldType === 'checkbox' || ($fieldType === 'select' && ($normalizedFields[$key]['value_type'] ?? '') === 'int') ? 'i' : 's';
            }
            if ($id > 0) {
                $types .= 'i';
            }

            try {
                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$params);
                if ($stmt->execute()) {
                    foreach ($oldUploads as $oldUpload) {
                        $cleanupUpload($oldUpload);
                    }
                    $message = 'Data berhasil disimpan.';
                } else {
                    $error = 'Data gagal disimpan. Periksa kembali data yang dimasukkan.';
                }
            } catch (mysqli_sql_exception $exception) {
                $error = 'Data gagal disimpan. Pastikan nilai unik dan data sesuai format.';
            }

            if ($error !== '') {
                foreach ($newUploads as $newUpload) {
                    $cleanupUpload($newUpload);
                }
            }
        } else {
            foreach ($newUploads as $newUpload) {
                $cleanupUpload($newUpload);
            }
        }
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM `$table` WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}

$result = $conn->query("SELECT * FROM `$table` ORDER BY sort_order ASC, id DESC");

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

            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

            <input
                type="hidden"
                name="id"
                value="<?= e($edit['id'] ?? '') ?>"
            >

            <?php foreach ($normalizedFields as $key => $field): ?>
                <?php $type = $field['type']; ?>
                <div class="form-field <?= in_array($type, ['textarea', 'image'], true) ? 'form-field-wide' : '' ?>">
                    <label for="content-<?= e($key) ?>">
                        <?= e($field['label']) ?>
                        <?= !empty($field['required']) ? '*' : '' ?>
                    </label>

                <?php if ($type === 'image'): ?>
                    <?php if (!empty($edit[$key]) && is_file(__DIR__ . '/assets/uploads/' . basename($edit[$key]))): ?>
                        <div class="current-image">
                            <img
                                class="content-image"
                                src="assets/uploads/<?= e(basename($edit[$key])) ?>"
                                alt="Gambar saat ini"
                            >
                            <span>Gambar tersimpan. Unggah file baru untuk menggantinya.</span>
                        </div>
                    <?php endif; ?>
                    <input
                        id="content-<?= e($key) ?>"
                        type="file"
                        name="<?= e($key) ?>"
                        accept=".jpg,.jpeg,.png,.gif,.webp"
                    >
                    <?php if (!empty($edit[$key])): ?>
                        <label class="checkbox-field">
                            <input type="checkbox" name="hapus_<?= e($key) ?>" value="1">
                            Hapus gambar tersimpan
                        </label>
                    <?php endif; ?>
                    <small class="field-help">JPG, PNG, GIF, atau WEBP. Maksimal 5 MB.</small>
                <?php elseif ($type === 'textarea'): ?>
                    <textarea
                        id="content-<?= e($key) ?>"
                        name="<?= e($key) ?>"
                        rows="<?= (int) ($field['rows'] ?? 4) ?>"
                        <?= !empty($field['required']) ? 'required' : '' ?>
                    ><?= e($edit[$key] ?? '') ?></textarea>
                <?php elseif ($type === 'checkbox'): ?>
                    <label class="checkbox-field">
                        <input
                            id="content-<?= e($key) ?>"
                            type="checkbox"
                            name="<?= e($key) ?>"
                            value="1"
                            <?= isset($edit[$key]) ? (!empty($edit[$key]) ? 'checked' : '') : (!empty($field['default']) ? 'checked' : '') ?>
                        >
                        <?= e($field['checked_label'] ?? 'Aktif') ?>
                    </label>
                <?php elseif ($type === 'select'): ?>
                    <select id="content-<?= e($key) ?>" name="<?= e($key) ?>" <?= !empty($field['required']) ? 'required' : '' ?>>
                        <?php if (empty($field['required'])): ?><option value="">Pilih <?= e(strtolower($field['label'])) ?></option><?php endif; ?>
                        <?php foreach (($field['options'] ?? []) as $optionValue => $optionLabel): ?>
                            <option value="<?= e($optionValue) ?>" <?= (string) ($edit[$key] ?? '') === (string) $optionValue ? 'selected' : '' ?>><?= e($optionLabel) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <input
                        id="content-<?= e($key) ?>"
                        type="<?= e(in_array($type, ['date', 'time', 'number', 'email', 'url', 'tel'], true) ? $type : 'text') ?>"
                        name="<?= e($key) ?>"
                        value="<?= e($edit[$key] ?? ($field['default'] ?? ($type === 'number' ? 0 : ''))) ?>"
                        <?= !empty($field['required']) ? 'required' : '' ?>
                        <?= isset($field['min']) ? 'min="' . (int) $field['min'] . '"' : '' ?>
                        <?= $type === 'number' ? 'step="1"' : '' ?>
                        placeholder="Masukkan <?= e(strtolower($field['label'])) ?>"
                    >
                <?php endif; ?>
                </div>
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

        <table class="crud-table">

            <thead>
                <tr>
                    <th>No</th>

                    <?php foreach ($normalizedFields as $field): ?>
                        <th><?= e($field['label']) ?></th>
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

                            <?php foreach ($normalizedFields as $key => $field): ?>
                                <td class="<?= $field['type'] === 'image' ? 'image-cell' : 'text-cell' ?>">
                                    <?php if ($field['type'] === 'image' && !empty($row[$key]) && is_file(__DIR__ . '/assets/uploads/' . basename($row[$key]))): ?>
                                        <img
                                            class="content-image"
                                            src="assets/uploads/<?= e(basename($row[$key])) ?>"
                                            alt="Gambar konten"
                                        >
                                    <?php elseif ($field['type'] === 'image'): ?>
                                        Belum ada gambar
                                    <?php elseif ($field['type'] === 'checkbox'): ?>
                                        <?= !empty($row[$key]) ? e($field['checked_label'] ?? 'Aktif') : 'Tidak' ?>
                                    <?php elseif ($field['type'] === 'select'): ?>
                                        <?= e($field['options'][$row[$key] ?? ''] ?? $row[$key] ?? '-') ?>
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

                            <td class="date-cell">
                                <?= e(
                                    $row['updated_at']
                                    ?? $row['created_at']
                                    ?? '-'
                                ) ?>
                            </td>

                            <td class="action-cell">
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

                                        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

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
                            colspan="<?= count($normalizedFields) + 3 ?>"
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