<?php
require '../config/omdb.php';
require '../templates/header.php';

if(isset($_GET['q'])){
    $result = omdbSearch($_GET['q']);
}
?>

<form method="GET">
    <input name="q" placeholder="Cari film / series...">
    <button>Cari</button>
</form>

<?php if(isset($result) && ($result['Response'] ?? 'False') !== 'True'): ?>
<div class="card">Error: <?= htmlspecialchars($result['Error'] ?? 'Tidak ada hasil', ENT_QUOTES) ?></div>
<?php elseif(isset($result['Search'])): ?>
<?php foreach($result['Search'] as $m): ?>
<div class="card">
    <img src="<?= $m['Poster'] ?>" width="100"><br>
    <b><?= $m['Title'] ?></b> (<?= $m['Year'] ?>)<br>
    <a href="details.php?id=<?= $m['imdbID'] ?>">Detail</a>
</div>
<?php endforeach; ?>
<?php elseif(isset($_GET['q'])): ?>
<div class="card">Tidak ada hasil untuk "<?= htmlspecialchars($_GET['q'], ENT_QUOTES) ?>"</div>
<?php endif; ?>

<?php require '../templates/footer.php'; ?>
