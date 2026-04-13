# Gestion des Métiers - Application PHP CRUD

Une application web complète en PHP pour la gestion des métiers avec opérations CRUD (Create, Read, Update, Delete).

## 🚀 Fonctionnalités

- ✅ **CREATE** : Ajouter de nouveaux métiers
- ✅ **READ** : Afficher et rechercher des métiers
- ✅ **UPDATE** : Modifier les informations des métiers
- ✅ **DELETE** : Supprimer des métiers avec confirmation
- ✅ **SEARCH** : Recherche en temps réel par nom, description ou secteur

## 📋 Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur (ou MariaDB)
- Serveur web (Apache, Nginx) ou PHP built-in server
- Extension PDO MySQL activée

## 🛠️ Installation

### 1. Configuration de la base de données

1. Créez une base de données MySQL nommée `gestion_metiers`
2. Importez le fichier `database_setup.sql` dans phpMyAdmin ou via la ligne de commande :
   ```sql
   mysql -u root -p gestion_metiers < database_setup.sql
   ```

### 2. Configuration PHP

Modifiez le fichier `config.php` si nécessaire pour adapter les paramètres de connexion à votre base de données :

```php
define('DB_HOST', 'localhost');  // Adresse du serveur MySQL
define('DB_NAME', 'gestion_metiers');  // Nom de la base de données
define('DB_USER', 'root');  // Nom d'utilisateur MySQL
define('DB_PASS', '');  // Mot de passe MySQL
```

### 3. Lancement de l'application

#### Option 1 : Serveur PHP intégré (recommandé pour développement)
```bash
cd /chemin/vers/votre/projet
php -S localhost:8000
```
Puis ouvrez http://localhost:8000/metiers.php dans votre navigateur.

#### Option 2 : Avec un serveur web (Apache/Nginx)
Placez les fichiers dans le répertoire web de votre serveur et accédez à `metiers.php`.

## 📁 Structure des fichiers

```
/
├── config.php              # Configuration de la base de données
├── metiers.php             # Page principale avec interface CRUD
├── database_setup.sql      # Script SQL pour créer la DB
├── assets/                 # Ressources CSS/JS (Bootstrap, etc.)
└── README_PHP.md          # Ce fichier
```

## 🎯 Utilisation

### Ajouter un métier
1. Cliquez sur "Ajouter un Métier"
2. Remplissez le formulaire (nom requis)
3. Cliquez sur "Ajouter"

### Modifier un métier
1. Cliquez sur "Modifier" à côté du métier souhaité
2. Modifiez les informations dans le formulaire
3. Cliquez sur "Modifier"

### Supprimer un métier
1. Cliquez sur "Supprimer" à côté du métier souhaité
2. Confirmez la suppression dans la boîte de dialogue

### Rechercher des métiers
1. Utilisez la barre de recherche en haut du tableau
2. La recherche se fait sur le nom, la description et le secteur

## 🔧 Structure de la base de données

```sql
CREATE TABLE metiers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    salaire_moyen DECIMAL(10,2),
    secteur VARCHAR(100),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🛡️ Sécurité

- Utilisation de requêtes préparées (PDO) pour éviter les injections SQL
- Échappement des données HTML avec `htmlspecialchars()`
- Validation côté client et serveur

## 📝 Notes techniques

- L'application utilise PDO pour l'accès à la base de données
- Interface responsive avec Bootstrap 5
- JavaScript vanilla (pas de framework externe)
- Architecture MVC simplifiée

## 🐛 Dépannage

### Erreur de connexion à la base de données
- Vérifiez les paramètres dans `config.php`
- Assurez-vous que MySQL est démarré
- Vérifiez les permissions utilisateur MySQL

### Page blanche
- Vérifiez les logs d'erreur PHP
- Assurez-vous que l'extension PDO MySQL est activée
- Vérifiez les permissions des fichiers

### Problèmes de caractères accentués
- Assurez-vous que la base de données utilise l'encodage UTF-8
- Vérifiez l'encodage des fichiers PHP (UTF-8 sans BOM)

## 🤝 Contribution

Pour améliorer cette application :
1. Fork le projet
2. Créez une branche pour votre fonctionnalité
3. Committez vos changements
4. Poussez vers la branche
5. Ouvrez une Pull Request

## 📄 Licence

Ce projet est sous licence MIT - voir le fichier LICENSE pour plus de détails.