# 📝 Exemples de Code - QR Code Feature

## Résumé des modifications

### 1. TestController.php - Nouvelles imports

```php
<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Question;
use App\Models\Reponse;
use App\Models\Test;
use App\Models\TestResult;              // ← NOUVEAU
use App\Utils\TokenGenerator;            // ← NOUVEAU
use PDO;
use PDOException;
```

### 2. TestController.php - Génération du token et sauvegarde

**Avant :**
```php
$this->renderStandalone('front/test_resultat', [
    'testToken' => $testToken,
    'tests' => $result['tests'],
    'score' => $result['score'],
    'totalQuestions' => count($testPack),
]);
```

**Après :**
```php
// Generate token and save test result
$resultToken = TokenGenerator::generate(16);
$resultDetails = [];

foreach ($result['tests'] as $i => $test) {
    $resultDetails[] = [
        'number' => (int) $i + 1,
        'question' => (string) ($test['question']->getTexte() ?? ''),
        'questionId' => (int) ($test['question']->getId() ?? 0),
        'selectedAnswer' => (string) ($test['selectedAnswerText'] ?? ''),
        'correctAnswer' => (string) ($test['correctAnswerText'] ?? ''),
        'isCorrect' => (bool) $test['isCorrect'],
    ];
}

try {
    $testResultModel = new TestResult(
        $resultToken,
        $result['score'],
        count($testPack),
        $resultDetails,
        $testToken,
        null // No expiry for now
    );
    $testResultModel->save($pdo);
} catch (PDOException $e) {
    error_log('Error saving test result: ' . $e->getMessage());
}

$this->renderStandalone('front/test_resultat', [
    'testToken' => $testToken,
    'tests' => $result['tests'],
    'score' => $result['score'],
    'totalQuestions' => count($testPack),
    'resultToken' => $resultToken,  // ← NOUVEAU
]);
```

## Fichiers créés

### 3. app/utils/TokenGenerator.php

```php
<?php
declare(strict_types=1);

namespace App\Utils;

final class TokenGenerator
{
    /**
     * Generate a unique, secure token
     * @param int $length Length in bytes
     * @return string Hex-encoded random token
     */
    public static function generate(int $length = 16): string
    {
        return bin2hex(random_bytes($length));
    }
}

// Utilisation:
// $token = TokenGenerator::generate(16);
// Result: "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6" (32 hex chars)
```

### 4. app/utils/QRCodeGenerator.php

```php
<?php
declare(strict_types=1);

namespace App\Utils;

final class QRCodeGenerator
{
    private const GOOGLE_CHART_URL = 'https://chart.googleapis.com/chart';
    
    /**
     * Generate QR code URL using Google Charts API
     */
    public static function generateQRCodeUrl(string $data, int $size = 300): string
    {
        $encodedData = urlencode($data);
        
        return self::GOOGLE_CHART_URL . '?chs=' . $size . 'x' . $size 
            . '&chld=L|0&cht=qr&chl=' . $encodedData;
    }
}

// Utilisation:
// $url = "https://monsite.com/result.php?token=abc123...";
// $qrUrl = QRCodeGenerator::generateQRCodeUrl($url, 300);
// echo '<img src="' . $qrUrl . '" />';
```

### 5. app/models/TestResult.php

```php
<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

final class TestResult
{
    private int $id;
    private string $token;
    private ?string $testToken;
    private int $score;
    private int $totalQuestions;
    private array $resultDetails;
    private string $createdAt;
    private ?string $expiresAt;

    public function __construct(
        string $token,
        int $score,
        int $totalQuestions,
        array $resultDetails,
        ?string $testToken = null,
        ?string $expiresAt = null
    ) {
        $this->token = $token;
        $this->testToken = $testToken;
        $this->score = $score;
        $this->totalQuestions = $totalQuestions;
        $this->resultDetails = $resultDetails;
        $this->expiresAt = $expiresAt;
        $this->createdAt = (new \DateTime())->format('Y-m-d H:i:s');
    }

    /**
     * Save result to database
     */
    public function save(PDO $pdo): bool
    {
        $stmt = $pdo->prepare(
            'INSERT INTO test_results (token, test_token, score, total_questions, result_details, created_at, expires_at) 
             VALUES (:token, :test_token, :score, :total_questions, :result_details, :created_at, :expires_at)'
        );

        return $stmt->execute([
            ':token' => $this->token,
            ':test_token' => $this->testToken,
            ':score' => $this->score,
            ':total_questions' => $this->totalQuestions,
            ':result_details' => json_encode($this->resultDetails),
            ':created_at' => $this->createdAt,
            ':expires_at' => $this->expiresAt,
        ]);
    }

    /**
     * Retrieve result by token
     */
    public static function findByToken(PDO $pdo, string $token): ?self
    {
        $stmt = $pdo->prepare(
            'SELECT token, test_token, score, total_questions, result_details, created_at, expires_at 
             FROM test_results 
             WHERE token = :token'
        );

        $stmt->execute([':token' => $token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        // Check if expired
        if ($row['expires_at'] !== null) {
            $expiresAt = new \DateTime($row['expires_at']);
            if ($expiresAt < new \DateTime()) {
                return null;
            }
        }

        $result = new self(
            $row['token'],
            (int) $row['score'],
            (int) $row['total_questions'],
            json_decode($row['result_details'], true) ?? [],
            $row['test_token'],
            $row['expires_at']
        );

        return $result;
    }

    // Getters
    public function getToken(): string { return $this->token; }
    public function getScore(): int { return $this->score; }
    public function getTotalQuestions(): int { return $this->totalQuestions; }
    public function getResultDetails(): array { return $this->resultDetails; }
    public function getPercentage(): float {
        return $this->totalQuestions === 0 ? 0 : round(($this->score / $this->totalQuestions) * 100, 2);
    }
}

// Utilisation:
// $result = new TestResult($token, 7, 10, $details);
// $result->save($pdo);
// 
// $saved = TestResult::findByToken($pdo, $token);
// echo $saved->getScore() . '%';
```

