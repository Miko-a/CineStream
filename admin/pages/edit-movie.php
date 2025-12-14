<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
    header("Location: ../../auth.php");
    exit;
}

require '../config/api.php';

$movie_id = $_GET['id'] ?? null;
$error = '';
$success = '';
$movie = null;

// Fetch movie details if editing
if ($movie_id) {
    $movies = apiRequest('/movies/read.php');
    if (is_array($movies)) {
        foreach ($movies as $m) {
            if ($m['movie_id'] == $movie_id) {
                $movie = $m;
                break;
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $release_year = $_POST['release_year'] ?? '';
    $duration = $_POST['duration'] ?? 0;
    $video_url = $_POST['video_url'] ?? 'placeholder.mp4';

    if (empty($title)) {
        $error = 'Judul film harus diisi!';
    } else {
        $result = apiRequest('/movies/update.php', 'POST', [
            'movie_id' => $movie_id,
            'title' => $title,
            'description' => $description,
            'release_year' => intval($release_year),
            'duration' => intval($duration),
            'video_url' => $video_url
        ]);
        $success = 'Film berhasil diperbarui!';
        $movie = [
            'movie_id' => $movie_id,
            'title' => $title,
            'description' => $description,
            'release_year' => $release_year,
            'duration' => $duration,
            'video_url' => $video_url
        ];
    }
}

if (!$movie) {
    header("Location: movies.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Film - Admin Panel</title>
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
                    <a href="movies.php" class="btn-back">
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
                <i class="fas fa-edit"></i> Edit Film
            </h1>
            <p class="text-secondary">Perbarui informasi film yang sudah ada</p>
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
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="title">
                        <i class="fas fa-heading"></i> Judul Film <span style="color: #dc3545;">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($movie['title'] ?? '', ENT_QUOTES) ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">
                        <i class="fas fa-align-left"></i> Deskripsi / Sinopsis
                    </label>
                    <textarea id="description" name="description" placeholder="Ceritakan tentang film ini..."><?= htmlspecialchars($movie['description'] ?? '', ENT_QUOTES) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="release_year">
                                <i class="fas fa-calendar"></i> Tahun Rilis
                            </label>
                            <input type="number" id="release_year" name="release_year" min="1900" max="2100" value="<?= htmlspecialchars($movie['release_year'] ?? '', ENT_QUOTES) ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="duration">
                                <i class="fas fa-clock"></i> Durasi (menit)
                            </label>
                            <input type="number" id="duration" name="duration" min="0" value="<?= htmlspecialchars($movie['duration'] ?? 0, ENT_QUOTES) ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="video_url">
                        <i class="fas fa-link"></i> URL Video / Poster
                    </label>
                    <input type="text" id="video_url" name="video_url" placeholder="https://..." value="<?= htmlspecialchars($movie['video_url'] ?? '', ENT_QUOTES) ?>">
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Perbarui Film
                    </button>
                    <a href="movies.php" class="btn-back">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
