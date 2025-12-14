<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth.php");
    exit;
}

// Redirect admin to admin panel
if (($_SESSION['role'] ?? 'user') === 'admin') {
    header("Location: ../admin/index.php");
    exit;
}

require __DIR__ . '/../admin/config/api.php';

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'] ?? '';

// Fetch user's favorite movies
$favorites = apiRequest('/favorites/read.php?user_id=' . $user_id);
if (!is_array($favorites)) { $favorites = []; }

// Fetch user's watching history
$history = apiRequest('/history/read.php?user_id=' . $user_id);
if (!is_array($history)) { $history = []; }

// Fetch all movies for recommendations
$allMovies = apiRequest('/movies/read.php');
if (!is_array($allMovies)) { $allMovies = []; }

// Fetch all series for recommendations
$allSeries = apiRequest('/series/read.php');
if (!is_array($allSeries)) { $allSeries = []; }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Nonton.in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <style>
        :root {
            --primary-orange: #ff6b35;
            --dark-bg: #0f1419;
            --card-bg: #1a1f2e;
            --hover-bg: #252b3b;
            --text-primary: #ffffff;
            --text-secondary: #8b92a8;
        }

        .user-navbar {
            background-color: var(--card-bg);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            border-bottom: 2px solid rgba(255, 107, 53, 0.3);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .user-nav-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-orange) !important;
            text-shadow: 0 0 20px rgba(255, 107, 53, 0.5);
        }

        .user-nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
            margin-left: auto;
        }

        .user-nav-links a, .user-nav-links span {
            color: var(--text-secondary) !important;
            transition: color 0.3s;
            cursor: pointer;
        }

        .user-nav-links a:hover, .user-nav-links span:hover {
            color: var(--primary-orange) !important;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-primary);
            padding-top: 80px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.2) 0%, rgba(15, 20, 25, 0.95) 100%);
            padding: 3rem 2rem;
            margin-bottom: 3rem;
            border-bottom: 2px solid rgba(255, 107, 53, 0.2);
        }

        .dashboard-header h1 {
            color: var(--primary-orange);
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .user-info {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 3rem 0 2rem;
        }

        .section-header h2 {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0;
            position: relative;
            display: inline-block;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-orange) 0%, transparent 100%);
            border-radius: 2px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: var(--card-bg);
            border-radius: 15px;
            border: 2px dashed rgba(255, 107, 53, 0.2);
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 3rem;
            color: rgba(255, 107, 53, 0.3);
            margin-bottom: 1rem;
        }

        .empty-state p {
            margin: 0;
        }

        .movie-card {
            background-color: var(--card-bg);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            border: 2px solid rgba(255, 107, 53, 0.2);
            height: 100%;
        }

        .movie-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 20px 50px rgba(255, 107, 53, 0.4);
            border-color: var(--primary-orange);
        }

        .movie-poster {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .movie-card:hover .movie-poster {
            transform: scale(1.1);
        }

        .movie-info {
            padding: 1.2rem;
        }

        .movie-title {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .movie-meta {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .user-nav-links {
                gap: 1rem;
            }

            .dashboard-header {
                padding: 2rem 1rem;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- User Navbar -->
    <nav class="user-navbar">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center">
                <a class="user-nav-brand" href="dashboard.php">
                    <i class="fas fa-play-circle"></i> Nonton.in
                </a>
                <div class="user-nav-links">
                    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                    <a href="profile.php" title="<?= htmlspecialchars($email, ENT_QUOTES) ?>">
                        <i class="fas fa-user-circle"></i> Profil
                    </a>
                    <a href="../logout.php" onclick="return confirm('Yakin ingin logout?')">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container-fluid">
            <h1><i class="fas fa-user"></i> Dashboard Saya</h1>
            <p class="user-info">Selamat datang kembali! 👋</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid px-4 pb-5">
        <!-- Favorit Section -->
        <div>
            <div class="section-header">
                <h2><i class="fas fa-heart"></i> Film Favorit Saya</h2>
            </div>

            <?php if (empty($favorites)): ?>
                <div class="empty-state">
                    <i class="fas fa-heart-broken"></i>
                    <p>Belum ada film favorit. <a href="../index.php" style="color: var(--primary-orange);">Cari film sekarang</a></p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($favorites as $fav): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="movie-card">
                                <div style="height: 250px; background: var(--hover-bg); display: flex; align-items: center; justify-content: center;">
                                    <span style="color: var(--text-secondary);">
                                        <?= strtoupper($fav['content_type'] ?? 'Film') ?> ID: <?= $fav['content_id'] ?>
                                    </span>
                                </div>
                                <div class="movie-info">
                                    <div class="movie-title">Favorit <?= strtoupper($fav['content_type'] ?? 'Film') ?></div>
                                    <div class="movie-meta">
                                        <i class="fas fa-plus-circle"></i> <?= date('d M Y', strtotime($fav['added_at'] ?? 'now')) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- History Section -->
        <div style="margin-top: 4rem;">
            <div class="section-header">
                <h2><i class="fas fa-history"></i> Riwayat Menonton</h2>
            </div>

            <?php if (empty($history)): ?>
                <div class="empty-state">
                    <i class="fas fa-play"></i>
                    <p>Belum ada riwayat. <a href="../index.php" style="color: var(--primary-orange);">Mulai menonton sekarang</a></p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach (array_slice($history, 0, 8) as $item): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="movie-card">
                                <div style="height: 250px; background: var(--hover-bg); display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 1rem;">
                                    <span style="color: var(--text-secondary); font-size: 0.9rem;">
                                        <?= strtoupper($item['content_type'] ?? 'Film') ?>
                                    </span>
                                    <div style="text-align: center;">
                                        <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                            Posisi: <strong><?= intval($item['last_position'] / 60) ?> menit</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="movie-info">
                                    <div class="movie-title">ID: <?= $item['content_id'] ?></div>
                                    <div class="movie-meta">
                                        <i class="fas fa-clock"></i> <?= date('d M Y', strtotime($item['watched_at'] ?? 'now')) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recommendations Section -->
        <div style="margin-top: 4rem;">
            <div class="section-header">
                <h2><i class="fas fa-sparkles"></i> Rekomendasi untuk Anda</h2>
            </div>

            <?php if (empty($allMovies) && empty($allSeries)): ?>
                <div class="empty-state">
                    <i class="fas fa-star"></i>
                    <p>Belum ada konten yang tersedia</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach (array_slice($allMovies, 0, 4) as $movie): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="movie-card">
                                <?php if (!empty($movie['video_url']) && $movie['video_url'] !== 'placeholder.mp4'): ?>
                                    <img src="<?= htmlspecialchars($movie['video_url'], ENT_QUOTES) ?>" class="movie-poster" alt="<?= htmlspecialchars($movie['title'], ENT_QUOTES) ?>">
                                <?php else: ?>
                                    <div class="movie-poster" style="background: var(--hover-bg); display: flex; align-items: center; justify-content: center; color: var(--text-secondary);">No Image</div>
                                <?php endif; ?>
                                <div class="movie-info">
                                    <div class="movie-title"><?= htmlspecialchars($movie['title'], ENT_QUOTES) ?></div>
                                    <div class="movie-meta">
                                        <i class="fas fa-calendar"></i> <?= htmlspecialchars($movie['release_year'], ENT_QUOTES) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php foreach (array_slice($allSeries, 0, 4) as $series): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="movie-card">
                                <?php if (!empty($series['thumbnail_url'])): ?>
                                    <img src="<?= htmlspecialchars($series['thumbnail_url'], ENT_QUOTES) ?>" class="movie-poster" alt="<?= htmlspecialchars($series['title'], ENT_QUOTES) ?>">
                                <?php else: ?>
                                    <div class="movie-poster" style="background: var(--hover-bg); display: flex; align-items: center; justify-content: center; color: var(--text-secondary);">No Image</div>
                                <?php endif; ?>
                                <div class="movie-info">
                                    <div class="movie-title"><?= htmlspecialchars($series['title'], ENT_QUOTES) ?></div>
                                    <div class="movie-meta">
                                        <i class="fas fa-tv"></i> Series
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
