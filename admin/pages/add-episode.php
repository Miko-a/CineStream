<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
    header("Location: ../../auth.php");
    exit;
}

require '../config/api.php';
require '../config/omdb.php';

$seriesId = isset($_GET['series_id']) ? $_GET['series_id'] : null;
$series = null;
$error = '';
$success = '';
$omdbResults = [];
$selectedEpisode = null;

// Get series details
if ($seriesId) {
    $allSeries = apiRequest("/series/read.php");
    if (is_array($allSeries)) {
        foreach ($allSeries as $s) {
            if ($s['series_id'] == $seriesId) {
                $series = $s;
                break;
            }
        }
    }
}

// Handle OMDB search by series title or episode title
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_omdb'])) {
    $searchQuery = $_POST['search_query'] ?? '';
    if (!empty($searchQuery)) {
        $result = omdbSearch($searchQuery);
        if (is_array($result) && ($result['Response'] ?? 'False') === 'True' && isset($result['Search'])) {
            // Prefer episodes type if available, else show all
            $episodesOnly = array_filter($result['Search'], function($item){
                return ($item['Type'] ?? '') === 'episode';
            });
            $omdbResults = !empty($episodesOnly) ? array_values($episodesOnly) : $result['Search'];
        } else {
            $error = 'Episode tidak ditemukan di OMDB';
            $omdbResults = [];
        }
    } else {
        $error = 'Masukkan judul episode atau series untuk mencari';
    }
}

// Handle select episode from OMDB (by imdbid)
if (isset($_GET['imdbid'])) {
    $detail = omdbDetail($_GET['imdbid']);
    if (($detail['Response'] ?? 'False') === 'True') {
        $selectedEpisode = $detail;
    } else {
        $error = 'Gagal mengambil detail episode dari OMDB';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['search_omdb'])) {
    $season = $_POST['season'] ?? '';
    $episode_number = $_POST['episode_number'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $video_url = $_POST['video_url'] ?? '';

    if (empty($title)) {
        $error = 'Judul episode harus diisi!';
    } else {
        $result = apiRequest('/episodes/create.php', 'POST', [
            'series_id' => $seriesId,
            'season' => intval($season),
            'episode_number' => intval($episode_number),
            'title' => $title,
            'description' => $description,
            'video_url' => $video_url
        ]);
        $success = 'Episode berhasil ditambahkan!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Episode - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-orange: #ff6b35;
            --dark-bg: #0f1419;
            --card-bg: #1a1f2e;
            --hover-bg: #252b3b;
            --text-primary: #ffffff;
            --navbar-bg: #141922;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-navbar {
            background-color: var(--navbar-bg);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            border-bottom: 2px solid rgba(255, 107, 53, 0.3);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-orange) !important;
            text-shadow: 0 0 20px rgba(255, 107, 53, 0.5);
        }

        .btn-back {
            background: transparent;
            border: 2px solid var(--text-secondary);
            color: var(--text-secondary);
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-back:hover {
            border-color: var(--primary-orange);
            color: var(--primary-orange);
        }

        .page-header {
            margin-top: 5rem;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .form-container {
            background-color: var(--card-bg);
            border: 2px solid rgba(255, 107, 53, 0.2);
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.7rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid rgba(255, 107, 53, 0.2);
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
            font-family: inherit;
            transition: all 0.3s;
            box-sizing: border-box;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: var(--text-secondary);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--primary-orange);
            background-color: rgba(255, 107, 53, 0.05);
            outline: none;
            box-shadow: 0 0 10px rgba(255, 107, 53, 0.3);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-orange) 0%, #ff8f66 100%);
            color: white;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.4);
        }

        .form-footer {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .alert {
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.2);
            border: 2px solid #4caf50;
            color: #a8e6a1;
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.2);
            border: 2px solid #dc3545;
            color: #f8a5a5;
        }

        .success-message a {
            color: var(--primary-orange);
            text-decoration: none;
            font-weight: 600;
        }

        .success-message a:hover {
            text-decoration: underline;
        }

        .series-info {
            background: rgba(255, 107, 53, 0.1);
            border: 2px solid rgba(255, 107, 53, 0.3);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .series-info-text {
            color: var(--text-secondary);
            margin: 0;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Admin Navbar -->
    <nav class="admin-navbar fixed-top">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <a class="navbar-brand" href="../index.php">
                    <i class="fas fa-crown"></i> Admin Nonton.in
                </a>
                <div>
                    <a href="episodes.php?id=<?= urlencode($seriesId) ?>" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <?php if (!$series): ?>
            <div style="text-align: center; padding: 3rem; background-color: var(--card-bg); border: 2px solid rgba(255, 107, 53, 0.2); border-radius: 15px; margin-top: 4rem;">
                <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: var(--primary-orange); margin-bottom: 1rem; display: block;"></i>
                <p style="margin: 0;">Series tidak ditemukan</p>
                <a href="series.php" class="btn-back" style="margin-top: 1rem;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Series
                </a>
            </div>
        <?php else: ?>
            <div class="page-header">
                <div class="series-info">
                    <p class="series-info-text">
                        <i class="fas fa-info-circle"></i> Menambahkan episode untuk: <strong style="color: var(--primary-orange);"><?= htmlspecialchars($series['title'], ENT_QUOTES) ?></strong>
                    </p>
                </div>
                <h1 class="page-title">
                    <i class="fas fa-plus-circle"></i> Tambah Episode Baru
                </h1>
                <p class="text-secondary">Masukkan informasi episode yang ingin ditambahkan</p>
            </div>

            <div class="form-container">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error, ENT_QUOTES) ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success, ENT_QUOTES) ?>
                    </div>
                    <div class="success-message" style="background: rgba(255, 107, 53, 0.1); border: 2px solid var(--primary-orange); border-radius: 8px; padding: 1.5rem; text-align: center;">
                        <p style="margin-bottom: 1rem;">Episode berhasil ditambahkan ke database!</p>
                        <a href="episodes.php?id=<?= urlencode($seriesId) ?>" class="btn btn-primary" style="background: linear-gradient(135deg, var(--primary-orange) 0%, #ff8f66 100%); color: white; padding: 0.7rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Episode
                        </a>
                    </div>
                <?php else: ?>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="season">
                                        <i class="fas fa-layer-group"></i> Musim <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="number" id="season" name="season" min="1" placeholder="1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="episode_number">
                                        <i class="fas fa-hashtag"></i> Nomor Episode <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="number" id="episode_number" name="episode_number" min="1" placeholder="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title">
                                <i class="fas fa-heading"></i> Judul Episode <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="text" id="title" name="title" placeholder="Judul episode" required>
                        </div>

                        <div class="form-group">
                            <label for="description">
                                <i class="fas fa-align-left"></i> Deskripsi / Sinopsis
                            </label>
                            <textarea id="description" name="description" placeholder="Deskripsi episode..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="video_url">
                                <i class="fas fa-link"></i> URL Video
                            </label>
                            <input type="text" id="video_url" name="video_url" placeholder="https://example.com/video.mp4">
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save"></i> Simpan Episode
                            </button>
                            <a href="episodes.php?id=<?= urlencode($seriesId) ?>" class="btn-back">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>