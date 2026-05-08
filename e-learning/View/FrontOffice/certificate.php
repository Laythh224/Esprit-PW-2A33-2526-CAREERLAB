<?php
/**
 * Attestation de complétion — variables ({{placeholder}}) :
 * {{NomUtilisateur}}  => $nomUtilisateur
 * {{NomFormation}}    => $nomFormation
 * {{DateCompletion}}  => $dateCompletion (libellé : ex. « Après la fin de la formation »)
 * {{CertificateID}}   => $certificateId
 */
$nomUtilisateur = $nomUtilisateur ?? '{{NomUtilisateur}}';
$nomFormation = $nomFormation ?? '{{NomFormation}}';
$dateCompletion = $dateCompletion ?? 'Après la fin de la formation';
$certificateId = $certificateId ?? '{{CertificateID}}';
$instructorName = $instructorName ?? '________________________';
$directorName = $directorName ?? '________________________';
$isPdf = !empty($isPdf);
$bodySentence = $bodySentence ?? sprintf(
    'Le présent certificat atteste que %s a suivi la formation « %s ». La remise officielle interviendra après la fin de la formation.',
    $nomUtilisateur,
    $nomFormation
);
$logoSrc = $logoSrc ?? (defined('E_LEARNING_WEB_BASE') ? E_LEARNING_WEB_BASE . '/View/assets/img/careerlab-logo.png' : '');
$inlineCertificateCss = $inlineCertificateCss ?? null;
$certificateEmbed = !empty($certificateEmbed ?? null);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Attestation de complétion — <?= htmlspecialchars((string) $nomUtilisateur, ENT_QUOTES, 'UTF-8') ?></title>
  <?php if (is_string($inlineCertificateCss) && $inlineCertificateCss !== ''): ?>
  <style><?= $inlineCertificateCss ?></style>
  <?php elseif (defined('E_LEARNING_WEB_BASE')): ?>
  <link rel="stylesheet" href="<?= htmlspecialchars(E_LEARNING_WEB_BASE, ENT_QUOTES, 'UTF-8') ?>/View/assets/css/certificate.css" />
  <?php endif; ?>
</head>
<body class="cert-body<?= $isPdf ? ' cert-body--pdf' : '' ?><?= $certificateEmbed ? ' cert-body--embed' : '' ?>">
  <?php if (!$isPdf && !$certificateEmbed): ?>
  <div class="cert-toolbar" role="toolbar" aria-label="Actions certificat">
    <button type="button" data-cert-print>Imprimer</button>
    <a class="cert-toolbar-link" href="<?= htmlspecialchars(E_LEARNING_WEB_BASE, ENT_QUOTES, 'UTF-8') ?>/index.php?r=front/formations">Retour au catalogue</a>
  </div>
  <?php endif; ?>

  <div class="cert-wrap<?= $certificateEmbed ? ' cert-wrap--embed' : '' ?>">
    <article class="cert-sheet" aria-label="Attestation de complétion">
      <div class="cert-watermark" aria-hidden="true">OFFICIEL</div>

      <div class="cert-frame-outer" aria-hidden="true"></div>
      <div class="cert-frame-inner" aria-hidden="true"></div>
      <span class="cert-corner cert-corner--tl" aria-hidden="true"></span>
      <span class="cert-corner cert-corner--tr" aria-hidden="true"></span>
      <span class="cert-corner cert-corner--bl" aria-hidden="true"></span>
      <span class="cert-corner cert-corner--br" aria-hidden="true"></span>
      <span class="cert-fleur cert-fleur--tl" aria-hidden="true"></span>
      <span class="cert-fleur cert-fleur--tr" aria-hidden="true"></span>
      <span class="cert-fleur cert-fleur--bl" aria-hidden="true"></span>
      <span class="cert-fleur cert-fleur--br" aria-hidden="true"></span>

      <div class="cert-inner">
        <?php if ($logoSrc !== ''): ?>
        <img class="cert-logo" src="<?= htmlspecialchars((string) $logoSrc, ENT_QUOTES, 'UTF-8') ?>" alt="CareerLab" width="180" height="52" />
        <?php endif; ?>

        <p class="cert-brand">CareerLab Learning</p>
        <h1 class="cert-title">Attestation de complétion</h1>
        <p class="cert-subtitle">Excellence en formation professionnelle</p>

        <div class="cert-divider" role="presentation"></div>

        <p class="cert-recipient-label">Le présent certificat est décerné à</p>
        <p class="cert-recipient-name"><?= htmlspecialchars((string) $nomUtilisateur, ENT_QUOTES, 'UTF-8') ?></p>

        <p class="cert-body-text"><?= htmlspecialchars($bodySentence, ENT_QUOTES, 'UTF-8') ?></p>

        <p class="cert-course-label">Formation</p>
        <p class="cert-course-name"><?= htmlspecialchars((string) $nomFormation, ENT_QUOTES, 'UTF-8') ?></p>

        <div class="cert-meta-row">
          <div class="cert-meta-item">
            <span>Délivrance</span>
            <strong><?= htmlspecialchars((string) $dateCompletion, ENT_QUOTES, 'UTF-8') ?></strong>
          </div>
          <div class="cert-meta-item">
            <span>Identifiant du certificat</span>
            <strong><?= htmlspecialchars((string) $certificateId, ENT_QUOTES, 'UTF-8') ?></strong>
          </div>
        </div>

        <div class="cert-seal" aria-hidden="true">
          <span class="cert-seal-star">★</span>
          Sceau<br />officiel
        </div>

      </div>

      <div class="cert-footer-id">N° <?= htmlspecialchars((string) $certificateId, ENT_QUOTES, 'UTF-8') ?></div>
    </article>
  </div>

  <?php if (!$isPdf && !$certificateEmbed): ?>
  <script src="<?= htmlspecialchars(E_LEARNING_WEB_BASE, ENT_QUOTES, 'UTF-8') ?>/View/assets/js/certificate.js" defer></script>
  <?php endif; ?>
</body>
</html>
