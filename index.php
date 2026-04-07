<?php
// index.php - Main Portfolio Page
require_once 'config.php';

// Fetch settings
$settings = get_settings($pdo);

// If no settings found (setup not run), redirect to setup
if (!$settings) {
    header("Location: setup.php");
    exit;
}

// Fetch projects
$projects = $pdo->query("SELECT * FROM projects ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr" data-theme="<?php echo htmlspecialchars($settings['color_palette']); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($settings['site_name']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($settings['quick_desc']); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Outfit:wght@400;700;900&family=Playfair+Display:wght@400;700;900&family=Roboto:wght@400;700;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="style.css">

    <style>
        body {
            font-family: '<?php echo htmlspecialchars($settings['font_family']); ?>', sans-serif;
        }
    </style>
</head>

<body>
    <header class="container">
        <nav>
            <div class="logo"><?php echo htmlspecialchars($settings['site_name']); ?></div>
            <div class="nav-links">
                <a href="#projets" class="project-link">Travaux</a>
                <a href="admin/login.php"
                    style="margin-left:2rem; opacity:0.5; color:inherit; text-decoration:none; font-size:0.8rem;">Admin</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <section class="hero reveal">
            <h1>Bienvenue, je suis <br><span
                    class="accent"><?php echo htmlspecialchars($settings['site_name']); ?></span></h1>
            <p><?php echo htmlspecialchars($settings['quick_desc']); ?></p>
            <div class="hero-actions">
                <a href="#projets" class="btn-primary">Voir mes projets</a>
            </div>
        </section>

        <section id="projets" class="reveal" style="padding-top: 5rem;">
            <h2 class="section-title">Mes Projets</h2>

            <div class="projects-grid">
                <?php foreach ($projects as $project): ?>
                    <div class="project-card">
                            <?php if (!empty($project['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($project['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($project['title']); ?>" class="project-img">
                            <?php else: ?>
                            <div class="project-img"
                                style="display:flex; align-items:center; justify-content:center; color:var(--text-muted); font-size:0.8rem;">
                                No Image</div>
                            <?php endif; ?>

                        <div class="project-info">
                            <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                            <p><?php echo htmlspecialchars($project['description']); ?></p>
                                <?php if (!empty($project['project_link'])): ?>
                                <a href="<?php echo htmlspecialchars($project['project_link']); ?>" target="_blank"
                                    class="project-link">Consulter →</a>
                                <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($projects)): ?>
                    <p style="text-align:center; grid-column: 1/-1; color: var(--text-muted);">Aucun projet publié pour le
                        moment.</p>
                <?php endif; ?>
            </div>
        </section>

        <section id="about" class="reveal section-card"
            style="margin-bottom: 5rem; padding: 3rem; background: var(--card-bg); border-radius: 2rem; border: 1px solid var(--border);">
            <h2 style="margin-bottom: 1.5rem; color: var(--primary);">À propos</h2>
            <p style="font-size: 1.2rem; line-height: 1.8; color: var(--text-muted);">
                <?php echo nl2br(htmlspecialchars($settings['about_text'])); ?>
            </p>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($settings['site_name']); ?>. Réalisé par
                Tropi-Code.</p>
        </div>
    </footer>

    <script>
        // Simple Intersection Observer for reveal animation
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
            el.style.transform = "translateY(30px)";
            el.style.transition = "all 0.8s ease forwards";
            observer.observe(el);
        });
    </script>
</body>

</html>