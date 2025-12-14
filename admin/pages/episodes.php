<?php
require '../config/api.php';
require '../templates/header.php';

$seriesId = isset($_GET['id']) ? $_GET['id'] : null;
if (!$seriesId) {
    echo '<div class="card">Series ID tidak ditemukan.</div>';
} else {
    $episodes = apiRequest("/episodes/read?series_id=".$seriesId);
    if (!is_array($episodes) || empty($episodes)) {
        echo '<div class="card">Episode belum tersedia.</div>';
    } else {
        foreach($episodes as $e):
?>
<div class="card">
    S<?= $e['season'] ?>E<?= $e['episode_number'] ?> - <?= $e['title'] ?>
</div>
<?php
        endforeach;
    }
}
?>
<?php require '../templates/footer.php'; ?>
