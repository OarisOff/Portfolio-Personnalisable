<?php
// admin/settings.php - Site Configuration
require_once 'auth.php';

$message = '';
$settings = get_settings($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = $_POST['site_name'] ?? '';
    $quick_desc = $_POST['quick_desc'] ?? '';
    $about_text = $_POST['about_text'] ?? '';
    $color_palette = $_POST['color_palette'] ?? 'emerald';
    $font_family = $_POST['font_family'] ?? 'Inter';
    $new_password = $_POST['new_password'] ?? '';

    // Update settings
    $query = "UPDATE settings SET site_name = ?, quick_desc = ?, about_text = ?, color_palette = ?, font_family = ? WHERE id = 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$site_name, $quick_desc, $about_text, $color_palette, $font_family]);

    // Update password if provided
    if (!empty($new_password)) {
        $hashed_pass = password_hash($new_password, PASSWORD_BCRYPT);
        $pdo->prepare("UPDATE settings SET admin_password = ? WHERE id = 1")->execute([$hashed_pass]);
    }

    $message = "Configuration mise à jour avec succès !";
    $settings = get_settings($pdo); // Refresh settings
}
?>
<!DOCTYPE html>
<html lang="fr" data-theme="<?php echo htmlspecialchars($settings['color_palette']); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - Admin Portfolio</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Outfit:wght@400;700;900&family=Playfair+Display:wght@400;700;900&family=Roboto:wght@400;700;900&family=Montserrat:wght@400;700;900&family=Poppins:wght@400;700;900&family=Lora:wght@400;700;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: '<?php echo htmlspecialchars($settings['font_family']); ?>', sans-serif;
        }
    </style>
</head>

<body class="admin-layout">
    <aside class="admin-sidebar">
        <h2>Portfolio by Tropi-Code</h2>
        <nav class="admin-nav">
            <ul>
                <li><a href="index.php">Tableau de bord</a></li>
                <li><a href="settings.php" class="active">Configuration</a></li>
                <li><a href="projects.php">Mes Projets</a></li>
                <li style="margin-top: 5rem;"><a href="../index.php" target="_blank"
                        style="background: rgba(255,255,255,0.05);">Voir le Site</a></li>
                <li><a href="logout.php" style="color: #ef4444;">Déconnexion</a></li>
            </ul>
        </nav>
    </aside>

    <main class="admin-content">
        <header style="margin-bottom: 4rem;">
            <p style="color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px;">
                Identité Visuelle,</p>
            <h1 style="font-size: 3rem; font-weight: 900;">Configuration</h1>
        </header>

        <?php if ($message): ?>
            <div class="admin-card"
                style="border-left: 5px solid var(--primary); color: var(--primary); padding: 1.5rem; border-radius: 0.75rem; background: rgba(16,185,129,0.1);">
                <strong>Succès !</strong> <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="reveal">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem;">
                <!-- Infos de base -->
                <div class="admin-card">
                    <h3>Identité & Bio</h3>
                    <div class="form-group">
                        <label>Titre / Nom sur le site</label>
                        <input type="text" name="site_name" class="form-control"
                            value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Poste / Métier / Accroche</label>
                        <input type="text" name="quick_desc" class="form-control"
                            value="<?php echo htmlspecialchars($settings['quick_desc']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Texte "À propos"</label>
                        <textarea name="about_text" class="form-control"
                            rows="8"><?php echo htmlspecialchars($settings['about_text']); ?></textarea>
                    </div>
                </div>

                <!-- Design & Sécurité -->
                <div class="admin-card">
                    <h3>Design & Thème</h3>
                    <div class="form-group">
                        <label>Palette de Couleurs</label>
                        <select name="color_palette" class="form-control">
                            <option value="emerald" <?php echo $settings['color_palette'] === 'emerald' ? 'selected' : ''; ?>>Emerald (Vert Moderne)</option>
                            <option value="ocean" <?php echo $settings['color_palette'] === 'ocean' ? 'selected' : ''; ?>>
                                Ocean (Bleu Profond)</option>
                            <option value="midnight" <?php echo $settings['color_palette'] === 'midnight' ? 'selected' : ''; ?>>Midnight (Noir & Blanc)</option>
                            <option value="rose" <?php echo $settings['color_palette'] === 'rose' ? 'selected' : ''; ?>>
                                Rose (Doux & Élégant)</option>
                            <option value="sunset" <?php echo $settings['color_palette'] === 'sunset' ? 'selected' : ''; ?>>Sunset (Orange / Red)</option>
                            <option value="amethyst" <?php echo $settings['color_palette'] === 'amethyst' ? 'selected' : ''; ?>>Amethyst (Violet / Purple)</option>
                            <option value="forest" <?php echo $settings['color_palette'] === 'forest' ? 'selected' : ''; ?>>Forest (Terre / Vert Forêt)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Police d'écriture</label>
                        <select name="font_family" class="form-control">
                            <option value="Inter" <?php echo $settings['font_family'] === 'Inter' ? 'selected' : ''; ?>>
                                Inter (Sans-serif Moderne)</option>
                            <option value="Poppins" <?php echo $settings['font_family'] === 'Poppins' ? 'selected' : ''; ?>>Poppins (Géométrique Doux)</option>
                            <option value="Montserrat" <?php echo $settings['font_family'] === 'Montserrat' ? 'selected' : ''; ?>>Montserrat (Moderne Robuste)</option>
                            <option value="Outfit" <?php echo $settings['font_family'] === 'Outfit' ? 'selected' : ''; ?>>
                                Outfit (Futuriste)</option>
                            <option value="Playfair Display" <?php echo $settings['font_family'] === 'Playfair Display' ? 'selected' : ''; ?>>Playfair Display (Serif Élégant)</option>
                            <option value="Lora" <?php echo $settings['font_family'] === 'Lora' ? 'selected' : ''; ?>>Lora
                                (Serif Moderne)</option>
                            <option value="Roboto" <?php echo $settings['font_family'] === 'Roboto' ? 'selected' : ''; ?>>
                                Roboto (Classique)</option>
                        </select>
                    </div>

                    <h3 style="margin-top: 3rem;">Sécurité Compte</h3>
                    <div class="form-group">
                        <label>Nouveau mot de passe (Laisser vide pour ne pas changer)</label>
                        <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn-admin"
                        style="width: 100%; margin-top: 1rem; font-size: 1.1rem; letter-spacing: 1px;">Enregistrer les
                        modifications</button>
                </div>
            </div>
        </form>
    </main>

    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = "1";
                    entry.target.style.transform = "translateY(0)";
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.reveal').forEach(el => {
            el.style.opacity = "0";
            el.style.transform = "translateY(20px)";
            el.style.transition = "all 0.6s ease forwards";
            observer.observe(el);
        });
    </script>
</body>

</html>