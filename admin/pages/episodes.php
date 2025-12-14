<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
    header("Location: ../../auth.php");
    exit;
}

require '../config/api.php';

$seriesId = isset($_GET['id']) ? $_GET['id'] : null;
$series = null;
$episodes = [];

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
    
    // Get episodes for this series
    $episodes = apiRequest("/episodes/read?series_id=".$seriesId);
    if (!is_array($episodes)) { $episodes = []; }
}

// Handle delete
$deleteSuccess = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteResult = apiRequest('/episodes/delete.php', 'POST', [
        'episode_id' => $_POST['delete_id']
    ]);
    $deleteSuccess = true;
    // Refresh episodes list
    $episodes = apiRequest("/episodes/read?series_id=".$seriesId);
    if (!is_array($episodes)) { $episodes = []; }
}

// Handle update
$updateSuccess = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $payload = [
        'episode_id' => $_POST['update_id'],
        'season' => $_POST['season'] ?? '',
        'episode_number' => $_POST['episode_number'] ?? '',
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'video_url' => $_POST['video_url'] ?? ''
    ];
    $updateResult = apiRequest('/episodes/update.php', 'POST', $payload);
    $updateSuccess = true;
    $episodes = apiRequest("/episodes/read?series_id=".$seriesId);
    if (!is_array($episodes)) { $episodes = []; }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Episode - Admin Panel</title>
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
        
                .episodes-table {
                    background-color: var(--card-bg);
                    border: 2px solid rgba(255, 107, 53, 0.2);
                    border-radius: 15px;
                    overflow: hidden;
                    margin-top: 2rem;
                }
        
                .table {
                    margin-bottom: 0;
                    color: var(--text-primary);
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
        
                .series-info {
                    background: rgba(255, 107, 53, 0.1);
                    border: 2px solid rgba(255, 107, 53, 0.3);
                    border-radius: 8px;
                    padding: 1rem;
                    margin-bottom: 1.5rem;
                }
        
                .series-info-title {
                    font-size: 1.3rem;
                    font-weight: bold;
                    color: var(--primary-orange);
                    margin: 0;
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
                <?php if (!$series): ?>
                    <div class="no-data" style="margin-top: 4rem;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: var(--primary-orange); margin-bottom: 1rem; display: block;"></i>
                        <p style="margin: 0;">Series tidak ditemukan</p>
                        <a href="series.php" class="btn-back" style="margin-top: 1rem;">
                            <i class="fas fa-arrow-left"></i> Kembali ke Series
                        </a>
                    </div>
                <?php else: ?>
                    <div class="page-header">
                        <div class="series-info">
                            <p class="series-info-title">
                                <i class="fas fa-tv"></i> <?= htmlspecialchars($series['title'], ENT_QUOTES) ?>
                            </p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h1 class="page-title">
                                    <i class="fas fa-play-circle"></i> Kelola Episode
                                </h1>
                                <p class="text-secondary">Tambah atau hapus episode untuk series ini</p>
                            </div>
                            <a href="add-episode.php?series_id=<?= urlencode($seriesId) ?>" class="btn-add">
                                <i class="fas fa-plus"></i> Tambah Episode
                            </a>
                        </div>
                    </div>
        
                    <?php if ($deleteSuccess): ?>
                        <div class="alert-success">
                            <i class="fas fa-check-circle"></i> Episode berhasil dihapus!
                        </div>
                    <?php endif; ?>
        
                    <!-- Simple Edit Modal -->
                    <div class="modal fade" id="editModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content" style="background-color: var(--card-bg); color: var(--text-primary);">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Episode</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="update_id" id="edit_id">
                                        <div class="mb-3">
                                            <label class="form-label">Season</label>
                                            <input type="number" class="form-control" name="season" id="edit_season">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Episode</label>
                                            <input type="number" class="form-control" name="episode_number" id="edit_episode_number">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Judul</label>
                                            <input type="text" class="form-control" name="title" id="edit_title">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi</label>
                                            <textarea class="form-control" name="description" id="edit_description"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Video URL</label>
                                            <input type="text" class="form-control" name="video_url" id="edit_video_url">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn-add"><i class="fas fa-save"></i> Simpan</button>
                                        <button type="button" class="btn-back" data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php if (empty($episodes)): ?>
                        <div class="no-data">
                            <i class="fas fa-play-circle" style="font-size: 3rem; color: var(--primary-orange); margin-bottom: 1rem; display: block;"></i>
                            <p style="margin: 0;">Belum ada episode untuk series ini</p>
                            <a href="add-episode.php?series_id=<?= urlencode($seriesId) ?>" class="btn-add" style="margin-top: 1rem;">
                                <i class="fas fa-plus"></i> Tambah Episode Pertama
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="episodes-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Musim</th>
                                        <th>Episode</th>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th style="text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($episodes as $e): ?>
                                    <tr>
                                        <td><span style="background: rgba(255, 107, 53, 0.3); color: var(--primary-orange); padding: 0.3rem 0.6rem; border-radius: 4px; font-weight: bold;">S<?= $e['season'] ?></span></td>
                                        <td><span style="background: rgba(255, 107, 53, 0.3); color: var(--primary-orange); padding: 0.3rem 0.6rem; border-radius: 4px; font-weight: bold;">E<?= $e['episode_number'] ?></span></td>
                                        <td style="color: var(--text-primary); font-weight: 500;"><?= htmlspecialchars($e['title'] ?? 'Tanpa judul', ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars(substr($e['description'] ?? '', 0, 50), ENT_QUOTES) ?><?= strlen($e['description'] ?? '') > 50 ? '...' : '' ?></td>
                                        <td style="text-align: center;">
                                            <button type="button" class="btn-delete" onclick="openEditModal('<?= htmlspecialchars($e['episode_id'], ENT_QUOTES) ?>')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus episode ini?');">
                                                <input type="hidden" name="delete_id" value="<?= htmlspecialchars($e['episode_id'], ENT_QUOTES) ?>">
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
                <?php endif; ?>
            </div>

            <script>
                const episodesData = <?= json_encode($episodes) ?>;
                function openEditModal(id) {
                    const e = episodesData.find(x => String(x.episode_id) === String(id));
                    if (!e) return;
                    document.getElementById('edit_id').value = e.episode_id || '';
                    document.getElementById('edit_season').value = e.season || '';
                    document.getElementById('edit_episode_number').value = e.episode_number || '';
                    document.getElementById('edit_title').value = e.title || '';
                    document.getElementById('edit_description').value = e.description || '';
                    document.getElementById('edit_video_url').value = e.video_url || '';
                    const modal = new bootstrap.Modal(document.getElementById('editModal'));
                    modal.show();
                }
            </script>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        </body>
</html>
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

