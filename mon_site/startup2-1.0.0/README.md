# Gestion des Métiers - PHP CRUD Application

Une application web PHP complète pour gérer un répertoire de métiers/professions avec interface moderne utilisant Bootstrap 5.

## 🚀 Fonctionnalités

- ✅ **CRUD complet** : Créer, Lire, Modifier, Supprimer des métiers
- ✅ **Recherche** : Recherche en temps réel par nom, description ou secteur
- ✅ **Statistiques** : Tableaux de bord avec analyses des données
- ✅ **Interface moderne** : Design responsive avec Bootstrap 5
- ✅ **Sécurité** : Requêtes préparées PDO, validation des données
- ✅ **Architecture MVC** : Séparation claire des responsabilités

## 📋 Prérequis

- **PHP 7.4+** avec extension PDO MySQL
- **MySQL 5.7+** ou **MariaDB 10.0+**
- **Serveur web** (Apache/Nginx) avec mod_rewrite activé
- **Composer** (optionnel, pour la gestion des dépendances)

## 🛠️ Installation

### 1. Cloner ou télécharger le projet

```bash
# Si vous utilisez Git
git clone <repository-url>
cd gestion-metiers-php
```

### 2. Configuration de la base de données

Créer une base de données MySQL et exécuter le script SQL :

```sql
-- Exécuter le contenu du fichier database_setup.sql
-- Ou créer manuellement la table :

CREATE DATABASE gestion_metiers;
USE gestion_metiers;

CREATE TABLE metiers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    salaire_moyen DECIMAL(10,2),
    secteur VARCHAR(100),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3. Configuration de l'application

Modifier le fichier `config.php` avec vos paramètres de base de données :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gestion_metiers');
define('DB_USER', 'votre_utilisateur');
define('DB_PASS', 'votre_mot_de_passe');
```

### 4. Déploiement

Placer les fichiers dans le répertoire web de votre serveur (ex: `/var/www/html/gestion-metiers/` ou `htdocs/gestion-metiers/`).

### 5. Tester l'application (optionnel)

```bash
php test.php
```

Ce script vérifie automatiquement :
- ✅ Connexion à la base de données
- ✅ Création de la table si nécessaire
- ✅ Opérations CRUD (Créer, Lire, Modifier, Supprimer)
- ✅ Fonctionnalités de recherche et statistiques
- ✅ Architecture MVC et séparation frontend/backend

## 📖 Utilisation

### Accès à l'application

Ouvrir votre navigateur et accéder à :
```
http://localhost/gestion-metiers/index.php
```

### Navigation

- **Liste des métiers** : Vue d'ensemble avec recherche et actions
- **Ajouter un métier** : Formulaire de création
- **Modifier un métier** : Édition via les boutons d'action
- **Supprimer un métier** : Confirmation avant suppression
- **Statistiques** : Analyses et graphiques des données

### Actions disponibles

| Action | URL | Description |
|--------|-----|-------------|
| Liste | `?action=list` | Afficher tous les métiers |
| Créer | `?action=create` | Formulaire d'ajout |
| Modifier | `?action=edit&id=1` | Formulaire d'édition |
| Supprimer | `?action=delete&id=1` | Supprimer un métier |
| Rechercher | `?action=search&q=dev` | Recherche par terme |
| Statistiques | `?action=stats` | Vue des statistiques |

## 🏗️ Architecture

```
gestion-metiers/
├── index.php                    # Point d'entrée principal - Routeur
├── config.php                   # Configuration base de données
├── database_setup.sql           # Script de création BD
├── controllers/
│   └── MetierController.php     # Logique de contrôle - Prépare les données
├── models/
│   └── MetierManager.php        # Gestion des données - CRUD + Stats
└── views/
    ├── metiers.php             # Template HTML liste des métiers
    ├── form.php                # Template HTML formulaire ajout/édition
    ├── stats.php               # Template HTML statistiques
    └── error.php               # Template HTML erreurs
```

### Architecture MVC Pure

- **Modèle (Model)** : `MetierManager` - Gestion des données et logique métier
- **Vue (View)** : Templates HTML purs dans `views/` - Interface utilisateur uniquement
- **Contrôleur (Controller)** : `MetierController` - Logique de traitement et préparation des données

### Séparation Frontend/Backend

✅ **Backend PHP** : Toute la logique métier, validation, traitement des données
✅ **Frontend HTML** : Templates purs avec variables PHP simples
✅ **Sécurité** : Échappement HTML, validation côté serveur
✅ **Maintenance** : Code organisé et maintenable

## 🔒 Sécurité

- **Requêtes préparées PDO** : Protection contre les injections SQL
- **Validation des données** : Côté serveur et client
- **Échappement HTML** : Protection XSS
- **Gestion des erreurs** : Logs et messages utilisateur appropriés

## 🎨 Interface Utilisateur

### Technologies frontend

