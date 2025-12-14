<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
    header("Location: ../../auth.php");
    exit;
}

require '../config/api.php';

// Fetch all movies
$movies = apiRequest('/movies/read.php');
if (!is_array($movies)) { $movies = []; }

// Handle delete
$deleteSuccess = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteResult = apiRequest('/movies/delete.php', 'POST', [
        'movie_id' => $_POST['delete_id']
    ]);
    $deleteSuccess = true;
    // Refresh movies list
    $movies = apiRequest('/movies/read.php');
    if (!is_array($movies)) { $movies = []; }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Movies - Admin Panel</title>
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

        .page-header {
            margin: 2rem 0;
        }

        .page-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .btn-add {
            background: linear-gradient(135deg, var(--primary-orange) 0%, #ff8f66 100%);
            color: white;
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.4);
            color: white;
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

        .movies-table {
            background-color: var(--card-bg);
            border: 2px solid rgba(255, 107, 53, 0.2);
            border-radius: 15px;
            overflow: hidden;
            margin-top: 2rem;
        }

        .table {
            margin-bottom: 0;
            color: var(--text-primary);
            /* Override Bootstrap's default white table background */
            --bs-table-bg: transparent;
        }

        .table thead {
            background: rgba(255, 107, 53, 0.1);
            border-bottom: 2px solid rgba(255, 107, 53, 0.3);
        }

        .table thead th {
            color: var(--primary-orange);
            font-weight: 600;
            border: none;
            padding: 1.2rem;
        }

        .table tbody td {
            border-color: rgba(255, 107, 53, 0.1);
            padding: 1.2rem;
            color: var(--text-secondary);
        }

        .table tbody tr:hover {
            background-color: rgba(255, 107, 53, 0.1);
        }

        /* Ensure all table cells inherit transparent background in dark theme */
        .table>:not(caption)>*>* {
            background-color: transparent;
        }

        .btn-edit {
            background: rgba(255, 107, 53, 0.2);
            border: 1px solid var(--primary-orange);
            color: var(--primary-orange);
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-edit:hover {
            background: var(--primary-orange);
            color: white;
        }

        .btn-delete {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid #dc3545;
            color: #dc3545;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-delete:hover {
            background: #dc3545;
            color: white;
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            background-color: var(--card-bg);
            border: 2px solid rgba(255, 107, 53, 0.2);
            border-radius: 15px;
            margin-top: 2rem;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.2);
            border: 2px solid #4caf50;
            color: #a8e6a1;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 1rem;
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
                    <a href="../index.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-film"></i> Kelola Film
                    </h1>
                    <p class="text-secondary">Tambah, edit, atau hapus film dari database</p>
                </div>
                <a href="add-movie.php" class="btn-add">
                    <i class="fas fa-plus"></i> Tambah Film Baru
                </a>
            </div>
        </div>

        <?php if ($deleteSuccess): ?>
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> Film berhasil dihapus!
            </div>
        <?php endif; ?>

        <?php if (empty($movies)): ?>
            <div class="no-data">
                <i class="fas fa-film" style="font-size: 3rem; color: var(--primary-orange); margin-bottom: 1rem; display: block;"></i>
                <p style="margin: 0;">Belum ada data film</p>
                <a href="add-movie.php" class="btn-add" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i> Tambah Film Pertama
                </a>
            </div>
        <?php else: ?>
            <div class="movies-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Judul Film</th>
                            <th>Tahun Rilis</th>
                            <th>Durasi</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movies as $m): ?>
                        <tr>
                            <td><code style="color: var(--primary-orange);"><?= htmlspecialchars($m['movie_id'] ?? '', ENT_QUOTES) ?></code></td>
                            <td style="color: var(--text-primary); font-weight: 500;"><?= htmlspecialchars($m['title'] ?? 'Tanpa judul', ENT_QUOTES) ?></td>
                            <td><?= htmlspecialchars($m['release_year'] ?? '-', ENT_QUOTES) ?></td>
                            <td><?= htmlspecialchars($m['duration'] ?? '-', ENT_QUOTES) ?> menit</td>
                            <td style="text-align: center;">
                                <a href="edit-movie.php?id=<?= urlencode($m['movie_id']) ?>" class="btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus film ini?');">
                                    <input type="hidden" name="delete_id" value="<?= htmlspecialchars($m['movie_id'], ENT_QUOTES) ?>">
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
