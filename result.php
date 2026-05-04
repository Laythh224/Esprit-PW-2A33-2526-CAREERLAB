<?php

declare(strict_types=1);

// Configuration
$projectRoot = dirname(__FILE__);
require_once $projectRoot . '/app/models/TestResult.php';

use App\Models\TestResult;

$token = isset($_GET['token']) ? trim((string) $_GET['token']) : '';
$error = null;
$result = null;

// Validate token format (hex string, 32 characters for 16 bytes)
if ($token === '' || !preg_match('/^[a-f0-9]{32}$/i', $token)) {
    $error = 'Token invalide.';
} else {
    try {
        $pdo = require $projectRoot . '/db.php';
        $result = TestResult::findByToken($pdo, $token);
        
        if ($result === null) {
            $error = 'Résultat non trouvé ou expiré.';
        }
    } catch (Exception $e) {
        $error = 'Erreur serveur: ' . $e->getMessage();
        error_log($error);
    }
}

$e = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats du test - Career Lab</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .score-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .score-display {
            font-size: 3.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 10px;
        }

        .score-percentage {
            font-size: 1.5rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .score-label {
            font-size: 0.9rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stats-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .stat-card.correct {
            border-top: 4px solid #28a745;
        }

        .stat-card.incorrect {
            border-top: 4px solid #dc3545;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .question-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .question-item {
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #e9ecef;
        }

        .question-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .question-number {
            font-size: 0.85rem;
            color: #667eea;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .question-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 15px;
        }

        .answer-item {
            margin-bottom: 10px;
            padding: 12px;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .answer-label {
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .answer-text {
            color: #212529;
        }

        .correct-answer {
            background: #d4edda;
            border-left: 4px solid #28a745;
        }

        .wrong-answer {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .status-badge.correct {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.incorrect {
            background: #f8d7da;
            color: #721c24;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #c3e6cb;
        }

        .footer-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.8rem;
            }

            .score-display {
                font-size: 2.5rem;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .score-section,
            .question-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="container-fluid" style="max-width: 900px; margin: 0 auto;">
            <div style="text-align: center;">
                <h1>Résultats de votre test</h1>
                <p style="margin: 0; opacity: 0.9;">Career Lab Évaluation</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid" style="max-width: 900px; margin: 0 auto; padding: 30px 20px;">
        <?php if ($error !== null): ?>
            <div class="error-message">
                <strong>Erreur:</strong> <?php echo $e($error); ?>
            </div>
        <?php elseif ($result !== null): ?>
            <!-- Score Section -->
            <div class="score-section">
                <div class="score-display"><?php echo $result->getScore(); ?> / <?php echo $result->getTotalQuestions(); ?></div>
                <div class="score-percentage"><?php echo $result->getPercentage(); ?>%</div>
                <div class="score-label">Votre résultat</div>
            </div>

            <!-- Statistics -->
            <div class="stats-row">
                <div class="stat-card correct">
                    <div class="stat-number" style="color: #28a745;"><?php echo $result->getScore(); ?></div>
                    <div class="stat-label">Bonnes réponses</div>
                </div>
                <div class="stat-card incorrect">
                    <div class="stat-number" style="color: #dc3545;"><?php echo $result->getTotalQuestions() - $result->getScore(); ?></div>
                    <div class="stat-label">Mauvaises réponses</div>
                </div>
            </div>

            <!-- Questions Details -->
            <div class="question-section">
                <h2 style="margin-bottom: 25px; color: #212529; font-weight: 700;">Détail des réponses</h2>
                
                <?php foreach ($result->getResultDetails() as $detail): ?>
                    <div class="question-item">
                        <div class="question-number">Question <?php echo (int) $detail['number']; ?></div>
                        <div class="question-text"><?php echo $e($detail['question']); ?></div>

                        <div class="answer-item <?php echo $detail['isCorrect'] ? 'correct-answer' : ''; ?>">
                            <div class="answer-label">Votre réponse</div>
                            <div class="answer-text"><?php echo $e($detail['selectedAnswer']); ?></div>
                        </div>

                        <?php if (!$detail['isCorrect']): ?>
                            <div class="answer-item correct-answer">
                                <div class="answer-label">Bonne réponse</div>
                                <div class="answer-text"><?php echo $e($detail['correctAnswer']); ?></div>
                            </div>
                        <?php endif; ?>

                        <div class="status-badge <?php echo $detail['isCorrect'] ? 'correct' : 'incorrect'; ?>">
                            <?php echo $detail['isCorrect'] ? '✓ Correct' : '✗ Incorrect'; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Footer Info -->
            <div style="background: white; padding: 20px; border-radius: 12px; text-align: center; color: #6c757d; font-size: 0.9rem; margin-bottom: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                <p style="margin: 0;">
                    Généré le: <strong><?php echo (new DateTime($result->getCreatedAt()))->format('d/m/Y H:i'); ?></strong>
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="footer-buttons">
                <a href="index.php?route=team" class="btn btn-primary">Faire un nouveau test</a>
                <button onclick="window.print()" class="btn btn-outline-secondary">Imprimer les résultats</button>
            </div>

        <?php else: ?>
            <div class="success-message">
                Chargement des résultats...
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Auto-close any alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.error-message, .success-message');
            alerts.forEach(alert => {
                if (alert.querySelector('strong')?.textContent === 'Erreur:') {
                    // Keep error messages
                } else if (alert.textContent.includes('Chargement')) {
                    alert.style.display = 'none';
                }
            });
        }, 3000);
    </script>
</body>
</html>
