<?php
require '../config/omdb.php';
require '../config/api.php';
require '../templates/header.php';

$data = omdbDetail($_GET['id']);
?>

<div class="card">
    <img src="<?= $data['Poster'] ?>" width="200"><br>
    <h3><?= $data['Title'] ?></h3>
    <p><?= $data['Plot'] ?></p>
    <p>Genre: <?= $data['Genre'] ?></p>

    <form method="POST" style="margin-bottom:8px; display:flex; gap:8px; flex-wrap:wrap;">
        <button name="save_movie">Simpan sebagai Film</button>
        <?php if (strtolower($data['Type'] ?? '') === 'series'): ?>
            <button name="save_series">Simpan sebagai Series</button>
        <?php endif; ?>
    </form>
</div>

<?php
if(isset($_POST['save_movie'])){
    $resp = apiRequest("/movies/create.php","POST",[
        "title" => $data['Title'],
        "description" => $data['Plot'],
        "release_year" => substr($data['Year'],0,4),
        "duration" => 0,
        "video_url" => $data['Poster'] ?? 'placeholder.mp4'
    ]);
    echo "✔ Film disimpan";
}

if(isset($_POST['save_series']) && strtolower($data['Type'] ?? '') === 'series'){
    $resp = apiRequest("/series/create.php","POST",[
        "title" => $data['Title'],
        "description" => $data['Plot'],
        "release_year" => substr($data['Year'],0,4),
        "thumbnail_url" => $data['Poster'] ?? ''
    ]);
    echo "✔ Series disimpan";
}
?>

<?php require '../templates/footer.php'; ?>
