<?php

$nomFormation = isset($nomFormation) ? (string) $nomFormation : '';

$oldInput = isset($oldInput) && is_array($oldInput) ? $oldInput : [];

$sessionId = isset($sessionId) ? (int) $sessionId : (int) ($oldInput['session_id'] ?? 0);

require __DIR__ . '/../Layouts/front_header.php';

?>

<main class="main-wrap">

  <header class="page-head">
    <h1 class="page-title">Inscription a une session</h1>
    <p class="page-subtitle">Formation selectionnee : <strong><?= htmlspecialchars($nomFormation, ENT_QUOTES, 'UTF-8') ?></strong></p>
  </header>



  <?php if (!empty($flash)): ?>

    <div id="front-flash-message" class="flash-banner flash-banner--error" data-message="<?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>" hidden></div>

  <?php endif; ?>



  <div class="card inscription-card">

    <form method="post" action="index.php?r=front/inscription/submit" id="inscription-form" class="inscription-form" novalidate>

      <input type="hidden" name="nom_formation" value="<?= htmlspecialchars($nomFormation, ENT_QUOTES, 'UTF-8') ?>" />

      <input type="hidden" name="session_id" value="<?= (int) $sessionId ?>" />

      <div class="form-grid">

        <div class="form-group">

          <label for="inscription-cin">CIN (8 chiffres)</label>

          <input id="inscription-cin" type="text" name="cin" maxlength="8" inputmode="numeric" autocomplete="off" value="<?= htmlspecialchars((string) ($oldInput['cin'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
          <small class="inscription-form__hint">Exemple: 12345678</small>
          <p class="form-error" data-error-for="cin" id="error-cin"></p>

        </div>

        <div class="form-group">

          <label for="inscription-nom">Nom</label>

          <input id="inscription-nom" type="text" name="nom" value="<?= htmlspecialchars((string) ($oldInput['nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
          <p class="form-error" data-error-for="nom" id="error-nom"></p>

        </div>

        <div class="form-group">

          <label for="inscription-prenom">Prénom</label>

          <input id="inscription-prenom" type="text" name="prenom" value="<?= htmlspecialchars((string) ($oldInput['prenom'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
          <p class="form-error" data-error-for="prenom" id="error-prenom"></p>

        </div>

        <div class="form-group form-group--full">

          <label for="inscription-adresse">E-mail</label>

          <input id="inscription-adresse" type="email" name="adresse" autocomplete="email" value="<?= htmlspecialchars((string) ($oldInput['adresse'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
          <p class="form-error" data-error-for="adresse" id="error-adresse"></p>

        </div>

        <div class="form-group">

          <label for="inscription-niveau">Niveau</label>

          <input id="inscription-niveau" type="text" name="niveau" value="<?= htmlspecialchars((string) ($oldInput['niveau'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
          <p class="form-error" data-error-for="niveau" id="error-niveau"></p>

        </div>

        <div class="form-group">

          <label for="inscription-age">Âge (16-99)</label>

          <input id="inscription-age" type="number" name="age" min="16" max="99" value="<?= htmlspecialchars((string) ($oldInput['age'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
          <p class="form-error" data-error-for="age" id="error-age"></p>

        </div>

        <div class="form-group">

          <label for="inscription-tel">Téléphone (8 chiffres)</label>

          <input id="inscription-tel" type="text" name="tel" maxlength="8" inputmode="numeric" value="<?= htmlspecialchars((string) ($oldInput['tel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
          <small class="inscription-form__hint">Exemple: 98765432</small>
          <p class="form-error" data-error-for="tel" id="error-tel"></p>

        </div>

      </div>

      <div class="actions-row">

        <button type="submit" class="btn btn--primary" id="inscription-submit-btn">Valider l'inscription</button>

        <a href="index.php?r=front/formations" class="btn btn--secondary">Annuler</a>

      </div>

    </form>

  </div>

</main>

<script src="/careerlabb/e-learning/View/assets/js/front-flash-alert.js"></script>
<script src="/careerlabb/e-learning/View/assets/js/front-inscription.js"></script>

<?php require __DIR__ . '/../Layouts/front_footer.php'; ?>

