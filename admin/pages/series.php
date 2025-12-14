<?php
require '../config/api.php';
require '../templates/header.php';

$series = apiRequest("/series/read");
if (!is_array($series) || empty($series)) {
    echo '<div class="card">Belum ada data series.</div>';
} else {
    foreach($series as $s):
?>
<div class="card">
    <b><?= $s['title'] ?></b><br>
    <a href="episodes.php?id=<?= $s['series_id'] ?>">Lihat Episode</a>
</div>
<?php
    endforeach;
}
?>
<?php require '../templates/footer.php'; ?>
