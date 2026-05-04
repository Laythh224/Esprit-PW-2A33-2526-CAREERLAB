<?php

declare(strict_types=1);

$score = (int) ($score ?? 0);
$totalQuestions = (int) ($totalQuestions ?? 0);
$resultToken = isset($resultToken) ? (string) $resultToken : '';
$qrData = isset($qrData) ? (string) $qrData : '';
$qrCodeUrl = $qrData !== ''
    ? 'https://api.qrserver.com/v1/create-qr-code/?size=320x320&data=' . urlencode($qrData)
    : '';

$e = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Resultat du test - Career Lab</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        :root {
            --ink: #172033;
            --muted: #667085;
            --line: #d9e1ec;
            --brand: #2563eb;
            --brand-dark: #1d4ed8;
            --ok: #16a34a;
            --ok-soft: #dcfce7;
            --ok-line: #86efac;
            --fail: #dc2626;
            --fail-soft: #fee2e2;
            --fail-line: #fca5a5;
            --paper: #ffffff;
            --surface: #f4f7fb;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--ink);
            background: var(--surface);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .page-shell {
            width: min(100%, 720px);
            margin: 0 auto;
            padding: 18px 14px 32px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 0 16px;
        }

        .brand-logo {
            height: 44px;
            width: auto;
            padding: 5px 9px;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 20px rgba(23, 32, 51, 0.08);
        }

        .panel {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 18px 44px rgba(23, 32, 51, 0.10);
            overflow: hidden;
        }

        .panel-header {
            padding: 22px 18px 12px;
            text-align: center;
        }

        .panel-title {
            margin: 0;
            font-size: 1.45rem;
            line-height: 1.2;
            font-weight: 750;
        }

        .panel-subtitle {
            margin: 8px auto 0;
            max-width: 32rem;
            color: var(--muted);
            font-size: 0.96rem;
            line-height: 1.45;
        }

        .qr-area {
            padding: 12px 18px 22px;
            text-align: center;
        }

        .qr-frame {
            display: inline-flex;
            padding: 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fff;
        }

        .qr-code-image {
            width: min(78vw, 280px);
            aspect-ratio: 1;
            object-fit: contain;
            display: block;
        }

        .page-actions {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            margin-top: 18px;
        }

        .page-action {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 14px;
            border-radius: 8px;
            font-weight: 800;
            text-decoration: none;
            transition: transform 160ms ease, filter 160ms ease;
        }

        .page-action:active {
            transform: scale(0.98);
        }

        .page-action.primary {
            color: #fff;
            background: var(--brand);
        }

        .page-action.secondary {
            color: var(--brand-dark);
            border: 1px solid #b7caf8;
            background: #eef4ff;
        }

        .scanner-area {
            border-top: 1px solid var(--line);
            padding: 18px;
            background: #fbfdff;
        }

        .scanner-area.is-hidden {
            display: none;
        }

        .scanner-actions {
            margin-bottom: 14px;
        }

        .action-button {
            width: 100%;
            min-height: 48px;
            border: 0;
            border-radius: 8px;
            font-weight: 700;
            color: #fff;
            background: var(--brand);
            transition: transform 160ms ease, background 160ms ease;
        }

        .action-button:active {
            transform: scale(0.98);
        }

        #qr-reader {
            width: 100%;
            min-height: 260px;
            border: 1px solid var(--line);
            border-radius: 8px;
            overflow: hidden;
            background: #0f172a;
        }

        #qr-reader:empty {
            display: none;
        }

        .scan-status {
            min-height: 22px;
            margin-top: 10px;
            color: var(--muted);
            font-size: 0.92rem;
            text-align: center;
        }

        .result-overlay {
            position: fixed;
            inset: 0;
            z-index: 1060;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            background: rgba(15, 23, 42, 0.64);
            backdrop-filter: blur(12px);
        }

        .result-overlay.is-open {
            display: flex;
        }

        .result-popup {
            width: min(100%, 420px);
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.28);
            transform: translateY(18px) scale(0.96);
            opacity: 0;
            animation: resultIn 220ms ease forwards;
            overflow: hidden;
            --result-color: var(--ok);
            --result-soft: var(--ok-soft);
            --result-line: var(--ok-line);
            --progress: 0deg;
        }

        .result-popup.success {
            border-top: 7px solid var(--ok);
            --result-color: var(--ok);
            --result-soft: var(--ok-soft);
            --result-line: var(--ok-line);
        }

        .result-popup.failure {
            border-top: 7px solid var(--fail);
            --result-color: var(--fail);
            --result-soft: var(--fail-soft);
            --result-line: var(--fail-line);
        }

        @keyframes resultIn {
            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .result-body {
            padding: 24px;
            text-align: center;
        }

        .result-kicker {
            margin: 0 0 4px;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .result-heading {
            margin: 0 0 18px;
            color: var(--ink);
            font-size: 1.4rem;
            line-height: 1.2;
            font-weight: 850;
        }

        .result-ring {
            width: 168px;
            height: 168px;
            margin: 0 auto 18px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background:
                conic-gradient(var(--result-color) var(--progress), #edf2f7 0),
                #edf2f7;
            box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.04);
        }

        .result-ring-inner {
            width: 128px;
            height: 128px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #fff;
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.10);
        }

        .result-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 32px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .result-status.success {
            color: #166534;
            background: var(--ok-soft);
        }

        .result-status.failure {
            color: #991b1b;
            background: var(--fail-soft);
        }

        .result-score {
            margin: 0;
            color: var(--result-color);
            font-size: 2.3rem;
            line-height: 1;
            font-weight: 850;
        }

        .result-total {
            margin-top: 5px;
            color: var(--muted);
            font-size: 0.84rem;
            font-weight: 700;
        }

        .result-message {
            margin: 12px 0 0;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.45;
        }

        .result-date {
            margin-top: 8px;
            color: #475467;
            font-size: 0.88rem;
            font-weight: 700;
        }

        .result-metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 18px;
        }

        .metric-item {
            min-width: 0;
            padding: 11px 8px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #f8fafc;
        }

        .metric-value {
            display: block;
            color: var(--ink);
            font-size: 1.02rem;
            font-weight: 850;
            line-height: 1.1;
        }

        .metric-label {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .result-note {
            margin-top: 16px;
            padding: 12px;
            border: 1px solid var(--result-line);
            border-radius: 8px;
            color: var(--ink);
            background: var(--result-soft);
            font-size: 0.92rem;
            line-height: 1.4;
        }

        .close-button {
            width: 100%;
            min-height: 50px;
            border: 0;
            color: #fff;
            background: var(--result-color);
            font-weight: 750;
            transition: filter 160ms ease;
        }

        .close-button:active {
            filter: brightness(0.92);
        }

        @media (min-width: 640px) {
            .page-shell {
                padding-top: 30px;
            }

            .page-actions {
                grid-template-columns: 1fr 1fr;
            }

            .action-button {
                width: min(100%, 360px);
                display: block;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <main class="page-shell">
        <div class="topbar">
            <img src="img/career_lab.png" alt="Career Lab" class="brand-logo">
        </div>

        <section class="panel" aria-labelledby="pageTitle">
            <div class="panel-header">
                <h1 class="panel-title" id="pageTitle">QR code du resultat</h1>
                <p class="panel-subtitle">Scannez le QR code pour voir vos resultats.</p>
            </div>

            <div class="qr-area">
                <?php if ($qrCodeUrl !== ''): ?>
                    <div class="qr-frame">
                        <img src="<?php echo $e($qrCodeUrl); ?>" alt="QR code du resultat" class="qr-code-image">
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mb-0" role="alert">QR code indisponible.</div>
                <?php endif; ?>

                <?php if (isset($_GET['debug']) && $_GET['debug'] === '1'): ?>
                    <pre class="mt-3 text-start small bg-light p-2 rounded"><?php echo $e($qrData); ?></pre>
                <?php endif; ?>

                <div class="page-actions">
                    <a class="page-action primary" href="http://localhost/projet_web/index.php?route=team">Refaire un nouveau test</a>
                    <a class="page-action secondary" href="http://localhost/projet_web/indexF.php">Home</a>
                </div>
            </div>

            <div class="scanner-area">
                <div class="scanner-actions">
                    <button type="button" class="action-button" id="startScanner">Scanner avec la camera</button>
                </div>
                <div id="qr-reader" aria-live="polite"></div>
                <div class="scan-status" id="scanStatus">Pret a scanner.</div>
            </div>
        </section>
    </main>

    <div class="result-overlay" id="resultOverlay" role="dialog" aria-modal="true" aria-labelledby="resultTitle">
        <div class="result-popup" id="resultPopup">
            <div class="result-body">
                <p class="result-kicker">Resultat scanne</p>
                <h2 class="result-heading" id="resultTitle">Synthese du test</h2>
                <div class="result-ring" aria-hidden="true">
                    <div class="result-ring-inner">
                        <div>
                            <div class="result-score" id="resultScore"></div>
                            <div class="result-total" id="resultTotal"></div>
                        </div>
                    </div>
                </div>
                <div class="result-status" id="resultStatus"></div>
                <p class="result-message" id="resultMessage"></p>
                <div class="result-date" id="resultDate"></div>
                <div class="result-metrics" aria-label="Details du resultat">
                    <div class="metric-item">
                        <span class="metric-value" id="metricScore"></span>
                        <span class="metric-label">Score</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-value" id="metricPercent"></span>
                        <span class="metric-label">Reussite</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-value" id="metricStatus"></span>
                        <span class="metric-label">Statut</span>
                    </div>
                </div>
                <div class="result-note" id="resultNote"></div>
            </div>
            <button type="button" class="close-button" id="closeResult">Fermer</button>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" defer></script>
    <script>
        (function () {
            var scanner = null;
            var scannerRunning = false;
            var scanLocked = false;

            var startButton = document.getElementById('startScanner');
            var statusEl = document.getElementById('scanStatus');
            var scannerArea = document.querySelector('.scanner-area');
            var overlay = document.getElementById('resultOverlay');
            var popup = document.getElementById('resultPopup');
            var resultStatus = document.getElementById('resultStatus');
            var resultScore = document.getElementById('resultScore');
            var resultTotal = document.getElementById('resultTotal');
            var resultMessage = document.getElementById('resultMessage');
            var resultDate = document.getElementById('resultDate');
            var metricScore = document.getElementById('metricScore');
            var metricPercent = document.getElementById('metricPercent');
            var metricStatus = document.getElementById('metricStatus');
            var resultNote = document.getElementById('resultNote');
            var closeButton = document.getElementById('closeResult');

            function setStatus(message) {
                statusEl.textContent = message;
            }

            function parseQrPayload(text) {
                var trimmed = String(text || '').trim();

                if (/^https?:\/\//i.test(trimmed) || /^localhost\b/i.test(trimmed)) {
                    throw new Error('Ce QR contient une URL. Scan refuse.');
                }

                var data = {};

                if (trimmed.charAt(0) === '{') {
                    data = JSON.parse(trimmed);
                } else {
                    var scoreMatch = trimmed.match(/votre score est\s+(\d+)\s*\/\s*(\d+)/i);
                    var percentageMatch = trimmed.match(/pourcentage[^0-9]*(\d+(?:[.,]\d+)?)\s*%/i);
                    var dateMatch = trimmed.match(/créé le\s+(.+)$/i);
                    var messageParts = trimmed.split(/votre score est/i);

                    if (!scoreMatch) {
                        throw new Error('QR resultat invalide.');
                    }

                    data = {
                        score: scoreMatch[1],
                        total: scoreMatch[2],
                        percentage: percentageMatch ? percentageMatch[1] + '%' : '',
                        status: '',
                        message: messageParts[0].replace(/[!\s]+$/g, ''),
                        cree_le: dateMatch ? 'Créé le ' + dateMatch[1].trim() : ''
                    };
                }

                var score = Number(data.score);
                var total = Number(data.total);

                if (!Number.isFinite(score) || !Number.isFinite(total) || total < 1) {
                    throw new Error('Donnees de score invalides.');
                }

                return {
                    score: score,
                    total: total,
                    percentage: parseFloat(String(data.percentage || Math.round((score / total) * 100)).replace(',', '.')),
                    status: String(data.status || '').toLowerCase(),
                    message: String(data.message || ''),
                    creeLe: String(data.cree_le || '')
                };
            }

            function showResult(data) {
                var passed = data.status === 'reussi' || data.score >= Math.ceil(data.total * 0.5);
                var tone = passed ? 'success' : 'failure';
                var percentage = Math.max(0, Math.min(100, Number(data.percentage || 0)));
                var statusLabel = passed ? 'Reussi' : 'Echoue';

                popup.className = 'result-popup ' + tone;
                popup.style.setProperty('--progress', (percentage * 3.6) + 'deg');
                resultStatus.className = 'result-status ' + tone;
                resultStatus.textContent = statusLabel;
                resultScore.textContent = data.score;
                resultTotal.textContent = 'sur ' + data.total;
                resultMessage.textContent = data.message || (passed ? 'Bravo, vous avez reussi le test.' : 'Resultat insuffisant, continuez a reviser.');
                resultDate.textContent = data.creeLe || '';
                metricScore.textContent = data.score + '/' + data.total;
                metricPercent.textContent = Math.round(percentage) + '%';
                metricStatus.textContent = statusLabel;
                resultNote.textContent = passed
                    ? 'Votre resultat est valide. Vous pouvez fermer cette carte et continuer.'
                    : 'Le resultat reste consultable ici. Prenez le temps de revoir les points difficiles.';

                overlay.classList.add('is-open');
            }

            function handleScan(decodedText) {
                if (scanLocked) return;
                scanLocked = true;

                try {
                    showResult(parseQrPayload(decodedText));
                    setStatus('Resultat lu avec succes.');
                    stopScanner();
                } catch (err) {
                    scanLocked = false;
                    setStatus(err && err.message ? err.message : 'Impossible de lire ce QR.');
                }
            }

            function stopScanner() {
                if (!scanner || !scannerRunning) return;

                scanner.stop().then(function () {
                    scannerRunning = false;
                    return scanner.clear();
                }).catch(function () {
                    scannerRunning = false;
                });
            }

            function startScanner() {
                scanLocked = false;

                if (!window.Html5Qrcode) {
                    setStatus('La librairie de scan QR n est pas encore chargee.');
                    return;
                }

                if (scannerRunning) {
                    setStatus('Scanner deja actif.');
                    return;
                }

                scanner = scanner || new Html5Qrcode('qr-reader');
                setStatus('Ouverture de la camera...');

                Html5Qrcode.getCameras().then(function (cameras) {
                    if (!cameras || cameras.length === 0) {
                        throw new Error('Aucune camera detectee.');
                    }

                    var backCamera = cameras.find(function (camera) {
                        return /back|rear|environment/i.test(camera.label || '');
                    });
                    var cameraId = backCamera ? backCamera.id : cameras[0].id;

                    return scanner.start(
                        cameraId,
                        { fps: 10, qrbox: { width: 240, height: 240 }, aspectRatio: 1 },
                        handleScan,
                        function () {}
                    );
                }).then(function () {
                    scannerRunning = true;
                    setStatus('Placez le QR code dans le cadre.');
                }).catch(function (err) {
                    setStatus(err && err.message ? err.message : 'Camera indisponible.');
                });
            }

            function isMobileScannerDevice() {
                return window.matchMedia('(max-width: 820px)').matches
                    && (navigator.maxTouchPoints || 0) > 0;
            }

            if (isMobileScannerDevice()) {
                startButton.addEventListener('click', startScanner);
            } else {
                scannerArea.classList.add('is-hidden');
            }

            closeButton.addEventListener('click', function () {
                overlay.classList.remove('is-open');
                scanLocked = false;
            });

            overlay.addEventListener('click', function (event) {
                if (event.target === overlay) {
                    overlay.classList.remove('is-open');
                    scanLocked = false;
                }
            });
        })();
    </script>
</body>
</html>
