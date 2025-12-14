<?php
require '../config/api.php';
require '../templates/header.php';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$userId) {
    echo '<div class="card">Silakan login untuk melihat favorit.</div>';
} else {
    $fav = apiRequest("/favorites/read?user_id=".$userId);
    if (!is_array($fav) || empty($fav)) {
        echo '<div class="card">Belum ada favorit.</div>';
    } else {
        foreach($fav as $f):
?>
<div class="card">
    <?= strtoupper($f['content_type']) ?> ID: <?= $f['content_id'] ?>
</div>
<?php
        endforeach;
    }
}
?>
<?php require '../templates/footer.php'; ?>
