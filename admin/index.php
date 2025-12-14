<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth.php");
    exit;
}

if (($_SESSION['role'] ?? 'user') !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit;
}

require __DIR__ . '/config/api.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Nonton.in</title>
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
            --navbar-bg: #141922;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
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
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-orange) !important;
            text-shadow: 0 0 20px rgba(255, 107, 53, 0.5);
        }

        .admin-header {
            margin: 3rem 0 2rem;
        }

        .admin-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .admin-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .admin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .admin-card {
            background-color: var(--card-bg);
            border-radius: 15px;
            overflow: hidden;
            border: 2px solid rgba(255, 107, 53, 0.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .admin-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(255, 107, 53, 0.3);
            border-color: var(--primary-orange);
        }

        .admin-card-icon {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.2) 0%, rgba(255, 107, 53, 0.1) 100%);
            padding: 2rem;
            text-align: center;
            font-size: 3.5rem;
            color: var(--primary-orange);
        }

        .admin-card-body {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .admin-card-title {
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .admin-card-text {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .admin-card-footer {
            display: flex;
            gap: 0.5rem;
        }

        .btn-admin {
            background: linear-gradient(135deg, var(--primary-orange) 0%, #ff8f66 100%);
            color: white;
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            flex: 1;
            text-align: center;
        }

        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.4);
            color: white;
        }

        .btn-logout {
            background: transparent;
            border: 2px solid var(--text-secondary);
            color: var(--text-secondary);
            padding: 0.7rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-logout:hover {
            border-color: var(--primary-orange);
            color: var(--primary-orange);
            box-shadow: 0 0 20px rgba(255, 107, 53, 0.3);
        }

        .btn-user-site {
            background: transparent;
            border: 2px solid rgba(255, 107, 53, 0.5);
            color: var(--text-secondary);
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            flex: 1;
            text-align: center;
        }

        .btn-user-site:hover {
            border-color: var(--primary-orange);
            color: var(--primary-orange);
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer {
            background: rgba(20, 25, 34, 0.8);
            border-top: 2px solid rgba(255, 107, 53, 0.2);
            color: var(--text-secondary);
            padding: 3rem 0 1rem;
            margin-top: 5rem;
        }

        .footer-title {
            color: var(--text-primary);
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .footer-link {
            color: var(--text-secondary);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
        }

        .footer-link:hover {
            color: var(--primary-orange);
            transform: translateX(5px);
        }
    </style>
</head>
<body>
    <!-- Admin Navbar -->
    <nav class="admin-navbar fixed-top">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-crown"></i> Admin Nonton.in
                </a>
                <a href="../logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="admin-header">
            <div class="top-bar">
                <div>
                    <h1 class="admin-title">
                        <i class="fas fa-sliders-h"></i> Panel Admin
                    </h1>
                    <p class="admin-subtitle">Kelola konten platform streaming CineStream</p>
                </div>
            </div>

            <div class="admin-grid">
                <!-- Movies Card -->
                <div class="admin-card">
                    <div class="admin-card-icon">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="admin-card-body">
                        <h3 class="admin-card-title">Film</h3>
                        <p class="admin-card-text">Tambah, edit, atau hapus film dari database. Kelola semua informasi film dan video utama.</p>
                        <div class="admin-card-footer">
                            <a href="pages/movies.php" class="btn-admin">
                                <i class="fas fa-cog"></i> Kelola Film
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Series Card -->
                <div class="admin-card">
                    <div class="admin-card-icon">
                        <i class="fas fa-tv"></i>
                    </div>
                    <div class="admin-card-body">
                        <h3 class="admin-card-title">Series</h3>
                        <p class="admin-card-text">Kelola data series secara keseluruhan. Tambah series baru, edit informasi, atau hapus series.</p>
                        <div class="admin-card-footer">
                            <a href="pages/series.php" class="btn-admin">
                                <i class="fas fa-cog"></i> Kelola Series
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="footer-title">
                        <i class="fas fa-crown"></i> Admin Panel
                    </h5>
                    <p class="text-secondary">Pusat manajemen konten untuk platform streaming Nonton.in. Kelola semua aspek dari film, series, dan episode.</p>
                </div>
                <div class="col-md-2">
                    <h5 class="footer-title">Navigasi</h5>
                    <a href="pages/movies.php" class="footer-link">Kelola Film</a>
                    <a href="pages/series.php" class="footer-link">Kelola Series</a>
                    <a href="pages/episodes.php" class="footer-link">Kelola Episode</a>
                    <a href="../index.php" class="footer-link">User Site</a>
                </div>
                <div class="col-md-3">
                    <h5 class="footer-title">Informasi</h5>
                    <a href="#" class="footer-link">Dokumentasi</a>
                    <a href="#" class="footer-link">Bantuan</a>
                    <a href="#" class="footer-link">FAQ Admin</a>
                    <a href="#" class="footer-link">Kontak Support</a>
                </div>
                <div class="col-md-3">
                    <h5 class="footer-title">Akun</h5>
                    <a href="../logout.php" class="footer-link">Logout</a>
                    <a href="../user/profile.php" class="footer-link">Profil</a>
                    <a href="#" class="footer-link">Pengaturan</a>
                    <a href="#" class="footer-link">Privasi</a>
                </div>
            </div>
            <hr style="border-color: rgba(255, 107, 53, 0.2); margin: 2rem 0;">
            <div class="text-center text-secondary">
                <p>&copy; 2024 Nonton.in Admin Panel. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