### 6. result.php - Page publique (extrait)

```php
<?php
use App\Models\TestResult;
use App\Utils\QRCodeGenerator;

$token = trim((string) ($_GET['token'] ?? ''));

if ($token === '') {
    $error = 'Token invalide.';
} else {
    $pdo = require dirname(__DIR__) . '/db.php';
    $result = TestResult::findByToken($pdo, $token);
    
    if ($result === null) {
        $error = 'Résultat non trouvé ou expiré.';
    }
}
?>

<?php if ($result !== null): ?>
    <div class="score-section">
        <div class="score-display"><?php echo $result->getScore(); ?> / <?php echo $result->getTotalQuestions(); ?></div>
        <div class="score-percentage"><?php echo $result->getPercentage(); ?>%</div>
    </div>

    <?php foreach ($result->getResultDetails() as $detail): ?>
        <div class="question-item">
            <div class="question-text"><?php echo htmlspecialchars($detail['question']); ?></div>
            <div class="answer-item">
                <strong>Votre réponse:</strong>
                <?php echo htmlspecialchars($detail['selectedAnswer']); ?>
            </div>
            <?php if (!$detail['isCorrect']): ?>
                <div class="answer-item">
                    <strong>Bonne réponse:</strong>
                    <?php echo htmlspecialchars($detail['correctAnswer']); ?>
                </div>
            <?php endif; ?>
            <span class="badge <?php echo $detail['isCorrect'] ? 'bg-success' : 'bg-danger'; ?>">
                <?php echo $detail['isCorrect'] ? 'Correct' : 'Incorrect'; ?>
            </span>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
```

### 7. test_resultat.php - Génération du QR Code (extrait)

```php
<?php
use App\Utils\QRCodeGenerator;

$resultToken = isset($resultToken) ? (string) $resultToken : '';

// Build URL and QR code
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
$resultUrl = $baseUrl . '/result.php?token=' . urlencode($resultToken);
$qrCodeUrl = QRCodeGenerator::generateQRCodeUrl($resultUrl, 300);
?>

<div class="qr-section">
    <h3>📱 Accédez à vos résultats</h3>
    <img src="<?php echo htmlspecialchars($qrCodeUrl, ENT_QUOTES); ?>" 
         alt="QR Code" 
         class="qr-code-image">
    <p>Scannez ce QR code pour voir vos résultats en ligne</p>
    <small><?php echo htmlspecialchars($resultUrl); ?></small>
</div>
```

## Flux complet d'exécution

```
1. Utilisateur soumet le test
   └─ TestController::submit()

2. Calcul du score
   └─ TestController::storeAndScoreAnswers()

3. Génération du token
   └─ TokenGenerator::generate(16) 
   └─ Résultat: "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6"

4. Création du modèle
   └─ new TestResult($token, $score, $total, $details)

5. Sauvegarde en base
   └─ $result->save($pdo)
   └─ INSERT INTO test_results (token, score, ...)

6. Affichage du QR
   └─ test_resultat.php
   └─ QRCodeGenerator::generateQRCodeUrl()
   └─ Affiche le QR code + lien

7. Utilisateur scanne
   └─ URL: /result.php?token=abc123...

8. Affichage des résultats
   └─ result.php
   └─ TestResult::findByToken($token)
   └─ Affiche score et détails
```

## Base de données - Exemple d'enregistrement

```sql
SELECT * FROM test_results WHERE token = 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6';

-- Résultat:
-- id: 1
-- token: a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6
-- test_token: test_xyz_123
-- score: 7
-- total_questions: 10
-- result_details: [
--   {"number":1,"question":"...","selectedAnswer":"...","correctAnswer":"...","isCorrect":true},
--   {"number":2,"question":"...","selectedAnswer":"...","correctAnswer":"...","isCorrect":false},
--   ...
-- ]
-- created_at: 2024-05-03 14:30:00
-- expires_at: NULL (jamais)
```

---

**Tous les changements ont été appliqués ! ✅**

