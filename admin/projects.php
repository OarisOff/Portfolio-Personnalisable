<?php
// admin/projects.php - Manage projects
require_once 'auth.php';

$message = '';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;
$settings = get_settings($pdo);

// Handle Delete
if ($action === 'delete' && $id > 0) {
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: projects.php?msg=supprimé");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $image_url = normalize_url($_POST['image_url'] ?? '');
    $project_link = normalize_url($_POST['project_link'] ?? '');

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, image_url = ?, project_link = ? WHERE id = ?");
        $stmt->execute([$title, $description, $image_url, $project_link, $id]);
        $message = "Projet mis à jour !";
    } else {
        $stmt = $pdo->prepare("INSERT INTO projects (title, description, image_url, project_link) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $image_url, $project_link]);
        $message = "Projet ajouté avec succès !";
    }
}

// Fetch project for editing
$edit_project = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $edit_project = $stmt->fetch();
}

$projects = $pdo->query("SELECT * FROM projects ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr" data-theme="<?php echo htmlspecialchars($settings['color_palette']); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets - Admin Portfolio</title>
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
                <li><a href="settings.php">Configuration</a></li>
                <li><a href="projects.php" class="active">Mes Projets</a></li>
                <li style="margin-top: 5rem;"><a href="../index.php" target="_blank"
                        style="background: rgba(255,255,255,0.05);">Voir le Site</a></li>
                <li><a href="logout.php" style="color: #ef4444;">Déconnexion</a></li>
            </ul>
        </nav>
    </aside>

    <main class="admin-content">
        <header style="margin-bottom: 4rem;">
            <p style="color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px;">Vos
                Travaux,</p>
            <h1 style="font-size: 3rem; font-weight: 900;">Gestion des Projets</h1>
        </header>

        <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 2.5rem; align-items: flex-start;">
            <!-- Form Card -->
            <div class="admin-card reveal">
                <h3><?php echo ($action === 'edit') ? 'Modifier le projet' : 'Nouveau Projet'; ?></h3>
                <?php if ($message): ?>
                    <div style="color:var(--primary); margin-bottom: 1.5rem;"><?php echo $message; ?></div> <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Titre du projet</label>
                        <input type="text" name="title" class="form-control"
                            value="<?php echo $edit_project ? htmlspecialchars($edit_project['title']) : ''; ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Description du projet</label>
                        <textarea name="description" class="form-control"
                            rows="3"><?php echo $edit_project ? htmlspecialchars($edit_project['description']) : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Lien de l'image (URL)</label>
                        <input type="text" name="image_url" class="form-control"
                            value="<?php echo $edit_project ? htmlspecialchars($edit_project['image_url']) : ''; ?>"
                            placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label>Lien du projet (URL)</label>
                        <input type="text" name="project_link" class="form-control"
                            value="<?php echo $edit_project ? htmlspecialchars($edit_project['project_link']) : ''; ?>"
                            placeholder="https://...">
                    </div>
                    <button type="submit" class="btn-admin"
                        style="width: 100%;"><?php echo ($action === 'edit') ? 'Mettre à jour le projet' : 'Publier le projet'; ?></button>
                    <?php if ($action === 'edit'): ?>
                        <a href="projects.php"
                            style="display:block; text-align:center; color:var(--text-muted); margin-top: 1rem; text-decoration:none; font-size: 0.8rem;">Annuler
                            l'édition</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- List Card -->
            <div class="admin-card reveal" style="padding: 1.5rem;">
                <h3>Liste de Projets</h3>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $proj): ?>
                            <tr>
                                <td style="font-weight: 700;"><?php echo htmlspecialchars($proj['title']); ?></td>
                                <td>
                                    <a href="projects.php?action=edit&id=<?php echo $proj['id']; ?>"
                                        style="color: var(--primary); text-decoration: none; margin-right: 1.25rem; font-weight: 700;">Éditer</a>
                                    <a href="projects.php?action=delete&id=<?php echo $proj['id']; ?>"
                                        style="color: #ef4444; text-decoration: none; font-weight: 700;"
                                        onclick="return confirm('Souhaitez-vous vraiment supprimer ce projet ?')">X</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($projects)): ?>
                            <tr>
                                <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 4rem 1rem;">
                                    Aucun projet pour le moment.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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