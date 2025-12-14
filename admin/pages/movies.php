<?php
require '../config/api.php';
require '../templates/header.php';

$movies = apiRequest("/movies/read");
if (!is_array($movies) || empty($movies)) {
    echo '<div class="card">Belum ada data film.</div>';
} else {
    foreach($movies as $m):
?>
<div class="card">
    <b><?= $m['title'] ?></b><br>
    <?= $m['description'] ?><br>
    <!-- Implementasi favorit via API is recommended on details page -->
</div>
<?php
    endforeach;
}
?>
<?php require '../templates/footer.php'; ?>
