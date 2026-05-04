# 🚀 INSTALLATION RAPIDE - Fonctionnalité QR Code

## ✅ Étapes d'installation (5 minutes)

### 1️⃣ Exécuter la migration SQL

**Ouvrez phpMyAdmin ou MySQL CLI et exécutez :**

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

### 2️⃣ Vérifier les fichiers créés

✓ `app/models/TestResult.php` - Modèle résultats
✓ `app/utils/QRCodeGenerator.php` - Génération QR codes
✓ `app/utils/TokenGenerator.php` - Génération tokens
✓ `result.php` - Page publique résultats
✓ `app/views/front/test_resultat.php` - Vue modifiée (avec QR)
✓ `app/controllers/TestController.php` - Contrôleur modifié (sauvegarde)

### 3️⃣ Vérifier le dossier utils

Assurez-vous que le dossier `app/utils/` existe :

```bash
# Linux/Mac
mkdir -p app/utils

# Windows (PowerShell)
New-Item -ItemType Directory -Path "app/utils" -Force
```

### 4️⃣ Tester le flux complet

1. **Aller à** : http://localhost/index.php?route=team
2. **Créer un test**
3. **Répondre aux questions**
4. **Soumettre** → Voir le QR code
5. **Scanner le QR** ou **copier le lien** → Voir les résultats

## 🎯 Cas d'usage

### Pour l'utilisateur
```
1. Complète le test
2. Voir le score avec QR code
3. Scanner avec smartphone
4. Consulter résultats détaillés en ligne
```

### Pour l'admin
```
1. Résultats stockés dans test_results
2. Accessible via token unique sécurisé
3. Peut archiver ou supprimer après X jours
4. JSON stocke tous les détails
```

## 📊 Base de données

### Table test_results

| Colonne | Type | Description |
|---------|------|-------------|
| id | INT | ID auto-incrémentée |
| token | VARCHAR(64) | Token unique sécurisé (32 hex) |
| test_token | VARCHAR(64) | Lien vers le test original |
| score | INT | Nombre de bonnes réponses |
| total_questions | INT | Nombre total de questions |
| result_details | JSON | Détails complètes (questions/réponses) |
| created_at | DATETIME | Date/heure de création |
| expires_at | DATETIME NULL | Expiration optionnelle |

### Exemple de résultat JSON

```json
[
  {
    "number": 1,
    "question": "Quelle est la capitale de la France?",
    "questionId": 5,
    "selectedAnswer": "Paris",
    "correctAnswer": "Paris",
    "isCorrect": true
  },
  {
    "number": 2,
    "question": "Quelle est la formule de l'eau?",
    "questionId": 6,
    "selectedAnswer": "H2O2",
    "correctAnswer": "H2O",
    "isCorrect": false
  }
]
```

## 🔗 URLs importantes

### Pour l'utilisateur
- **Page résultats** : `http://localhost/result.php?token=abc123...`
- **Lien scannable** : Inclus dans le QR code

### Pour les tests
- **Créer test** : `http://localhost/index.php?route=team`
- **Passer test** : `http://localhost/index.php?route=team/test&token=test123...`
- **Voir résultats** : `http://localhost/index.php?route=team/test/submit` (POST)

## 🎨 Personnalisation possible

### Couleurs (dans test_resultat.php)
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
/* Changer les hex codes par vos couleurs */
```

### Taille QR code
```php
QRCodeGenerator::generateQRCodeUrl($url, 400);  // 300 par défaut, 400 pour plus grand
```

### Expiration des résultats
```php
// Dans TestController, modifier la sauvegarde pour ajouter expiration
$expiresAt = new DateTime();
$expiresAt->modify('+30 days');  // 30 jours d'accès
```

## ⚠️ Points importants

1. **Google Charts API** : Les QR codes sont générés via Google Charts (gratuit, pas de clé API)
2. **Sécurité** : Les tokens sont 32 caractères hex (256-bit), quasi impossible à deviner
3. **Base de données** : Les résultats sont archivés indéfiniment (ajouter expiration si nécessaire)
4. **Mobile-first** : La page `result.php` est entièrement responsive

## 🐛 Dépannage

### Erreur "Class not found: App\Utils\TokenGenerator"
→ Vérifier que `app/utils/` existe et contient les fichiers

### Erreur "Class not found: App\Models\TestResult"
→ Vérifier que `app/models/TestResult.php` est présent

### QR code ne s'affiche pas
→ Vérifier la connexion Internet (Google Charts API requiert une connexion)
→ Ou vérifier que l'URL du résultat est correcte

### Token non reconnu
→ Vérifier que la migration SQL a été exécutée
→ Vérifier que le token existe dans `test_results`

## 📧 Contact & Support

Pour questions ou améliorations, consulter :
- `QR_CODE_SETUP.md` (documentation complète)
- Code des fichiers (bien commentés)

---

**C'est prêt ! 🎉**

Vous pouvez maintenant :
- ✅ Générer des QR codes après chaque test
- ✅ Permettre aux utilisateurs de consulter leurs résultats en ligne
- ✅ Archiver les résultats de manière sécurisée
- ✅ Partager les résultats via lien ou QR code

