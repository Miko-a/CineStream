<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
    header("Location: ../../auth.php");
    exit;
}

require '../config/api.php';
require '../config/omdb.php';

$error = '';
$success = '';
$omdbResults = [];
$selectedSeries = null;

// Handle OMDB search
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_omdb'])) {
    $searchQuery = $_POST['search_query'] ?? '';
    if (!empty($searchQuery)) {
        $omdbResults = omdbSearch($searchQuery);
        if (($omdbResults['Response'] ?? 'False') === 'True' && isset($omdbResults['Search'])) {
            // Filter hanya series
            $omdbResults = array_filter($omdbResults['Search'], function($item) {
                return ($item['Type'] ?? '') === 'series';
            });
            $omdbResults = array_values($omdbResults);
        } else {
            $error = 'Series tidak ditemukan di OMDB';
            $omdbResults = [];
        }
    } else {
        $error = 'Masukkan judul series yang ingin dicari';
    }
}

// Handle select series from OMDB
if (isset($_GET['imdbid'])) {
    $seriesDetail = omdbDetail($_GET['imdbid']);
    if (($seriesDetail['Response'] ?? 'False') === 'True') {
        $selectedSeries = $seriesDetail;
    } else {
        $error = 'Gagal mengambil detail series dari OMDB';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_series'])) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $thumbnail_url = $_POST['thumbnail_url'] ?? '';
    $genres = isset($_POST['genres']) ? array_filter($_POST['genres']) : [];

    if (empty($title)) {
        $error = 'Judul series harus diisi!';
    } else {
        $result = apiRequest('/series/create.php', 'POST', [
            'title' => $title,
            'description' => $description,
            'thumbnail_url' => $thumbnail_url,
            'genres' => $genres
        ]);
        $success = 'Series berhasil ditambahkan!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Series - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-orange: #ff6b35;
            --dark-bg: #0f1419;
            --card-bg: #1a1f2e;
            --hover-bg: #252b3b;
            --text-primary: #ffffff;
            --text-secondary: #8b92a8;
            --navbar-bg: #141922;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 80px;
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
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-back:hover {
            border-color: var(--primary-orange);
            color: var(--primary-orange);
        }

        .page-header {
            margin: 2rem 0;
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
            max-width: 800px;
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
                    <a href="series.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-plus-circle"></i> Tambah Series Baru
            </h1>
            <p class="text-secondary">Cari series dari OMDB atau masukkan data secara manual</p>
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
                    <p style="margin-bottom: 1rem;">Series berhasil ditambahkan ke database!</p>
                    <a href="series.php" class="btn btn-primary" style="background: linear-gradient(135deg, var(--primary-orange) 0%, #ff8f66 100%); color: white; padding: 0.7rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Series
                    </a>
                </div>
            <?php elseif ($selectedSeries): ?>
                <!-- Form with OMDB data -->
                <div style="background: rgba(255, 107, 53, 0.1); border: 2px solid rgba(255, 107, 53, 0.3); border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                    <p style="margin: 0; color: var(--primary-orange);">
                        <i class="fas fa-info-circle"></i> Data diambil dari OMDB
                    </p>
                </div>

                <form method="POST">
                    <input type="hidden" name="save_series" value="1">
                    
                    <div class="form-group">
                        <label for="title">
                            <i class="fas fa-heading"></i> Judul Series <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($selectedSeries['Title'] ?? '', ENT_QUOTES) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-align-left"></i> Deskripsi / Sinopsis
                        </label>
                        <textarea id="description" name="description"><?= htmlspecialchars($selectedSeries['Plot'] ?? '', ENT_QUOTES) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail_url">
                            <i class="fas fa-image"></i> URL Thumbnail / Poster
                        </label>
                        <input type="text" id="thumbnail_url" name="thumbnail_url" value="<?= htmlspecialchars($selectedSeries['Poster'] ?? '', ENT_QUOTES) ?>">
                    </div>

                    <div class="form-group">
                        <label for="genres">
                            <i class="fas fa-tag"></i> Genre
                        </label>
                        <div id="genreContainer" style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="text" id="newGenreInput" placeholder="Tambah genre baru..." style="flex: 1; padding: 0.8rem; border: 2px solid rgba(255, 107, 53, 0.2); border-radius: 8px; background-color: rgba(255, 255, 255, 0.05); color: var(--text-primary);">
                            <button type="button" id="addGenreBtn" class="btn-submit" style="width: auto;">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Simpan Series
                        </button>
                        <a href="add-series.php" class="btn-back">
                            <i class="fas fa-search"></i> Cari Series Lain
                        </a>
                        <a href="series.php" class="btn-back">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            <?php elseif (!empty($omdbResults)): ?>
                <!-- Search Results -->
                <div style="background: rgba(255, 107, 53, 0.1); border: 2px solid rgba(255, 107, 53, 0.3); border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                    <p style="margin: 0; color: var(--primary-orange);">
                        <i class="fas fa-search"></i> Ditemukan <?= count($omdbResults) ?> hasil pencarian series
                    </p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <?php foreach ($omdbResults as $series): ?>
                    <div style="background-color: rgba(255, 107, 53, 0.1); border: 2px solid rgba(255, 107, 53, 0.2); border-radius: 10px; overflow: hidden; transition: all 0.3s;">
                        <?php if ($series['Poster'] !== 'N/A'): ?>
                            <img src="<?= htmlspecialchars($series['Poster'], ENT_QUOTES) ?>" style="width: 100%; height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($series['Title'], ENT_QUOTES) ?>">
                        <?php else: ?>
                            <div style="width: 100%; height: 200px; background: rgba(255, 107, 53, 0.2); display: flex; align-items: center; justify-content: center; color: var(--primary-orange);">
                                <i class="fas fa-image" style="font-size: 2rem;"></i>
                            </div>
                        <?php endif; ?>
                        <div style="padding: 1rem;">
                            <h4 style="margin: 0 0 0.5rem 0; color: var(--text-primary);"><?= htmlspecialchars($series['Title'], ENT_QUOTES) ?></h4>
                            <p style="margin: 0 0 1rem 0; color: var(--text-secondary); font-size: 0.9rem;">
                                <i class="fas fa-calendar"></i> <?= htmlspecialchars($series['Year'], ENT_QUOTES) ?>
                                | <i class="fas fa-tag"></i> <?= htmlspecialchars($series['Type'], ENT_QUOTES) ?>
                            </p>
                            <a href="?imdbid=<?= urlencode($series['imdbID']) ?>" style="background: linear-gradient(135deg, var(--primary-orange) 0%, #ff8f66 100%); color: white; padding: 0.6rem 1rem; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; transition: all 0.3s;">
                                <i class="fas fa-arrow-right"></i> Pilih Series
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <a href="add-series.php" style="background: transparent; border: 2px solid var(--text-secondary); color: var(--text-secondary); padding: 0.7rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s;">
                    <i class="fas fa-search"></i> Cari Series Lain
                </a>
            <?php else: ?>
                <!-- Search Form -->
                <form method="POST" style="margin-bottom: 2rem;">
                    <div class="form-group">
                        <label for="search_query">
                            <i class="fas fa-search"></i> Cari Series di OMDB
                        </label>
                        <input type="text" id="search_query" name="search_query" placeholder="Masukkan judul series..." value="">
                    </div>
                    <div class="form-footer">
                        <button type="submit" name="search_omdb" value="1" class="btn-submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>

                <hr style="border-color: rgba(255, 107, 53, 0.2); margin: 2rem 0;">

                <h3 style="margin-top: 2rem; margin-bottom: 1rem;">atau Tambah Secara Manual</h3>

                <form method="POST">
                    <input type="hidden" name="save_series" value="1">
                    
                    <div class="form-group">
                        <label for="title">
                            <i class="fas fa-heading"></i> Judul Series <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" id="title" name="title" placeholder="Contoh: Breaking Bad" required>
                    </div>

                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-align-left"></i> Deskripsi / Sinopsis
                        </label>
                        <textarea id="description" name="description" placeholder="Ceritakan tentang series ini..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail_url">
                            <i class="fas fa-image"></i> URL Thumbnail / Poster
                        </label>
                        <input type="text" id="thumbnail_url" name="thumbnail_url" placeholder="https://example.com/thumbnail.jpg">
                    </div>

                    <div class="form-group">
                        <label for="genres">
                            <i class="fas fa-tag"></i> Genre
                        </label>
                        <div id="genreContainer" style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="text" id="newGenreInput" placeholder="Tambah genre baru..." style="flex: 1; padding: 0.8rem; border: 2px solid rgba(255, 107, 53, 0.2); border-radius: 8px; background-color: rgba(255, 255, 255, 0.05); color: var(--text-primary);">
                            <button type="button" id="addGenreBtn" class="btn-submit" style="width: auto;">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Simpan Series
                        </button>
                        <a href="series.php" class="btn-back">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedGenres = [];
        let availableGenres = [];

        // Fetch available genres on page load
        async function loadGenres() {
            try {
                const response = await fetch('/api/genres/read.php');
                const result = await response.json();
                if (result.success) {
                    availableGenres = result.genres;
                }
            } catch (error) {
                console.error('Error loading genres:', error);
            }
        }

        // Add genre
        document.getElementById('addGenreBtn').addEventListener('click', async function() {
            const input = document.getElementById('newGenreInput');
            const genreName = input.value.trim();

            if (!genreName) return;

            try {
                const response = await fetch('/api/genres/create.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name: genreName })
                });

                const result = await response.json();
                if (result.success) {
                    const genre = { genre_id: result.genre_id, name: genreName };
                    addGenreTag(genre);
                    input.value = '';
                }
            } catch (error) {
                alert('Error adding genre: ' + error.message);
            }
        });

        function addGenreTag(genre) {
            if (selectedGenres.some(g => g.genre_id === genre.genre_id)) return;

            selectedGenres.push(genre);
            const container = document.getElementById('genreContainer');
            
            const tag = document.createElement('div');
            tag.style.cssText = 'background: rgba(255, 107, 53, 0.3); border: 1px solid var(--primary-orange); color: var(--primary-orange); padding: 0.5rem 1rem; border-radius: 20px; display: flex; align-items: center; gap: 0.5rem;';
            tag.innerHTML = `
                ${genre.name}
                <input type="hidden" name="genres" value="${genre.genre_id}">
                <button type="button" class="remove-genre" style="background: none; border: none; color: var(--primary-orange); cursor: pointer; padding: 0; font-size: 1.2rem;" title="Hapus">
                    <i class="fas fa-times"></i>
                </button>
            `;

            tag.querySelector('.remove-genre').addEventListener('click', function() {
                selectedGenres = selectedGenres.filter(g => g.genre_id !== genre.genre_id);
                tag.remove();
            });

            container.appendChild(tag);
        }

        // Load genres when page loads
        document.addEventListener('DOMContentLoaded', loadGenres);

        // Enter key to add genre
        document.getElementById('newGenreInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('addGenreBtn').click();
            }
        });
    </script>
</body>
</html>