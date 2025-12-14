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
<h2 style="margin: 10px 0;">🎬 Admin Panel - Nonton.in</h2>
<nav>
<?php if(isset($_SESSION['user_id'])): ?>
    <a href="home.php">Home</a>
    <a href="movies.php">Film</a>
    <a href="series.php">Series</a>
    <a href="episodes.php">Episodes</a>
    <a href="search.php">Search OMDB</a>
    <a href="../user/dashboard.php" style="color: #999;">👥 View as User</a>
    <a href="../logout.php" onclick="return confirm('Yakin ingin logout?')" style="float: right; color: #ff6b35;">Logout</a>
    <span style="float: right; color: #8b92a8; margin-right: 20px;">
        Logged: <strong style="color: #00ffcc;"><?= htmlspecialchars($_SESSION['email'] ?? 'Admin', ENT_QUOTES) ?></strong>
    </span>
<?php else: ?>
    <a href="../auth.php">Login</a>
<?php endif; ?>

<script>
function logout() {
    if (confirm('Yakin ingin logout?')) {
        <?php $_SESSION = array(); session_destroy(); ?>
        window.location.href = '../auth/login.php';
    }
}
</script>
</nav>
<hr>
