<?php
require '../config/api.php';
require '../templates/header.php';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$userId) {
    echo '<div class="card">Silakan login untuk melihat riwayat.</div>';
} else {
    $history = apiRequest("/history/read?user_id=".$userId);
    if (!is_array($history) || empty($history)) {
        echo '<div class="card">Belum ada riwayat.</div>';
    } else {
        foreach($history as $h):
?>
<div class="card">
    <?= $h['content_type'] ?> ID <?= $h['content_id'] ?> |
    Posisi: <?= $h['last_position'] ?> detik
</div>
<?php
        endforeach;
    }
}
?>
<?php require '../templates/footer.php'; ?>
