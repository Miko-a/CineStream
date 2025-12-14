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

require __DIR__ . '/../api/config/database.php';

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'] ?? '';

// Fetch user data from database
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: ../auth.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Nonton.in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-orange: #ff6b35;
            --dark-bg: #0f1419;
            --card-bg: #1a1f2e;
            --text-primary: #ffffff;
            --text-secondary: #8b92a8;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-primary);
            padding-top: 80px;
        }

        .navbar {
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

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-orange) !important;
        }

        .nav-link {
            color: var(--text-secondary) !important;
        }

        .nav-link:hover {
            color: var(--primary-orange) !important;
        }

        .profile-header {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.2) 0%, rgba(15, 20, 25, 0.95) 100%);
            padding: 3rem 2rem;
            text-align: center;
            margin-bottom: 3rem;
            border-bottom: 2px solid rgba(255, 107, 53, 0.2);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--primary-orange) 0%, #ff8f66 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
        }

        .profile-name {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-orange);
            margin-bottom: 0.5rem;
        }

        .profile-email {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .profile-card {
            background: var(--card-bg);
            border-radius: 15px;
            border: 2px solid rgba(255, 107, 53, 0.2);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .profile-card-title {
            color: var(--primary-orange);
            font-weight: bold;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 107, 53, 0.1);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .info-value {
            color: var(--text-primary);
            font-weight: 600;
        }

        .badge-role {
            display: inline-block;
            padding: 0.4rem 1rem;
            background: rgba(255, 107, 53, 0.2);
            border: 2px solid var(--primary-orange);
            color: var(--primary-orange);
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn-custom {
            flex: 1;
            min-width: 140px;
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-orange) 0%, #ff8f66 100%);
            color: white;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 107, 53, 0.5);
            text-decoration: none;
            color: white;
        }

        .btn-secondary-custom {
            background: transparent;
            color: var(--primary-orange);
            border: 2px solid var(--primary-orange);
        }

        .btn-secondary-custom:hover {
            background: var(--primary-orange);
            color: white;
            text-decoration: none;
        }

        .btn-danger-custom {
            background: rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
            border: 2px solid #ff6b6b;
        }

        .btn-danger-custom:hover {
            background: #ff6b6b;
            color: white;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .profile-header {
                padding: 2rem 1rem;
            }

            .profile-name {
                font-size: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-custom {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-play-circle"></i> Nonton.in
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="profile.php"><i class="fas fa-user"></i> Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php"><i class="fas fa-fire"></i> Explore</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php" onclick="return confirm('Logout?')">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            <i class="fas fa-user"></i>
        </div>
        <h1 class="profile-name"><?= htmlspecialchars($user['username'] ?? 'User', ENT_QUOTES) ?></h1>
        <p class="profile-email"><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES) ?></p>
    </div>

    <!-- Profile Container -->
    <div class="profile-container">
        <!-- Account Information -->
        <div class="profile-card">
            <div class="profile-card-title">
                <i class="fas fa-info-circle"></i> Informasi Akun
            </div>

            <div class="info-row">
                <span class="info-label"><i class="fas fa-envelope"></i> Email</span>
                <span class="info-value"><?= htmlspecialchars($user['email'], ENT_QUOTES) ?></span>
            </div>

            <div class="info-row">
                <span class="info-label"><i class="fas fa-user-tag"></i> Username</span>
                <span class="info-value"><?= htmlspecialchars($user['username'], ENT_QUOTES) ?></span>
            </div>

            <div class="info-row">
                <span class="info-label"><i class="fas fa-shield-alt"></i> Role</span>
                <span class="badge-role">
                    <?= strtoupper($user['role'] ?? 'User') ?>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label"><i class="fas fa-calendar"></i> Bergabung</span>
                <span class="info-value">
                    <?= date('d M Y', strtotime($user['created_at'] ?? 'now')) ?>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label"><i class="fas fa-clock"></i> Terakhir Update</span>
                <span class="info-value">
                    <?= date('d M Y H:i', strtotime($user['updated_at'] ?? 'now')) ?>
                </span>
            </div>
        </div>

        <!-- Account Stats -->
        <div class="profile-card">
            <div class="profile-card-title">
                <i class="fas fa-chart-bar"></i> Statistik
            </div>

            <div class="info-row">
                <span class="info-label"><i class="fas fa-heart"></i> Total Favorit</span>
                <span class="info-value">-</span>
            </div>

            <div class="info-row">
                <span class="info-label"><i class="fas fa-history"></i> Film Ditonton</span>
                <span class="info-value">-</span>
            </div>

            <div class="info-row">
                <span class="info-label"><i class="fas fa-clock"></i> Total Waktu</span>
                <span class="info-value">-</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="dashboard.php" class="btn-custom btn-secondary-custom">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            <a href="../logout.php" class="btn-custom btn-danger-custom" onclick="return confirm('Yakin ingin logout?')">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
