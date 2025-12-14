<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    $redirect = ($_SESSION['role'] ?? 'user') === 'admin' ? 'admin/index.php' : 'user/dashboard.php';
    header("Location: $redirect");
    exit;
}

$error = '';
$success = '';
$is_register = isset($_GET['register']) && $_GET['register'] === '1';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_register ? 'Daftar Akun' : 'Login' ?> - Nonton.in</title>
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
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(15, 20, 25, 0.95) 100%),
                        url('https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=1920') center/cover;
            background-attachment: fixed;
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            background: var(--card-bg);
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
            border: 2px solid rgba(255, 107, 53, 0.2);
            padding: 40px;
            max-width: 450px;
            width: 100%;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-orange);
            text-shadow: 0 0 20px rgba(255, 107, 53, 0.5);
            margin-bottom: 10px;
        }

        .brand p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 107, 53, 0.2);
            color: var(--text-primary);
            padding: 12px 16px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary-orange);
            box-shadow: 0 0 20px rgba(255, 107, 53, 0.2);
            color: var(--text-primary);
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-orange) 0%, #ff8f66 100%);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.3);
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 107, 53, 0.5);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .toggle-auth {
            text-align: center;
            margin-top: 20px;
            color: var(--text-secondary);
        }

        .toggle-auth a {
            color: var(--primary-orange);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .toggle-auth a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
            border: none;
            animation: slideIn 0.3s ease-out;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            color: #51cf66;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle .toggle-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-secondary);
            transition: color 0.3s;
        }

        .password-toggle .toggle-icon:hover {
            color: var(--primary-orange);
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 35%;
            height: 1px;
            background: rgba(255, 107, 53, 0.2);
        }

        .divider::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            width: 35%;
            height: 1px;
            background: rgba(255, 107, 53, 0.2);
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: 25px;
            }

            .brand h1 {
                font-size: 2rem;
            }

            .form-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="brand">
            <h1><i class="fas fa-play-circle"></i> Nonton.in</h1>
            <p>Streaming Film Terbaik</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error, ENT_QUOTES) ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success, ENT_QUOTES) ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($is_register): ?>
            <!-- Register Form -->
            <h2 class="form-title">Daftar Akun Baru</h2>
            <form id="registerForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Masukkan username Anda" required>
                    <small class="text-secondary">Minimal 3 karakter</small>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="nama@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-toggle">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Minimal 6 karakter" required>
                        <i class="fas fa-eye toggle-icon" onclick="togglePassword('password')"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <div class="password-toggle">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                               placeholder="Ulangi password" required>
                        <i class="fas fa-eye toggle-icon" onclick="togglePassword('confirm_password')"></i>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>
            </form>

            <div class="toggle-auth">
                Sudah punya akun? <a href="auth.php">Login di sini</a>
            </div>
        <?php else: ?>
            <!-- Login Form -->
            <h2 class="form-title">Masuk ke Akun</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="nama@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-toggle">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Masukkan password" required>
                        <i class="fas fa-eye toggle-icon" onclick="togglePassword('password')"></i>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div class="divider">atau</div>

            <div class="toggle-auth">
                Belum punya akun? <a href="auth.php?register=1">Daftar di sini</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = event.target;
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('<?= $is_register ? 'registerForm' : 'loginForm' ?>').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const endpoint = <?= $is_register ? "'/api/auth/register.php'" : "'/api/auth/login.php'" ?>;
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Store session data
                    sessionStorage.setItem('user_id', result.user_id);
                    sessionStorage.setItem('role', result.role);

                    // Create session via AJAX and redirect
                    fetch('<?= dirname($_SERVER['PHP_SELF']) ?>/set-session.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            user_id: result.user_id,
                            email: result.email,
                            role: result.role
                        })
                    }).then(() => {
                        // Redirect based on role
                        if (result.role === 'admin') {
                            window.location.href = 'admin/index.php';
                        } else {
                            window.location.href = 'user/dashboard.php';
                        }
                    });
                } else {
                    alert(result.error || 'Terjadi kesalahan');
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        });
    </script>
</body>
</html>
