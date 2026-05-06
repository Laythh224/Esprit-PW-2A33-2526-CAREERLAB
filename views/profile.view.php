<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/mon_site/');
}

$values = $values ?? [];
$errors = $errors ?? ['profile' => [], 'password' => []];
$messages = $messages ?? ['success' => '', 'error' => ''];
$accountType = $accountType ?? 'utilisateur';
$roleLabel = $roleLabel ?? 'Utilisateur';
$isVerified = $isVerified ?? false;
$navigationLinks = $navigationLinks ?? [];
$profileSummary = $profileSummary ?? [];
$infoCards = $infoCards ?? [];

$safe = static fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$displayName = (string) ($profileSummary['display_name'] ?? 'Votre profil');
$email = (string) ($profileSummary['email'] ?? ($values['email'] ?? ''));
$telephone = (string) ($profileSummary['telephone'] ?? ($values['telephone'] ?? ''));
$initials = (string) ($profileSummary['initials'] ?? 'CL');
$createdAt = (string) ($profileSummary['created_at'] ?? '');
$accountName = (string) ($_SESSION['user_name'] ?? $displayName);
$sessionRole = (string) ($_SESSION['role'] ?? $accountType);

$toPublicPath = static function (string $path): string {
    $path = trim(str_replace('\\', '/', $path));
    if ($path === '') {
        return '#';
    }

    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }

    return BASE_URL . ltrim($path, '/');
};

