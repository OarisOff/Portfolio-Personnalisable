# 🚀 Premium Portfolio Engine by Tropi-Code

Un système de portfolio complet, puissant et ultra-personnalisable, conçu avec PHP et SQLite pour une installation sans prise de tête.

## ✨ Fonctionnalités

- 🎨 **Moteur de Thèmes Dynamique** : Choisissez parmi 7 palettes de couleurs premium (Emerald, Ocean, Midnight, Rose, Sunset, Amethyst, Forest).
- ✍️ **Typographie Élégante** : 7 polices modernes intégrées (Inter, Poppins, Montserrat, Outfit, Playfair, Lora, Roboto).
- 🛠️ **Panel d'Administration Complet** : Interface de gestion sécurisée par mot de passe pour tout contrôler.
- 📁 **Gestion de Projets** : Ajoutez, modifiez et supprimez vos travaux avec images et liens externes.
- 📦 **Installation Simplifiée** : Un installeur visuel (`setup.php`) qui s'auto-supprime après configuration.
- 💾 **SQLite** : Pas de base de données MySQL à configurer. Tout est stocké dans un seul fichier local.
- 🌗 **Synchronisation Thématique** : L'admin s'adapte automatiquement au thème et à la police choisis.

## 🚀 Installation Rapide

1.  Copiez tous les fichiers sur votre serveur (ou dans votre dossier `htdocs` pour XAMPP).
2.  Accédez à l'URL : `http://votre-site.com/setup.php`.
3.  Remplissez le formulaire (Nom, Bio, Thème, Mot de passe Admin).
4.  **C'est tout !** L'installeur crée la base de données et se supprime par sécurité.

## 📂 Structure du Projet

- `index.php` : Le portfolio public dynamique.
- `style.css` : Le cœur du design avec les variables de thèmes.
- `setup.php` : L'installeur intelligent.
- `admin/` : Le panel de gestion complet.
## 🔐 Sécurité

Le panel admin est protégé par une session sécurisée et des mots de passe hachés (BCRYPT).

---

Réalisé avec ❤️ par **Tropi-Code**.
