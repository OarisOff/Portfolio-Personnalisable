<?php
// admin/index.php - Admin Dashboard
require_once 'auth.php';

$settings = get_settings($pdo);
?>
<!DOCTYPE html>
<html lang="fr" data-theme="<?php echo htmlspecialchars($settings['color_palette']); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Portfolio</title>
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
                <li><a href="index.php" class="active">Tableau de bord</a></li>
                <li><a href="settings.php">Configuration</a></li>
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
                Bienvenue,</p>
            <h1 style="font-size: 3rem; font-weight: 900;">Tableau de bord</h1>
    </main>

    <div class="admin-card reveal">
        <h3>Statut Global</h3>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
            <div
                style="background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--border);">
                <p style="color: var(--text-muted); font-size: 0.8rem;">Nom du site</p>
                <p style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">
                    <?php echo htmlspecialchars($settings['site_name']); ?></p>
            </div>
            <div
                style="background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--border);">
                <p style="color: var(--text-muted); font-size: 0.8rem;">Thème Actif</p>
                <p style="font-size: 1.25rem; font-weight: 700; color: var(--primary); text-transform: capitalize;">
                    <?php echo htmlspecialchars($settings['color_palette']); ?></p>
            </div>
            <div
                style="background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--border);">
                <p style="color: var(--text-muted); font-size: 0.8rem;">Police Active</p>
                <p style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">
                    <?php echo htmlspecialchars($settings['font_family']); ?></p>
            </div>
        </div>
    </div>

    <div class="admin-card reveal" style="animation-delay: 0.1s;">
        <h3>Actions Rapides</h3>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">Personnalisez votre portfolio pour qu'il reflète au
            mieux votre identité professionnelle.</p>
        <div style="display: flex; gap: 1rem;">
            <a href="settings.php" class="btn-admin" style="text-decoration:none;">Éditer le Profil</a>
            <a href="projects.php" class="btn-admin"
                style="text-decoration:none; background: transparent; border: 1px solid var(--primary); color: var(--primary);">Ajouter
                un Projet</a>
        </div>
    </div>
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