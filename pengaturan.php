<?php
require_once 'auth.php'; require_once 'layout.php';
render_header('','Pengaturan');
?>
<div class="panel"><h2>Pengaturan Admin</h2><p>Panel dasar Museum Bandar Cimanuk.</p><p>Untuk keamanan, ganti password awal setelah login dan jangan gunakan akun demo di website publik.</p><a class="btn primary" href="logout.php">Logout</a></div>
<?php render_footer(); ?>