- **Bootstrap 5** : Framework CSS responsive
- **Font Awesome 6** : Icônes vectorielles
- **JavaScript vanilla** : Interactions dynamiques
- **CSS3** : Styles personnalisés

### Fonctionnalités UI/UX

- Design responsive (mobile-first)
- Messages de confirmation pour les actions destructives
- Validation en temps réel des formulaires
- Recherche instantanée avec debounce
- Indicateurs de chargement et retours visuels

## 📊 Statistiques

L'application fournit plusieurs types de statistiques :

- **Métriques générales** : Nombre total de métiers, secteurs représentés
- **Analyse salariale** : Moyennes, minimums, maximums, répartitions
- **Répartition sectorielle** : Graphiques et pourcentages par secteur
- **Évolution temporelle** : Derniers métiers ajoutés

## 🔧 Développement

### Structure des fichiers

#### Configuration (`config.php`)
```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gestion_metiers');
define('DB_USER', 'root');
define('DB_PASS', '');

// Fonction de connexion à la base de données
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
?>
```

#### Modèle (`models/MetierManager.php`)
```php
class MetierManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllMetiers() {
        // Logique de récupération
    }

    public function ajouterMetier($data) {
        // Logique d'ajout
    }
    // ... autres méthodes
}
```

#### Contrôleur (`controllers/MetierController.php`)
```php
class MetierController {
    private $metierManager;

    public function __construct() {
        $this->metierManager = new MetierManager(getDBConnection());
    }

    public function index() {
        return $this->metierManager->getAllMetiers();
    }

    public function store() {
        // Validation et traitement
    }
    // ... autres méthodes
}
```

### Tests et débogage

Pour activer les logs d'erreur PHP :
```php
// Dans config.php ou index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'logs/php_errors.log');
```

## 🔧 Dépannage

### Problème : "Les données disparaissent quand j'accède au frontend"

**Cause probable** : Problème de connexion à la base de données ou table non créée.

**Solutions** :

1. **Utilisez le diagnostic intégré** :
   - Cliquez sur le bouton "Diagnostic" dans l'interface
   - Suivez les recommandations affichées

2. **Vérifiez la configuration MySQL** :
   - Assurez-vous que MySQL est installé et démarré
   - Vérifiez les identifiants dans `config.php`

3. **Créez manuellement la base** :
   ```sql
   CREATE DATABASE gestion_metiers;
   USE gestion_metiers;
   SOURCE database_setup.sql;
   ```

4. **Vérifiez les permissions MySQL** :
   ```sql
   GRANT ALL PRIVILEGES ON gestion_metiers.* TO 'root'@'localhost';
   FLUSH PRIVILEGES;
   ```

### Problème : "Erreur de connexion à la base de données"

- Vérifiez que MySQL est démarré
- Confirmez les identifiants dans `config.php`
- Testez la connexion avec un outil comme phpMyAdmin

### Problème : "Table metiers n'existe pas"

- Exécutez le script `database_setup.sql`
- Ou laissez l'application créer automatiquement la table (elle le fait au premier accès)

### Problème : "PHP n'est pas reconnu en ligne de commande"

- Installez PHP et ajoutez-le au PATH système
- Ou utilisez un environnement de développement intégré (XAMPP, WAMP, MAMP)

## 📞 Support

Pour obtenir de l'aide :
1. Utilisez l'outil de diagnostic intégré (`diagnostic.php`)
2. Vérifiez les logs d'erreur PHP
3. Consultez la documentation MySQL pour les problèmes de base de données

---

**Note** : Cette application est conçue pour un environnement de développement. Pour la production, configurez correctement les permissions et la sécurité.

## 🚀 Déploiement en production

### Optimisations recommandées

1. **Cache des requêtes** : Implémenter un système de cache (Redis/Memcached)
2. **Minification** : Compresser les assets CSS/JS
3. **HTTPS** : Activer SSL/TLS
4. **Logs** : Configurer la rotation des logs
5. **Sauvegarde** : Automatiser les sauvegardes de base de données

### Variables d'environnement

Utiliser des variables d'environnement pour la configuration :
```bash
# .env
DB_HOST=localhost
DB_NAME=gestion_metiers
DB_USER=prod_user
DB_PASS=secure_password
```

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 🤝 Contribution

Les contributions sont les bienvenues ! Veuillez :

1. Forker le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## 📞 Support

Pour toute question ou problème :

1. Vérifier la documentation
2. Consulter les logs d'erreur
3. Ouvrir une issue sur GitHub
4. Contacter l'équipe de développement

---

**Développé avec ❤️ en PHP et Bootstrap**

***
### [Kaiadmin - Futuristic Dashboard](https://themekita.com/demo-kaiadmin-pro-bootstrap-dashboard/livepreview/examples/demo9/)
![Kaiadmin - Futuristic Dashboard](https://github.com/Hizrian/kaiadmin-lite/assets/10692084/83f79f3d-d248-4d01-ac15-9c98bee3ca9f)








