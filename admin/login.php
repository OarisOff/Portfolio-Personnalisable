<?php
// admin/login.php - Admin Panel Login
require_once dirname(__DIR__) . '/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    // Fetch hashed password from settings
    $settings = get_settings($pdo);

    if (!$settings) {
        header("Location: ../setup.php");
        exit;
    }

    if (password_verify($password, $settings['admin_password'])) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Mot de passe incorrect.";
    }
}

// Optional: apply theme to login page too
$settings = get_settings($pdo);
$theme = $settings ? $settings['color_palette'] : 'emerald';
$font = $settings ? $settings['font_family'] : 'Inter';
?>
<!DOCTYPE html>
<html lang="fr" data-theme="<?php echo htmlspecialchars($theme); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Admin Portfolio</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Outfit:wght@400;700;900&family=Playfair+Display:wght@400;700;900&family=Roboto:wght@400;700;900&family=Montserrat:wght@400;700;900&family=Poppins:wght@400;700;900&family=Lora:wght@400;700;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: '<?php echo htmlspecialchars($font); ?>', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background: var(--bg);
        }

        .login-card {
            background: var(--card-bg);
            padding: 3.5rem;
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 450px;
            border: 1px solid var(--border);
            backdrop-filter: blur(15px);
            text-align: center;
        }

        h1 {
            margin-bottom: 2.5rem;
            font-size: 2rem;
            font-weight: 900;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.75rem;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .error {
            color: #ef4444;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            background: rgba(239, 68, 68, 0.1);
            padding: 0.75rem;
            border-radius: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="login-card reveal">
        <h1>Admin</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="password">Clé d'administration</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••"
                    required autofocus>
            </div>
            <button type="submit" class="btn-admin"
                style="width: 100%; margin-top: 1rem; padding: 1.25rem; font-size: 1.1rem; letter-spacing: 1px;">Accéder
                au Panel</button>
        </form>
        <p style="margin-top: 2rem; font-size: 0.8rem; color: var(--text-muted);">&copy; Portfolio by Tropi-Code v1.0
        </p>
    </div>

    <script>
        document.querySelector('.reveal').style.opacity = "1";
        document.querySelector('.reveal').style.transform = "translateY(0)";
        document.querySelector('.reveal').style.transition = "all 0.8s ease forwards";
    </script>
</body>

</html>