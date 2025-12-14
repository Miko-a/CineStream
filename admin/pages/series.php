<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
    header("Location: ../../auth.php");
    exit;
}

require '../config/api.php';

// Fetch all series
$series = apiRequest("/series/read.php");
if (!is_array($series)) { $series = []; }

// Handle delete
$deleteSuccess = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteResult = apiRequest('/series/delete.php', 'POST', [
        'series_id' => $_POST['delete_id']
    ]);
    $deleteSuccess = true;
    // Refresh series list
    $series = apiRequest("/series/read");
    if (!is_array($series)) { $series = []; }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Series - Admin Panel</title>
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

        .series-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .series-card {
            background-color: var(--card-bg);
            border: 2px solid rgba(255, 107, 53, 0.2);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .series-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(255, 107, 53, 0.3);
            border-color: var(--primary-orange);
        }

        .series-card-header {
            background: rgba(255, 107, 53, 0.1);
            padding: 1.5rem;
            border-bottom: 2px solid rgba(255, 107, 53, 0.3);
        }

        .series-card-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--text-primary);
            margin: 0;
        }

        .series-card-body {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .series-card-desc {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            flex-grow: 1;
        }

        .series-card-footer {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-action {
            background: rgba(255, 107, 53, 0.2);
            border: 1px solid var(--primary-orange);
            color: var(--primary-orange);
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            cursor: pointer;
            flex: 1;
            justify-content: center;
        }

        .btn-action:hover {
            background: var(--primary-orange);
            color: white;
        }

        .btn-delete {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid #dc3545;
            color: #dc3545;
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            flex: 1;
            justify-content: center;
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
                        <i class="fas fa-tv"></i> Kelola Series
                    </h1>
                    <p class="text-secondary">Tambah, edit, atau hapus series dari database</p>
                </div>
                <a href="add-series.php" class="btn-add">
                    <i class="fas fa-plus"></i> Tambah Series Baru
                </a>
            </div>
        </div>

        <?php if ($deleteSuccess): ?>
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> Series berhasil dihapus!
            </div>
        <?php endif; ?>

        <?php if (empty($series)): ?>
            <div class="no-data">
                <i class="fas fa-tv" style="font-size: 3rem; color: var(--primary-orange); margin-bottom: 1rem; display: block;"></i>
                <p style="margin: 0;">Belum ada data series</p>
                <a href="add-series.php" class="btn-add" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i> Tambah Series Pertama
                </a>
            </div>
        <?php else: ?>
            <div class="series-grid">
                <?php foreach ($series as $s): ?>
                <div class="series-card">
                    <div class="series-card-header">
                        <h3 class="series-card-title"><?= htmlspecialchars($s['title'] ?? 'Tanpa judul', ENT_QUOTES) ?></h3>
                    </div>
                    <div class="series-card-body">
                        <p class="series-card-desc"><?= htmlspecialchars(substr($s['description'] ?? '', 0, 100), ENT_QUOTES) ?><?= strlen($s['description'] ?? '') > 100 ? '...' : '' ?></p>
                        <div class="series-card-footer">
                            <a href="episodes.php?id=<?= urlencode($s['series_id']) ?>" class="btn-action">
                                <i class="fas fa-play-circle"></i> Episode
                            </a>
                            <form method="POST" style="flex: 1; display: flex;" onsubmit="return confirm('Yakin ingin menghapus series ini?');">
                                <input type="hidden" name="delete_id" value="<?= htmlspecialchars($s['series_id'], ENT_QUOTES) ?>">
                                <button type="submit" class="btn-delete" style="width: 100%;">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
