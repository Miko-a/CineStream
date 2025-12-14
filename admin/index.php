<?php
// Landing page for CineStream frontend
// Try to include header template if available
@include __DIR__ . '/templates/header.php';

// Detect login state (from session or localStorage hint)
session_start();
$userId = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
?>

<style>
	body { font-family: system-ui, Arial, sans-serif; margin: 20px; }
	.grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
	.card { border: 1px solid #ddd; border-radius: 8px; padding: 16px; }
	.title { display:flex; align-items:center; justify-content:space-between; }
	.title h1 { margin: 0; font-size: 22px; }
	.actions a { margin-right: 8px; }
	.muted { color: #666; font-size: 12px; }
</style>

<div class="title">
	<h1>CineStream</h1>
	<div class="actions">
		<?php if($userId): ?>
			<span class="muted">Logged in as #<?= $userId ?></span>
			<a href="logout.php">Logout</a>
		<?php else: ?>
			<a href="auth/login.php">Login</a>
		<?php endif; ?>
	</div>
</div>

<p class="muted">Cari film/series dari OMDB, simpan ke database, tandai favorit, dan lihat riwayat.</p>

<div class="grid">
	<div class="card">
		<h3>Search</h3>
		<p>Cari film atau series via OMDB API.</p>
		<a href="pages/search.php">Buka pencarian</a>
	</div>

	<div class="card">
		<h3>Home</h3>
		<p>Tampilan umum atau rekomendasi (opsional).</p>
		<a href="pages/home.php">Ke Home</a>
	</div>

	<div class="card">
		<h3>Movies</h3>
		<p>Data film yang tersimpan di database.</p>
		<a href="pages/movies.php">Lihat Movies</a>
	</div>

	<div class="card">
		<h3>Series</h3>
		<p>Data series yang tersimpan di database.</p>
		<a href="pages/series.php">Lihat Series</a>
	</div>

	<div class="card">
		<h3>Favorites</h3>
		<p>Koleksi favorit Anda.</p>
		<a href="pages/favorites.php">Lihat Favorit</a>
	</div>

	<div class="card">
		<h3>History</h3>
		<p>Riwayat tontonan.</p>
		<a href="pages/history.php">Lihat Riwayat</a>
	</div>

	<div class="card">
		<h3>Episodes</h3>
		<p>Daftar episode (jika series).</p>
		<a href="pages/episodes.php">Lihat Episodes</a>
	</div>
</div>

<script>
// If session isn't set, we may rely on localStorage set by login page
// This snippet only displays a hint; actual auth flow should set PHP session
(function() {
	try {
		var el = document.querySelector('.muted');
		if (el && !<?= $userId ? 'true' : 'false' ?>) {
			var lsId = localStorage.getItem('user_id');
			if (lsId) {
				el.textContent = 'Logged in (local) as #' + lsId + ' — refresh to sync session';
			}
		}
	} catch(e) {}
})();
</script>

<?php
// Footer template if available
@include __DIR__ . '/templates/footer.php';
?>
