<?php

$nomFormation = isset($nomFormation) ? (string) $nomFormation : '';
$sessionId = isset($sessionId) ? (int) $sessionId : 0;
$preview = isset($preview) && is_array($preview) ? $preview : null;
$certificateEmbedUrl = isset($certificateEmbedUrl) ? (string) $certificateEmbedUrl : '';
$certificateFullUrl = isset($certificateFullUrl) ? (string) $certificateFullUrl : '';

require __DIR__ . '/../Layouts/front_header.php';

?>

<main class="main-wrap">

  <header class="page-head">
    <h1 class="page-title">Confirmer l'inscription</h1>
    <p class="page-subtitle">Formation : <strong><?= htmlspecialchars($nomFormation, ENT_QUOTES, 'UTF-8') ?></strong></p>
    <p class="page-subtitle text-muted">Les informations ci-dessous proviennent de votre compte CareerLab.</p>
  </header>

  <?php if (!empty($flash)): ?>
    <div id="front-flash-message" class="flash-banner flash-banner--error" data-message="<?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>" hidden></div>
  <?php endif; ?>

  <?php if ($preview !== null): ?>
    <div class="card inscription-card">
      <div class="card__meta"><span class="card__label-inline">Profil :</span> <?= htmlspecialchars((string) ($preview['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
      <div class="card__meta"><span class="card__label-inline">Nom :</span> <?= htmlspecialchars((string) ($preview['nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
      <div class="card__meta"><span class="card__label-inline">Prénom :</span> <?= htmlspecialchars((string) ($preview['prenom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
      <div class="card__meta"><span class="card__label-inline">E-mail :</span> <?= htmlspecialchars((string) ($preview['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
      <div class="card__meta"><span class="card__label-inline">Téléphone :</span> <?= htmlspecialchars((string) ($preview['telephone'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
      <div class="card__meta card__meta--spaced"><span class="card__label-inline">Niveau :</span> <?= htmlspecialchars((string) ($preview['niveau'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
      <div class="card__meta card__meta--spaced"><span class="card__label-inline">Âge enregistré :</span> <?= (int) ($preview['age'] ?? 0) ?> ans</div>

      <form method="post" action="index.php?r=front/inscription/submit" class="inscription-form">
        <input type="hidden" name="nom_formation" value="<?= htmlspecialchars($nomFormation, ENT_QUOTES, 'UTF-8') ?>" />
        <input type="hidden" name="session_id" value="<?= (int) $sessionId ?>" />
        <div class="actions-row">
          <button type="submit" class="btn btn--primary">Confirmer mon inscription</button>
          <a href="index.php?r=front/formations" class="btn btn--secondary">Annuler</a>
        </div>
      </form>
    </div>

    <?php if ($certificateEmbedUrl !== ''): ?>
    <section class="inscription-cert" aria-labelledby="inscription-cert-heading">
      <h2 id="inscription-cert-heading" class="inscription-cert__title">Aperçu du certificat</h2>
      <p class="inscription-cert__hint">Voici le certificat tel qu’il sera émis au format officiel après validation de votre inscription (données préremplies).</p>
      <div class="inscription-cert__frame">
        <iframe
          class="inscription-cert__iframe"
          title="Aperçu du certificat"
          src="<?= htmlspecialchars($certificateEmbedUrl, ENT_QUOTES, 'UTF-8') ?>"
          loading="lazy"
          referrerpolicy="strict-origin-when-cross-origin"
        ></iframe>
      </div>
      <div class="inscription-cert__actions">
        <a class="btn btn--secondary" href="<?= htmlspecialchars($certificateFullUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">Ouvrir en plein écran</a>
      </div>
    </section>
    <?php endif; ?>

  <?php endif; ?>

</main>

<script src="<?= htmlspecialchars(E_LEARNING_WEB_BASE, ENT_QUOTES, 'UTF-8') ?>/View/assets/js/front-flash-alert.js"></script>

<?php require __DIR__ . '/../Layouts/front_footer.php'; ?>
