<!DOCTYPE html>
<html>
<head>
    <title>Streaming Film</title>
    <style>
        body { font-family: Arial; background:#111; color:#fff }
        a { color:#00ffcc; margin-right:10px }
        .card { border:1px solid #444; padding:10px; margin:10px 0 }
    </style>
</head>
<body>
<h2>🎬 Website Streaming Film</h2>
<nav>
<?php if(isset($_SESSION['user_id'])): ?>
    <a href="home.php">Home</a>
    <a href="movies.php">Film</a>
    <a href="series.php">Series</a>
    <a href="favorites.php">Favorit</a>
    <a href="history.php">History</a>
    <a href="../logout.php">Logout</a>
<?php endif; ?>
</nav>
<hr>