$toExternalUrl = static function (string $url): string {
    $url = trim($url);
    if ($url === '') {
        return '#';
    }

    if (preg_match('#^https?://#i', $url)) {
        return $url;
    }

    return 'https://' . $url;
};
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CareerLab | Profil</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <style>
    :root {
      --cl-bg: #f4f9ff;
      --cl-surface: #ffffff;
      --cl-surface-strong: #ffffff;
      --cl-border: rgba(6, 163, 218, 0.15);
      --cl-text: #091e3e;
      --cl-muted: #6b7280;
      --cl-primary: #06a3da;
      --cl-primary-dark: #091e3e;
      --cl-cyan: #34adf7;
      --cl-violet: #5533ff;
      --cl-success: #16a34a;
      --cl-warning: #f59e0b;
      --cl-danger: #dc2626;
      --cl-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
      --cl-shadow-soft: 0 0 24px rgba(0, 0, 0, 0.06);
      --cl-radius: 26px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      min-height: 100vh;
      margin: 0;
      color: var(--cl-text);
      font-family: "Nunito", sans-serif;
      background: var(--cl-bg);
    }

    .navbar-brand {
      color: #ffffff !important;
    }

    .page-shell {
      max-width: 1320px;
      margin: 0 auto;
      padding: 10px 18px 56px;
    }

    .navbar-user-line {
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .navbar-profile-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 12px;
      margin-left: 0;
      margin-right: 0;
      border-radius: 999px;
      border: 1px solid rgba(255, 255, 255, 0.18);
      background: rgba(255, 255, 255, 0.08);
      color: #fff;
      text-decoration: none;
      transition: all 0.2s ease;
      font-size: 0.95rem;
      line-height: 1.1;
      max-width: 168px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .navbar-profile-btn:hover {
      color: #fff;
      background: rgba(255, 255, 255, 0.14);
      border-color: rgba(255, 255, 255, 0.28);
      box-shadow: 0 10px 24px rgba(0, 0, 0, 0.16);
    }

    .navbar-verified-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 18px;
      height: 18px;
      border-radius: 999px;
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      color: #fff;
      font-size: 11px;
      line-height: 1;
      box-shadow: 0 6px 14px rgba(59, 130, 246, 0.32);
      flex: 0 0 auto;
    }

    .profile-nav-actions {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-left: 0.75rem;
      flex-wrap: wrap;
      justify-content: flex-end;
    }

    .profile-nav-actions .btn {
      padding-top: 7px;
      padding-bottom: 7px;
      line-height: 1.2;
    }

    .profile-nav-actions .btn.text-primary {
      margin-left: 0 !important;
      padding-left: 8px;
      padding-right: 8px;
    }

    .navbar.navbar-dark {
      padding-top: 0.45rem !important;
      padding-bottom: 0.45rem !important;
      min-height: 72px;
    }

    .btn-career,
    .btn-career-outline,
    .btn-career-danger {
      border-radius: 16px;
      font-weight: 800;
      padding: 12px 18px;
      border: none;
      transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .btn-career:hover,
    .btn-career-outline:hover,
    .btn-career-danger:hover {
      transform: translateY(-1px);
    }

    .btn-career {
      background: linear-gradient(135deg, var(--cl-primary), #34adf7);
      color: #fff;
      box-shadow: 0 12px 24px rgba(6, 163, 218, 0.22);
    }

    .btn-career-outline {
      background: #fff;
      color: var(--cl-primary-dark);
      border: 1px solid rgba(6, 163, 218, 0.16);
    }

    .btn-career-danger {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: #fff;
      box-shadow: 0 14px 24px rgba(220, 38, 38, 0.18);
    }

    .hero-card {
      position: relative;
      overflow: hidden;
      padding: 32px;
      border-radius: 24px;
      border: 0;
      background:
        linear-gradient(rgba(9, 30, 62, 0.72), rgba(9, 30, 62, 0.72)),
        url("<?= BASE_URL ?>Views/assets/img/carousel-1.jpg") center/cover no-repeat;
      color: #fff;
      box-shadow: var(--cl-shadow);
      display: grid;
      grid-template-columns: minmax(0, 1.25fr) minmax(280px, 0.75fr);
      gap: 26px;
      margin-top: 10px;
      margin-bottom: 26px;
    }

    .hero-card::after {
      content: "";
      position: absolute;
      width: 260px;
      height: 260px;
      right: -40px;
      top: -70px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.14);
      filter: blur(6px);
    }

    .hero-main,
    .hero-side {
      position: relative;
      z-index: 1;
    }

    .hero-main {
      padding-top: 42px;
    }

    .hero-side {
      padding-top: 42px;
    }

    .hero-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 14px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.14);
      border: 1px solid rgba(255, 255, 255, 0.28);
      font-size: 0.9rem;
      font-weight: 700;
      margin-bottom: 14px;
    }

    .hero-main h1 {
      font-family: "Rubik", sans-serif;
      font-size: clamp(2rem, 1.5rem + 1.6vw, 3.2rem);
      line-height: 1.02;
      margin-bottom: 10px;
    }

    .hero-main p {
      max-width: 680px;
      margin: 0;
      color: rgba(255, 255, 255, 0.86);
      font-size: 1rem;
    }

    .hero-stat-grid {
      display: grid;
      gap: 14px;
    }

    .hero-stat {
      padding: 16px 18px;
      border-radius: 24px;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.16);
      backdrop-filter: blur(10px);
    }

    .hero-stat span {
      display: block;
      color: rgba(255, 255, 255, 0.72);
      font-size: 0.82rem;
      margin-bottom: 4px;
    }

    .hero-stat strong {
      font-size: 1rem;
    }

    .content-grid {
      display: grid;
      grid-template-columns: minmax(280px, 340px) minmax(0, 1fr);
      gap: 24px;
      align-items: start;
    }

    .panel,
    .surface-card {
      border-radius: var(--cl-radius);
      background: var(--cl-surface);
      border: 1px solid rgba(0, 0, 0, 0.04);
      box-shadow: var(--cl-shadow-soft);
    }

    .panel {
      padding: 24px;
      position: sticky;
      top: 112px;
    }

    .profile-avatar {
      width: 92px;
      height: 92px;
      border-radius: 30px;
      display: grid;
      place-items: center;
      margin-bottom: 18px;
      font-family: "Rubik", sans-serif;
      font-size: 1.85rem;
      font-weight: 800;
      color: #fff;
      background: linear-gradient(135deg, var(--cl-primary), var(--cl-cyan));
      box-shadow: 0 18px 32px rgba(6, 163, 218, 0.2);
    }

    .panel-title,
    .section-title {
      font-family: "Rubik", sans-serif;
      font-weight: 800;
      margin-bottom: 6px;
    }

    .panel-subtitle,
    .section-subtitle {
      color: var(--cl-muted);
      margin-bottom: 18px;
    }

    .status-chip {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 9px 14px;
      border-radius: 999px;
      font-weight: 700;
      font-size: 0.9rem;
    }

    .status-chip.success {
      color: #0f7a3f;
      background: rgba(34, 197, 94, 0.12);
      border: 1px solid rgba(34, 197, 94, 0.22);
    }

    .status-chip.warning {
      color: #b45309;
      background: rgba(245, 158, 11, 0.14);
      border: 1px solid rgba(245, 158, 11, 0.24);
    }

    .meta-stack {
      display: grid;
      gap: 12px;
      margin: 18px 0 22px;
    }

    .meta-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      padding: 14px 16px;
      border-radius: 18px;
      background: rgba(255, 255, 255, 0.76);
      border: 1px solid rgba(6, 163, 218, 0.08);
    }

    .meta-row span {
      color: var(--cl-muted);
      font-size: 0.92rem;
    }

    .meta-row strong {
      text-align: right;
      word-break: break-word;
    }

    .quick-links {
      display: grid;
      gap: 10px;
    }

    .quick-links a {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      padding: 14px 16px;
      text-decoration: none;
      color: var(--cl-text);
      border-radius: 18px;
      background: rgba(37, 99, 235, 0.06);
      border: 1px solid rgba(6, 163, 218, 0.08);
    }

    .main-stack {
      display: grid;
      gap: 22px;
    }

    .alert-banner {
      padding: 16px 18px;
      border-radius: 20px;
      font-weight: 700;
      border: 1px solid transparent;
    }

    .alert-banner.success {
      background: rgba(34, 197, 94, 0.12);
      border-color: rgba(34, 197, 94, 0.22);
      color: #166534;
    }

    .alert-banner.error {
      background: rgba(239, 68, 68, 0.12);
      border-color: rgba(239, 68, 68, 0.22);
      color: #991b1b;
    }

    .surface-card {
      padding: 26px;
    }

    .cards-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
      gap: 16px;
    }

    .info-card {
      position: relative;
      overflow: hidden;
      padding: 20px;
      border-radius: 24px;
      background: #fff;
      border: 1px solid rgba(6, 163, 218, 0.08);
      box-shadow: 0 14px 26px rgba(15, 23, 42, 0.05);
      min-height: 154px;
    }

    .info-card::after {
      content: "";
      position: absolute;
      width: 90px;
      height: 90px;
      right: -16px;
      bottom: -22px;
      border-radius: 50%;
      background: rgba(6, 163, 218, 0.08);
    }

    .info-icon {
      width: 48px;
      height: 48px;
      border-radius: 16px;
      display: grid;
      place-items: center;
      margin-bottom: 16px;
      font-size: 1.15rem;
    }

    .accent-primary .info-icon {
      color: var(--cl-primary);
      background: rgba(6, 163, 218, 0.12);
    }

    .accent-info .info-icon {
      color: var(--cl-cyan);
      background: rgba(52, 173, 247, 0.12);
    }

    .accent-violet .info-icon {
      color: var(--cl-violet);
      background: rgba(124, 58, 237, 0.12);
    }

    .accent-success .info-icon {
      color: var(--cl-success);
      background: rgba(34, 197, 94, 0.12);
    }

    .accent-warning .info-icon {
      color: var(--cl-warning);
      background: rgba(245, 158, 11, 0.12);
    }

    .info-card span {
      display: block;
      color: var(--cl-muted);
      font-size: 0.86rem;
      margin-bottom: 8px;
    }

    .info-card strong,
    .info-card a {
      position: relative;
      z-index: 1;
      display: inline-block;
      color: var(--cl-text);
      font-size: 1rem;
      line-height: 1.45;
      text-decoration: none;
      word-break: break-word;
    }

    .info-card a {
      color: var(--cl-primary-dark);
      font-weight: 800;
    }

    .form-layout {
      display: grid;
      gap: 18px;
    }

    .field-note {
      display: block;
      color: var(--cl-muted);
      font-size: 0.82rem;
      margin-top: 6px;
    }

    .form-label {
      font-weight: 800;
      margin-bottom: 8px;
    }

    .form-control,
    .form-select {
      min-height: 52px;
      border-radius: 16px;
      border: 1px solid rgba(6, 163, 218, 0.12);
      background: rgba(255, 255, 255, 0.98);
      color: var(--cl-text);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }

    textarea.form-control {
      min-height: 130px;
      resize: vertical;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: rgba(6, 163, 218, 0.32);
      box-shadow: 0 0 0 0.22rem rgba(6, 163, 218, 0.12);
    }

    .input-group.profile-password-group .form-control {
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
    }

    .input-group.profile-password-group .btn {
      min-width: 54px;
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
      border: 1px solid rgba(6, 163, 218, 0.12);
      border-left: none;
      background: #fff;
      color: var(--cl-muted);
    }

    .section-actions {
      display: flex;
      justify-content: flex-end;
      margin-top: 8px;
    }

    @media (max-width: 1199px) {
      .career-nav {
        border-radius: 28px;
        align-items: flex-start;
      }

      .nav-links {
        justify-content: flex-start;
      }

      .content-grid,
      .hero-card {
        grid-template-columns: 1fr;
      }

      .panel {
        position: static;
      }

      .profile-nav-actions {
        width: 100%;
        margin-left: 0;
        margin-top: 12px;
        justify-content: flex-start;
      }
    }

    @media (max-width: 767px) {
      .page-shell {
        padding: 22px 14px 40px;
      }

      .hero-card {
        margin-top: 12px;
      }

      .hero-main {
        padding-top: 22px;
      }

      .hero-side {
        padding-top: 22px;
      }

      .profile-nav-actions {
        gap: 10px;
      }

      .navbar-profile-btn,
      .profile-nav-actions .btn {
        width: 100%;
        justify-content: center;
      }

      .nav-account {
        width: 100%;
        flex-wrap: wrap;
      }

      .account-pill {
        flex: 1 1 100%;
      }

      .hero-card,
      .surface-card,
      .panel {
        padding: 22px;
      }

      .cards-grid {
        grid-template-columns: 1fr;
      }

      .section-actions .btn-career,
      .section-actions .btn-career-danger {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="container-fluid bg-dark px-5 d-none d-lg-block">
    <div class="row gx-0">
      <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
        <div class="d-inline-flex align-items-center" style="height: 45px;">
          <small class="me-3 text-light"><i class="bi bi-geo-alt-fill me-2"></i>Esprit, Ghazela</small>
          <small class="me-3 text-light"><i class="bi bi-telephone-fill me-2"></i>+216 98 542 321</small>
          <small class="text-light"><i class="bi bi-envelope-fill me-2"></i>careerlab@gmail.com</small>
        </div>
      </div>
      <div class="col-lg-4 text-center text-lg-end">
        <div class="d-inline-flex align-items-center" style="height: 45px;">
          <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-twitter fw-normal"></i></a>
          <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-facebook-f fw-normal"></i></a>
          <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-linkedin-in fw-normal"></i></a>
          <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-instagram fw-normal"></i></a>
          <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href=""><i class="fab fa-youtube fw-normal"></i></a>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
      <a href="index.php?page=accueil" class="navbar-brand p-0">
        <img src="<?= BASE_URL ?>Views/assets/img/image_2026-04-11_005109464-removebg-preview.png" alt="CareerLab" style="height: 52px; max-width: 100%;" />
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0">
          <a href="index.php?page=accueil" class="nav-item nav-link">Accueil</a>
          <a href="index.php?page=about" class="nav-item nav-link">A propos</a>
          <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Blog</a>
            <div class="dropdown-menu m-0">
              <a href="index.php?page=blog" class="dropdown-item">Blog Grid</a>
              <a href="index.php?page=detail" class="dropdown-item">Blog Detail</a>
            </div>
          </div>
          <a href="index.php?page=service" class="nav-item nav-link">Services</a>
          <a href="index.php?page=contact" class="nav-item nav-link">Contact</a>
        </div>
        <div class="profile-nav-actions">
          <a href="index.php?page=profile" class="navbar-profile-btn navbar-user-line">
            <span><?= $safe($accountName) ?></span>
            <?php if ($isVerified): ?>
              <span class="navbar-verified-badge" title="Compte verifie">✓</span>
            <?php endif; ?>
          </a>
          <a href="index.php?page=logout" class="btn btn-outline-light py-2 px-3">Se deconnecter</a>
          <button type="button" class="btn text-primary"><i class="fa fa-search"></i></button>
        </div>
      </div>
    </nav>
  </div>

  <div class="page-shell">
    <section class="hero-card">
      <div class="hero-main">
        <div class="hero-eyebrow">
          <i class="bi bi-stars"></i>
          <span>Profil CareerLab</span>
        </div>
        <h1><?= $safe($displayName) ?></h1>
        <p>
          Retrouvez vos informations de compte, mettez a jour vos coordonnees et gerez votre securite
          depuis une interface claire, moderne et adaptee a votre role.
        </p>
      </div>

      <div class="hero-side">
        <div class="hero-stat-grid">
          <div class="hero-stat">
            <span>Type de compte</span>
            <strong><?= $safe($roleLabel) ?></strong>
          </div>
          <div class="hero-stat">
            <span>Statut du compte</span>
            <strong><?= $isVerified ? 'Verifie' : 'Non verifie' ?></strong>
          </div>
          <div class="hero-stat">
            <span>Adresse email</span>
            <strong><?= $safe($email !== '' ? $email : 'Non renseignee') ?></strong>
          </div>
        </div>
      </div>
    </section>

    <div class="content-grid">
      <aside class="panel">
        <div class="profile-avatar"><?= $safe($initials) ?></div>
        <h2 class="panel-title"><?= $safe($displayName) ?></h2>
        <p class="panel-subtitle">Avatar par defaut, informations synchronisees avec votre compte actif.</p>

        <div class="status-chip <?= $isVerified ? 'success' : 'warning' ?>">
          <i class="bi <?= $isVerified ? 'bi-patch-check-fill' : 'bi-patch-exclamation-fill' ?>"></i>
          <span><?= $isVerified ? 'Compte verifie' : 'Compte non verifie' ?></span>
        </div>

        <div class="meta-stack">
          <div class="meta-row">
            <span>Nom complet</span>
            <strong><?= $safe($displayName) ?></strong>
          </div>
          <div class="meta-row">
            <span>Email</span>
            <strong><?= $safe($email !== '' ? $email : 'Non renseigne') ?></strong>
          </div>
          <div class="meta-row">
            <span>Telephone</span>
            <strong><?= $safe($telephone !== '' ? $telephone : 'Non renseigne') ?></strong>
          </div>
          <div class="meta-row">
            <span>Role session</span>
            <strong><?= $safe($sessionRole) ?></strong>
          </div>
          <?php if ($createdAt !== ''): ?>
            <div class="meta-row">
              <span>Date de creation</span>
              <strong><?= $safe($createdAt) ?></strong>
            </div>
          <?php endif; ?>
        </div>

        <div class="quick-links">
          <a href="index.php?page=dashboard">
            <span>Retour au tableau de bord</span>
            <i class="bi bi-arrow-right-circle"></i>
          </a>
          <a href="index.php?page=statistiques">
            <span>Voir les statistiques</span>
            <i class="bi bi-bar-chart-line"></i>
          </a>
          <a href="index.php?r=main#assistant-ia">
            <span>Acceder a l'assistant IA</span>
            <i class="bi bi-robot"></i>
          </a>
        </div>
      </aside>

      <main class="main-stack">
        <?php if ($messages['success'] !== ''): ?>
          <div class="alert-banner success"><?= $safe($messages['success']) ?></div>
        <?php endif; ?>

        <?php if ($messages['error'] !== ''): ?>
          <div class="alert-banner error"><?= $safe($messages['error']) ?></div>
        <?php endif; ?>

        <section class="surface-card">
          <h3 class="section-title">Vue d'ensemble du profil</h3>
          <p class="section-subtitle">
            Les cartes ci-dessous s'adaptent automatiquement au type de compte connecte.
          </p>

          <div class="cards-grid">
            <?php foreach ($infoCards as $card): ?>
              <?php $accentClass = 'accent-' . preg_replace('/[^a-z-]/', '', (string) ($card['accent'] ?? 'primary')); ?>
              <article class="info-card <?= $safe($accentClass) ?>">
                <div class="info-icon">
                  <i class="bi <?= $safe($card['icon'] ?? 'bi-grid') ?>"></i>
                </div>
                <span><?= $safe($card['label'] ?? '') ?></span>
                <?php if (!empty($card['href'])): ?>
                  <?php
                    $hrefValue = (string) $card['href'];
                    $resolvedHref = str_contains($hrefValue, 'Views/assets/uploads/')
                      ? $toPublicPath($hrefValue)
                      : $toExternalUrl($hrefValue);
                  ?>
                  <a href="<?= $safe($resolvedHref) ?>" target="_blank" rel="noopener noreferrer">
                    <?= $safe($card['value'] ?? '') ?>
                  </a>
                <?php else: ?>
                  <strong><?= $safe($card['value'] ?? '') ?></strong>
                <?php endif; ?>
              </article>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="surface-card">
          <h3 class="section-title">Informations personnelles</h3>
          <p class="section-subtitle">
            Modifiez les champs existants de votre compte sans impacter les autres fonctionnalites du site.
          </p>

          <form method="post" novalidate class="form-layout">
            <input type="hidden" name="action" value="update_profile" />

            <div class="row g-3">
              <?php if ($accountType === 'entreprise'): ?>
                <div class="col-12">
                  <label class="form-label" for="nom_entreprise">Nom entreprise</label>
                  <input
                    type="text"
                    id="nom_entreprise"
                    name="nom_entreprise"
                    class="form-control <?= ($errors['profile']['nom_entreprise'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    value="<?= $safe($values['nom_entreprise'] ?? '') ?>"
                    required
                  />
                  <div class="invalid-feedback"><?= $safe($errors['profile']['nom_entreprise'] ?? '') ?></div>
                </div>
              <?php else: ?>
                <div class="col-md-6">
                  <label class="form-label" for="nom">Nom</label>
                  <input
                    type="text"
                    id="nom"
                    name="nom"
                    class="form-control <?= ($errors['profile']['nom'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    value="<?= $safe($values['nom'] ?? '') ?>"
                    required
                  />
                  <div class="invalid-feedback"><?= $safe($errors['profile']['nom'] ?? '') ?></div>
                </div>

                <div class="col-md-6">
                  <label class="form-label" for="prenom">Prenom</label>
                  <input
                    type="text"
                    id="prenom"
                    name="prenom"
                    class="form-control <?= ($errors['profile']['prenom'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    value="<?= $safe($values['prenom'] ?? '') ?>"
                    required
                  />
                  <div class="invalid-feedback"><?= $safe($errors['profile']['prenom'] ?? '') ?></div>
                </div>
              <?php endif; ?>

              <div class="col-md-6">
                <label class="form-label" for="email">Email</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  class="form-control <?= ($errors['profile']['email'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                  value="<?= $safe($values['email'] ?? '') ?>"
                  required
                />
                <div class="invalid-feedback"><?= $safe($errors['profile']['email'] ?? '') ?></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="telephone">Telephone</label>
                <input
                  type="text"
                  id="telephone"
                  name="telephone"
                  class="form-control <?= ($errors['profile']['telephone'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                  value="<?= $safe($values['telephone'] ?? '') ?>"
                />
                <div class="invalid-feedback"><?= $safe($errors['profile']['telephone'] ?? '') ?></div>
              </div>

              <?php if ($accountType !== 'entreprise'): ?>
                <div class="col-md-6">
                  <label class="form-label" for="sexe">Sexe / genre</label>
                  <select
                    id="sexe"
                    name="sexe"
                    class="form-select <?= ($errors['profile']['sexe'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    required
                  >
                    <option value="">Selectionnez</option>
                    <option value="homme" <?= ($values['sexe'] ?? '') === 'homme' ? 'selected' : '' ?>>Homme</option>
                    <option value="femme" <?= ($values['sexe'] ?? '') === 'femme' ? 'selected' : '' ?>>Femme</option>
                  </select>
                  <div class="invalid-feedback"><?= $safe($errors['profile']['sexe'] ?? '') ?></div>
                </div>
              <?php endif; ?>

              <?php if ($accountType === 'utilisateur'): ?>
                <div class="col-md-6">
                  <label class="form-label" for="niveau_etude">Niveau</label>
                  <input
                    type="text"
                    id="niveau_etude"
                    name="niveau_etude"
                    class="form-control <?= ($errors['profile']['niveau_etude'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    value="<?= $safe($values['niveau_etude'] ?? '') ?>"
                  />
                  <div class="invalid-feedback"><?= $safe($errors['profile']['niveau_etude'] ?? '') ?></div>
                </div>

                <div class="col-md-6">
                  <label class="form-label" for="domaine">Domaine</label>
                  <input
                    type="text"
                    id="domaine"
                    name="domaine"
                    class="form-control <?= ($errors['profile']['domaine'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    value="<?= $safe($values['domaine'] ?? '') ?>"
                  />
                  <div class="invalid-feedback"><?= $safe($errors['profile']['domaine'] ?? '') ?></div>
                </div>

                <div class="col-12">
                  <label class="form-label" for="competences">Competences</label>
                  <textarea
                    id="competences"
                    name="competences"
                    class="form-control <?= ($errors['profile']['competences'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    rows="4"
                  ><?= $safe($values['competences'] ?? '') ?></textarea>
                  <div class="invalid-feedback"><?= $safe($errors['profile']['competences'] ?? '') ?></div>
                  <span class="field-note">Ajoutez vos competences principales si ce champ est disponible dans votre compte.</span>
                </div>
              <?php endif; ?>

              <?php if ($accountType === 'formateur'): ?>
                <div class="col-md-6">
                  <label class="form-label" for="specialite">Specialite</label>
                  <input
                    type="text"
                    id="specialite"
                    name="specialite"
                    class="form-control <?= ($errors['profile']['specialite'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    value="<?= $safe($values['specialite'] ?? '') ?>"
                  />
                  <div class="invalid-feedback"><?= $safe($errors['profile']['specialite'] ?? '') ?></div>
                </div>

                <div class="col-md-6">
                  <label class="form-label" for="diplomes">Diplome</label>
                  <input
                    type="text"
                    id="diplomes"
                    name="diplomes"
                    class="form-control <?= ($errors['profile']['diplomes'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    value="<?= $safe($values['diplomes'] ?? '') ?>"
                  />
                  <div class="invalid-feedback"><?= $safe($errors['profile']['diplomes'] ?? '') ?></div>
                </div>

                <div class="col-12">
                  <label class="form-label" for="experience">Experience</label>
                  <textarea
                    id="experience"
                    name="experience"
                    class="form-control <?= ($errors['profile']['experience'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    rows="4"
                  ><?= $safe($values['experience'] ?? '') ?></textarea>
                  <div class="invalid-feedback"><?= $safe($errors['profile']['experience'] ?? '') ?></div>
                </div>
              <?php endif; ?>

              <?php if ($accountType === 'entreprise'): ?>
                <div class="col-md-6">
                  <label class="form-label" for="adresse">Adresse</label>
                  <input
                    type="text"
                    id="adresse"
                    name="adresse"
                    class="form-control <?= ($errors['profile']['adresse'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    value="<?= $safe($values['adresse'] ?? '') ?>"
                  />
                  <div class="invalid-feedback"><?= $safe($errors['profile']['adresse'] ?? '') ?></div>
                </div>

                <div class="col-md-6">
                  <label class="form-label" for="ville">Ville</label>
                  <input
                    type="text"
                    id="ville"
                    name="ville"
                    class="form-control <?= ($errors['profile']['ville'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    value="<?= $safe($values['ville'] ?? '') ?>"
                  />
                  <div class="invalid-feedback"><?= $safe($errors['profile']['ville'] ?? '') ?></div>
                </div>

                <div class="col-md-6">
                  <label class="form-label" for="secteur">Secteur</label>
                  <input
                    type="text"
                    id="secteur"
                    name="secteur"
                    class="form-control <?= ($errors['profile']['secteur'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    value="<?= $safe($values['secteur'] ?? '') ?>"
                  />
                  <div class="invalid-feedback"><?= $safe($errors['profile']['secteur'] ?? '') ?></div>
                </div>

                <div class="col-md-6">
                  <label class="form-label" for="site_web">Site web</label>
                  <input
                    type="text"
                    id="site_web"
                    name="site_web"
                    class="form-control <?= ($errors['profile']['site_web'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    value="<?= $safe($values['site_web'] ?? '') ?>"
                  />
                  <div class="invalid-feedback"><?= $safe($errors['profile']['site_web'] ?? '') ?></div>
                </div>

                <div class="col-12">
                  <label class="form-label" for="description">Description</label>
                  <textarea
                    id="description"
                    name="description"
                    class="form-control <?= ($errors['profile']['description'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                    rows="4"
                  ><?= $safe($values['description'] ?? '') ?></textarea>
                  <div class="invalid-feedback"><?= $safe($errors['profile']['description'] ?? '') ?></div>
                </div>
              <?php endif; ?>
            </div>

            <div class="section-actions">
              <button type="submit" class="btn-career">Mettre a jour le profil</button>
            </div>
          </form>
        </section>

        <section class="surface-card">
          <h3 class="section-title">Mettre a jour le mot de passe</h3>
          <p class="section-subtitle">
            Cette section complete le systeme de mot de passe oublie existant sans le remplacer.
          </p>

          <form method="post" novalidate class="form-layout">
            <input type="hidden" name="action" value="change_password" />

            <div class="row g-3">
              <div class="col-12">
                <label class="form-label" for="current_password">Ancien mot de passe</label>
                <div class="input-group profile-password-group">
                  <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    class="form-control <?= ($errors['password']['current_password'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                  />
                  <button class="btn btn-career-outline" type="button" data-toggle-password data-toggle-target="current_password">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
                <?php if (($errors['password']['current_password'] ?? '') !== ''): ?>
                  <div class="invalid-feedback d-block"><?= $safe($errors['password']['current_password']) ?></div>
                <?php endif; ?>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="password">Nouveau mot de passe</label>
                <div class="input-group profile-password-group">
                  <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control <?= ($errors['password']['password'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                  />
                  <button class="btn btn-career-outline" type="button" data-toggle-password data-toggle-target="password">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
                <?php if (($errors['password']['password'] ?? '') !== ''): ?>
                  <div class="invalid-feedback d-block"><?= $safe($errors['password']['password']) ?></div>
                <?php endif; ?>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="confirm_password">Confirmer le nouveau mot de passe</label>
                <div class="input-group profile-password-group">
                  <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    class="form-control <?= ($errors['password']['confirm_password'] ?? '') !== '' ? 'is-invalid' : '' ?>"
                  />
                  <button class="btn btn-career-outline" type="button" data-toggle-password data-toggle-target="confirm_password">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
                <?php if (($errors['password']['confirm_password'] ?? '') !== ''): ?>
                  <div class="invalid-feedback d-block"><?= $safe($errors['password']['confirm_password']) ?></div>
                <?php endif; ?>
              </div>
            </div>

            <div class="section-actions">
              <button type="submit" class="btn-career">Enregistrer</button>
            </div>
          </form>
        </section>
      </main>
  </div>

  <script src="<?= BASE_URL ?>Views/assets/js/password-toggle.js"></script>
  <script>
    document.querySelectorAll("[data-toggle-password]").forEach(function (button) {
      button.addEventListener("click", function () {
        var icon = button.querySelector("i");
        var targetId = button.getAttribute("data-toggle-target");
        var field = targetId ? document.getElementById(targetId) : null;

        if (!field || !icon) {
          return;
        }

        window.setTimeout(function () {
          icon.className = field.type === "password" ? "bi bi-eye" : "bi bi-eye-slash";
        }, 0);
      });
    });
  </script>
</body>
</html>
