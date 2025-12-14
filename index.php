<?php
// Use relative paths from project root
require __DIR__ . '/admin/config/omdb.php';
require __DIR__ . '/admin/config/api.php';

$q = $_GET['q'] ?? 'Indonesia';

// Prefer helper function to fetch OMDB data
$result = omdbSearch($q);
$movies = [];
if (is_array($result) && ($result['Response'] ?? 'False') === 'True' && isset($result['Search'])) {
    $movies = $result['Search'];
}

// Fetch movies & series from local database API
$moviesDb = apiRequest('/movies/read.php');
if (!is_array($moviesDb)) { $moviesDb = []; }

$seriesDb = apiRequest('/series/read.php');
if (!is_array($seriesDb)) { $seriesDb = []; }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nonton.in - Streaming Film Terbaik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <i class="fas fa-play-circle"></i> Nonton.in
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home" onclick="setActive(this)">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#film" onclick="setActive(this)">Film</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#series" onclick="setActive(this)">Series</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kategori" onclick="setActive(this)">Kategori</a>
                    </li>
                </ul>
                <div class="search-bar">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari film atau series..."
                        onkeyup="if(event.key==='Enter') searchMovies()" value="<?= htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES) ?>">
                    <i class="fas fa-search" onclick="searchMovies()"></i>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Nonton Film Indonesia Favorit</h1>
                <p class="hero-description">Ribuan film Indonesia berkualitas HD siap menemani waktu santai kamu.
                    Streaming tanpa batas, tanpa iklan.</p>
                <button class="btn btn-watch" onclick="scrollToSection('film')">
                    <i class="fas fa-play"></i> Tonton Sekarang
                </button>
                <button class="btn btn-info"
                    onclick="alert('Info: Nonton.in menyediakan streaming film Indonesia berkualitas HD dan 4K!')">
                    <i class="fas fa-info-circle"></i> Info Lengkap
                </button>
            </div>
        </div>
    </section>

    <!-- Trending Section -->
    <section class="container" id="film" style="margin-top: 80px;">
        <h2 class="section-title">
            <i class="fas fa-fire"></i> Film Trending
        </h2>

        <div class="row g-4" id="trendingMovies">
            <?php if (empty($moviesDb)): ?>
                <div class="col-12 text-secondary">Belum ada data film.</div>
            <?php else: ?>
                <?php foreach ($moviesDb as $m): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100">
                        <?php if (!empty($m['video_url']) && $m['video_url'] !== 'placeholder.mp4'): ?>
                        <img src="<?= htmlspecialchars($m['video_url'], ENT_QUOTES) ?>" class="card-img-top" alt="<?= htmlspecialchars($m['title'] ?? 'Film', ENT_QUOTES) ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                        <div class="card-img-top bg-secondary" style="height: 200px; display:flex; align-items:center; justify-content:center; color:#fff;">No Image</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($m['title'] ?? 'Tanpa judul', ENT_QUOTES) ?></h5>
                            <p class="card-text text-truncate"><?= htmlspecialchars($m['description'] ?? '', ENT_QUOTES) ?></p>
                            <?php if (!empty($m['release_year'])): ?>
                            <span class="badge bg-dark"><?= htmlspecialchars($m['release_year'], ENT_QUOTES) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Series Section -->
    <section class="container" id="series" style="margin-top: 80px;">
        <h2 class="section-title">
            <i class="fas fa-tv"></i> Series  Populer
        </h2>

        <div class="row g-4" id="seriesContent">
            <?php if (empty($seriesDb)): ?>
                <div class="col-12 text-secondary">Belum ada data series.</div>
            <?php else: ?>
                <?php foreach ($seriesDb as $s): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100">
                        <?php if (!empty($s['thumbnail_url'])): ?>
                        <img src="<?= htmlspecialchars($s['thumbnail_url'], ENT_QUOTES) ?>" class="card-img-top" alt="<?= htmlspecialchars($s['title'] ?? 'Series', ENT_QUOTES) ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                        <div class="card-img-top bg-secondary" style="height: 200px; display:flex; align-items:center; justify-content:center; color:#fff;">No Image</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($s['title'] ?? 'Tanpa judul', ENT_QUOTES) ?></h5>
                            <p class="card-text text-truncate"><?= htmlspecialchars($s['description'] ?? '', ENT_QUOTES) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Category Section -->
    <section class="container" id="kategori">
        <h2 class="section-title">
            <i class="fas fa-th-large"></i> Kategori Film
        </h2>

        <div class="category-pills">
            <div class="category-pill active" onclick="filterCategory('Semua')">Semua</div>
            <div class="category-pill" onclick="filterCategory('Drama')">Drama</div>
            <div class="category-pill" onclick="filterCategory('Komedi')">Komedi</div>
            <div class="category-pill" onclick="filterCategory('Horror')">Horror</div>
            <div class="category-pill" onclick="filterCategory('Romance')">Romance</div>
            <div class="category-pill" onclick="filterCategory('Action')">Action</div>
        </div>

        <div class="row g-4" id="categoryMovies">
        </div>
    </section>

    <!-- Movie Detail Modal -->
    <div class="modal fade" id="movieModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img id="modalPoster" class="movie-detail-poster" src="" alt="Movie Poster">
                        </div>
                        <div class="col-md-8">
                            <div class="movie-detail-info" id="modalInfo">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-watch">
                        <i class="fas fa-play"></i> Tonton Sekarang
                    </button>
                    <button type="button" class="btn btn-info" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="footer-title">
                        <i class="fas fa-play-circle"></i> Nonton.in
                    </h5>
                    <p class="text-secondary">Platform streaming film Indonesia terbaik. Nonton film dan series favorit
                        kamu kapan saja, dimana saja.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h5 class="footer-title">Navigasi</h5>
                    <a href="#home" class="footer-link">Beranda</a>
                    <a href="#film" class="footer-link">Film</a>
                    <a href="#series" class="footer-link">Series</a>
                    <a href="#kategori" class="footer-link">Kategori</a>
                </div>
                <div class="col-md-3">
                    <h5 class="footer-title">Informasi</h5>
                    <a href="#" class="footer-link">Tentang Kami</a>
                    <a href="#" class="footer-link">Syarat & Ketentuan</a>
                    <a href="#" class="footer-link">Kebijakan Privasi</a>
                    <a href="#" class="footer-link">Kontak</a>
                </div>
                <div class="col-md-3">
                    <h5 class="footer-title">Dukungan</h5>
                    <a href="#" class="footer-link">Pusat Bantuan</a>
                    <a href="#" class="footer-link">FAQ</a>
                    <a href="#" class="footer-link">Cara Menonton</a>
                    <a href="#" class="footer-link">Langganan</a>
                </div>
            </div>
            <hr style="border-color: rgba(255, 107, 53, 0.2); margin: 2rem 0;">
            <div class="text-center text-secondary">
                <p>&copy; Nonton.in. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- <script>
        function searchMovies() {
            const query = document.getElementById('searchInput').value.trim();
            if (query === '') {
                alert('Masukkan judul film atau series');
                return;
            }
            window.location.href = '?q=' + encodeURIComponent(query);
        }

        function setActive(el) {
            // Remove active class from all nav links
            document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            // Add active class to clicked element
            el.classList.add('active');
        }

        function scrollToSection(id) {
            const element = document.getElementById(id);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function filterCategory(category) {
            // Update active category pill
            document.querySelectorAll('.category-pill').forEach(pill => {
                pill.classList.remove('active');
            });
            event.target.classList.add('active');
            // Placeholder: could filter movies by category
            alert('Filter by ' + category + ' (fitur akan datang)');
        }
    </script> -->
</body>

</html>