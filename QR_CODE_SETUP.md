# Fonctionnalité QR Code - Guide d'intégration

## 📋 Vue d'ensemble

Ce système génère automatiquement un **QR code avec lien de résultats** après chaque test. Les utilisateurs peuvent :
- Scanner le QR code avec leur smartphone
- Accéder à leurs résultats détaillés via une URL sécurisée
- Imprimer ou partager leurs résultats

## 🔧 Installation

### 1. Migration de la base de données

Exécutez la migration SQL pour créer la table `test_results` :

```bash
mysql -u root -p evaluation_db < database/migrations/add_test_results_table.sql
```

Ou directement dans phpMyAdmin/MySQL CLI :

```sql
USE evaluation_db;

CREATE TABLE IF NOT EXISTS test_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(64) NOT NULL UNIQUE,
    test_token VARCHAR(64),
    score INT NOT NULL,
    total_questions INT NOT NULL,
    result_details JSON NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME NULL,
    KEY idx_token (token),
    KEY idx_test_token (test_token),
    KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### 2. Structure des fichiers

Les nouveaux fichiers ont été créés :

```
app/
├── models/
│   └── TestResult.php           # Modèle pour gérer les résultats
├── utils/
│   ├── QRCodeGenerator.php       # Génération QR code (Google Charts API)
│   └── TokenGenerator.php        # Génération tokens sécurisés
└── views/
    └── front/
        └── test_resultat.php     # Vue résultats avec QR code (modifiée)

result.php                        # Page publique d'affichage des résultats

database/migrations/
└── add_test_results_table.sql   # Migration SQL
```

## 🚀 Fonctionnement

### Flux utilisateur

1. **Utilisateur termine le test**
   - Clic sur "Valider mes réponses"
   - Les réponses sont sauvegardées

2. **Génération du score et du token**
   ```php
   // TestController.php (ligne ~290)
   $resultToken = TokenGenerator::generate(16);  // Token unique de 32 caractères (hex)
   
   $testResultModel = new TestResult(
       $resultToken,
       $result['score'],
       count($testPack),
       $resultDetails,
       $testToken
   );
   $testResultModel->save($pdo);
   ```

3. **Affichage du QR code**
   - Page `test_resultat.php` affiche le QR code
   - Le QR contient l'URL : `https://monsite.com/result.php?token=XXXXX`

4. **Accès mobile via QR**
   - Scanner le QR code
   - Redirection vers `result.php?token=XXXXX`
   - Page `result.php` récupère les résultats détaillés

### Sécurité

- **Token unique** : 32 caractères hexadécimaux (256 bits)
- **Pas d'ID incrémental** : Impossible de deviner les tokens
- **Pas de données sensibles** : Le QR contient uniquement le token
- **Clé unique** : Chaque token pointe à exactement un résultat
- **Expiration optionnelle** : Champ `expires_at` disponible (NULL = jamais)

## 📁 Détails des fichiers

### TokenGenerator.php

Génère un token sécurisé en hexadécimal :

```php
$token = TokenGenerator::generate(16);  // 32 caractères hex
```

### QRCodeGenerator.php

Génère les QR codes via **Google Charts API** (sans dépendances) :

```php
$qrUrl = QRCodeGenerator::generateQRCodeUrl('https://...', 300);  // 300x300 px
$html = QRCodeGenerator::generateQRCodeHtml('https://...', 300);
```

### TestResult.php (Modèle)

Gère le stockage et la récupération des résultats :

```php
// Sauvegarde
$result = new TestResult($token, $score, $totalQuestions, $details);
$result->save($pdo);

// Récupération
$result = TestResult::findByToken($pdo, $token);
$score = $result->getScore();
$details = $result->getResultDetails();
```

### result.php (Page publique)

Page accessible au public pour afficher les résultats :

- URL : `https://monsite.com/result.php?token=ABC123...`
- Affiche : score, statistiques, détails des réponses
- Design responsive pour mobile
- Possibilité d'imprimer

### test_resultat.php (Vue modifiée)

Affiche immédiatement après le test :

- Score et pourcentage
- **QR code** (280x280 px)
- Lien direct à copier
- Détails des réponses
- Boutons d'action (nouveau test, imprimer)

## 🧪 Tests

### Tester localement

```php
// Générer un token de test
$token = \App\Utils\TokenGenerator::generate(16);
echo $token;  // Affiche par ex: a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6

// Générer un QR code
$url = "https://localhost/result.php?token=" . urlencode($token);
$qrUrl = \App\Utils\QRCodeGenerator::generateQRCodeUrl($url, 300);
// Copier l'URL dans un navigateur pour voir le QR code
```

### Accès à la page résultats

```
https://localhost/result.php?token=a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6
```

## 📱 Affichage mobile

La page `result.php` est **100% responsive** :

- ✅ Affichage adapté sur tous les appareils
- ✅ Boutons tactiles larges
- ✅ Texte lisible sur petit écran
- ✅ Impression optimisée

## 🔐 Archivage des résultats

Les résultats sont archivés dans `test_results` avec :

- **Token** : Identifiant unique (clé primaire)
- **Score** : Nombre de bonnes réponses
- **Total questions** : Nombre total de questions
- **Détails (JSON)** : Données complètes des réponses (question, réponses, etc.)
- **Created_at** : Date/heure de création
- **Expires_at** : Date d'expiration (NULL = pas d'expiration)

## 🛠️ Personalisation

### Ajouter une expiration

```php
// Dans TestController.php
$expiresAt = new DateTime();
$expiresAt->modify('+24 hours');  // 24 heures

$result = new TestResult(
    $resultToken,
    $score,
    $totalQuestions,
    $resultDetails,
    $testToken,
    $expiresAt->format('Y-m-d H:i:s')  // ← Ajouter ici
);
$result->save($pdo);
```

### Changer la taille du QR code

Dans `test_resultat.php` et `result.php` :

```php
$qrCodeUrl = QRCodeGenerator::generateQRCodeUrl($resultUrl, 400);  // 400x400 au lieu de 300x300
```

### Branding

Modifier les couleurs dans le CSS des fichiers :

```css
.score-box {
    background: linear-gradient(135deg, #YOUR_COLOR1 0%, #YOUR_COLOR2 100%);
}
```

## 📞 Support

Pour toute question ou amélioration, consultez le code source :
- `app/controllers/TestController.php` (génération du token)
- `app/models/TestResult.php` (stockage/récupération)
- `result.php` (affichage des résultats)

