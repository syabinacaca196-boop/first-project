<?php
require_once 'auth.php'; require_once 'layout.php';
render_header('dashboard.php','Dashboard');
$tables = ['beranda'=>'Beranda','jelajah'=>'Jelajah','koleksi'=>'Koleksi','galeri'=>'Galeri','artikel'=>'Artikel','kontak'=>'Kontak'];
$colors=['blue','green','orange','purple','red','teal'];
$counts=[];
foreach($tables as $t=>$label){$r=$conn->query("SELECT COUNT(*) AS total FROM `$t`");$counts[$t]=(int)($r?$r->fetch_assoc()['total']:0);}
?>
<div class="stats-grid">
<?php $i=0; foreach($tables as $t=>$label): ?><a class="stat-card <?= $colors[$i++] ?>" href="<?= e($t==='beranda'?'beranda.php':$t.'.php') ?>"><div class="stat-icon"><?= ['⌂','◉','⬡','▧','▤','☎'][$i-1] ?></div><div><?= e($label) ?></div><strong><?= $counts[$t] ?></strong><small>Data</small></a><?php endforeach; ?>
</div>
<div class="panel"><div class="panel-head"><div><h2>Data Terbaru</h2><p>Daftar konten yang telah ditambahkan atau diperbarui.</p></div><a class="btn primary" href="beranda.php">＋ Kelola Data</a></div>
<div class="table-wrap"><table><thead><tr><th>Bagian</th><th>Jumlah Data</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($tables as $t=>$label): ?><tr><td><?= e($label) ?></td><td><?= $counts[$t] ?></td><td><a class="btn small primary" href="<?= e($t.'.php') ?>">Kelola</a></td></tr><?php endforeach; ?>
</tbody></table></div></div>
<?php render_footer(); ?>