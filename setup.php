<?php
// setup.php - Installation & Initialization
require_once 'config.php';

$message = '';
$is_installed = false;

// Check if tables already exist and have data
try {
    // Handle Reset
    if (isset($_GET['reset']) && $_GET['reset'] === '1') {
        $pdo->exec("DROP TABLE IF EXISTS settings");
        $pdo->exec("DROP TABLE IF EXISTS projects");
        header("Location: setup.php");
        exit;
    }

    $stmt = $pdo->query("SELECT COUNT(*) FROM settings");
    if ($stmt && $stmt->fetchColumn() > 0) {
        $is_installed = true;
    }
} catch (PDOException $e) {
    // Table doesn't exist yet, we can install
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_installed) {
    try {
        $site_name = $_POST['site_name'] ?? 'Mon Portfolio';
        $quick_desc = $_POST['quick_desc'] ?? '';
        $color_palette = $_POST['color_palette'] ?? 'emerald';
        $font_family = $_POST['font_family'] ?? 'Inter';
        $password = $_POST['admin_password'] ?? 'admin123';
        
        // 1. Create Tables
        $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
            id INTEGER PRIMARY KEY DEFAULT 1,
            site_name TEXT,
            quick_desc TEXT,
            about_text TEXT DEFAULT 'Bienvenue sur mon portfolio.',
            color_palette TEXT,
            font_family TEXT,
            admin_password TEXT
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS projects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            image_url TEXT,
            project_link TEXT
        )");

        // 2. Insert Settings
        $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
        $pdo->prepare("INSERT INTO settings (id, site_name, quick_desc, color_palette, font_family, admin_password) VALUES (1, ?, ?, ?, ?, ?)")
            ->execute([$site_name, $quick_desc, $color_palette, $font_family, $hashed_pass]);

        $message = "Installation réussie ! Le fichier de configuration setup.php a été supprimé par sécurité. Vous pouvez maintenant accéder à votre portfolio.";
        $is_installed = true;
        
        // Self-delete the setup file after success
        unlink(__FILE__);
    } catch (PDOException $e) {
        $message = "Erreur pendant l'installation : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Portfolio</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Outfit:wght@400;700;900&family=Playfair+Display:wght@400;700;900&family=Roboto:wght@400;700;900&family=Montserrat:wght@400;700;900&family=Poppins:wght@400;700;900&family=Lora:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #10b981; --bg: #020617; --card: #1e293b; --text: #f8fafc; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 2rem; box-sizing: border-box;}
        .setup-card { background: var(--card); padding: 3rem; border-radius: 2rem; width: 100%; max-width: 550px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); }
        h1 { margin-bottom: 2rem; color: var(--primary); text-align: center; font-weight: 900; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; margin-bottom: 0.5rem; color: #94a3b8; font-weight: 600; font-size: 0.9rem; }
        input, select { width: 100%; padding: 1rem; border-radius: 1rem; border: 1px solid #334155; background: #0f172a; color: white; box-sizing: border-box; font-family: inherit; font-size: 1rem; transition: 0.3s; }
        input:focus, select:focus { border-color: var(--primary); outline: none; background: #1e293b; }
        button { width: 100%; padding: 1.25rem; background: var(--primary); color: white; border: none; border-radius: 1rem; font-weight: 800; cursor: pointer; transition: 0.3s; margin-top: 1.5rem; text-transform: uppercase; letter-spacing: 1px; }
        button:hover { opacity: 0.9; transform: translateY(-5px); box-shadow: 0 10px 20px rgba(16,185,129,0.3); }
        .alert { background: rgba(16, 185, 129, 0.1); padding: 1.5rem; border-radius: 1rem; margin-bottom: 2rem; border-left: 5px solid var(--primary); text-align: center; }
        .reset-link { display: block; text-align: center; color: #ef4444; text-decoration: none; margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 600; opacity: 0.8; }
        .reset-link:hover { opacity: 1; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="setup-card">
        <?php if ($is_installed && empty($message)): ?>
            <h1>Installation terminée</h1>
            <div class="alert">Votre portfolio est déjà installé et configuré.</div>
            <a href="setup.php?reset=1" class="reset-link" onclick="return confirm('Attention: Cela va supprimer tous vos projets et réglages. Continuer ?')">Réinitialiser l'installation</a>
            <a href="index.php" style="display:block; text-align:center; color:var(--primary); text-decoration:none; font-weight:900; font-size:1.1rem;">Aller au portfolio →</a>
        <?php elseif (!empty($message)): ?>
            <h1>Terminé !</h1>
            <div class="alert"><?php echo htmlspecialchars($message); ?></div>
            <p style="text-align:center; color: #94a3b8; font-size: 0.9rem; margin-bottom: 2rem;">Accédez à votre espace administrateur avec le mot de passe choisi.</p>
            <a href="index.php" style="display:block; text-align:center; color:var(--primary); text-decoration:none; font-weight:900; font-size:1.2rem;">Lancer le Portfolio →</a>
        <?php else: ?>
            <h1>Portfolio by Tropi-Code v1.0</h1>
            <p style="color:#94a3b8; text-align:center; margin-bottom: 2.5rem; font-size: 0.95rem;">Bienvenue ! Commençons par configurer ton identité visuelle.</p>
            <form method="POST">
                <div class="form-group">
                    <label>Nom du Portfolio</label>
                    <input type="text" name="site_name" placeholder="Ex: Garis" required>
                </div>
                <div class="form-group">
                    <label>Slogan / Métier</label>
                    <input type="text" name="quick_desc" placeholder="Ex: Développeur PHP Freelance" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Palette de Couleurs</label>
                        <select name="color_palette">
                            <option value="emerald">Emerald (Vert)</option>
                            <option value="ocean">Ocean (Bleu)</option>
                            <option value="midnight">Midnight (N&W)</option>
                            <option value="rose">Rose (Doux)</option>
                            <option value="sunset">Sunset (Orange)</option>
                            <option value="amethyst">Amethyst (Violet)</option>
                            <option value="forest">Forest (Terre)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Police d'écriture</label>
                        <select name="font_family">
                            <option value="Inter">Inter</option>
                            <option value="Poppins">Poppins</option>
                            <option value="Montserrat">Montserrat</option>
                            <option value="Outfit">Outfit</option>
                            <option value="Playfair Display">Playfair</option>
                            <option value="Lora">Lora</option>
                            <option value="Roboto">Roboto</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Mot de passe Administrateur</label>
                    <input type="password" name="admin_password" placeholder="Saisir un mot de passe sécurisé" required>
                </div>
                <button type="submit">Finaliser l'installation</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
