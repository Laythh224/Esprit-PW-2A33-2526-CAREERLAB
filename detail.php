<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Défis - Career Lab</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner"></div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid bg-dark px-5 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>123 Street, New York, USA</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+012 345 6789</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>info@example.com</small>
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
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="indexF.html" class="navbar-brand p-0">
                <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Carrer Lab</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="indexF.html" class="nav-item nav-link">Accueil</a>
                    <a href="about.html" class="nav-item nav-link">À propos</a>
                  
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Blog</a>
                        <div class="dropdown-menu m-0">
                            <a href="blog.php" class="dropdown-item">Blog</a>
                            <a href="detail.php" class="dropdown-item active">Défis</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                        <div class="dropdown-menu m-0">
                            <a href="price.html" class="dropdown-item">Utilisateur</a>
                            <a href="feature.html" class="dropdown-item">Métiers</a>
                            <a href="team.html" class="dropdown-item">Évaluation</a>
                            <a href="testimonial.html" class="dropdown-item">Les offres</a>
                            <a href="quote.html" class="dropdown-item">E-learning</a>
                            <a href="quote.html" class="dropdown-item">Blog</a>
                        </div>
                    </div>
                    <a href="contact.html" class="nav-item nav-link">Contact</a>
                </div>
                <butaton type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="Rechercher"><i class="fa fa-search"></i></butaton>
                <a href="blog.php" class="btn btn-primary py-2 px-4 ms-3">Articles</a>
            </div>
        </nav>

        <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
            <div class="row py-5">
                <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                    <h1 class="display-4 text-white animated zoomIn">Défis</h1>
                    <a href="indexF.html" class="h5 text-white">Accueil</a>
                    <i class="far fa-circle text-white px-2"></i>
                    <a href="detail.php" class="h5 text-white">Défis</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar End -->


    <!-- Full Screen Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" style="background: rgba(9, 30, 62, .7);">
                <div class="modal-header border-0">
                    <button type="button" class="btn bg-white btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center">
                    <div class="input-group" style="max-width: 600px;">
                        <input type="text" class="form-control bg-transparent border-primary p-3" placeholder="Mot-clé…">
                        <button class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Full Screen Search End -->


    <!-- Challenges Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-8">
                    <p class="text-muted mb-4" id="challengeEmptyMsg" style="display:none;">Aucun défi trouvé.</p>
                    <div id="challengeList"></div>
                </div>
    
                <!-- Sidebar Start -->
                <div class="col-lg-4">
                    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s">
                        <button class="btn btn-success w-100 mb-3" type="button" id="createChallengeBtn">
                            <i class="bi bi-plus-circle me-2"></i>Créer un défi
                        </button>
                        <div class="input-group">
                            <input type="search" class="form-control p-3" placeholder="Rechercher..." aria-label="Rechercher" id="challengeSearchInput">
                            <button class="btn btn-primary px-4" type="button" id="challengeSearchBtn" aria-label="Lancer la recherche"><i class="bi bi-search"></i></button>
                        </div>
                        <div class="mt-3">
                            <label class="form-label small mb-1" for="challengeSortSelect">Trier par</label>
                            <select class="form-control" id="challengeSortSelect">
                                <option value="date_desc">Date de début (plus récente)</option>
                                <option value="date_asc">Date de début (plus ancienne)</option>
                                <option value="upvotes_desc">Popularité (upvotes)</option>
                                <option value="theme_asc">Thème (A → Z)</option>
                                <option value="theme_desc">Thème (Z → A)</option>
                            </select>
                        </div>
                        <p class="small text-muted mt-2 mb-0">Rechercher par thème, description, flair ou créateur.</p>
                    </div>
                    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s">
                        <div class="section-title section-title-sm position-relative pb-3 mb-4">
                            <h3 class="mb-0">Défis récents</h3>
                        </div>
                        <div id="sidebarRecentChallenges"></div>
                    </div>
                </div>
                <!-- Sidebar End -->
            </div>
        </div>
    </div>
    <!-- Challenges End -->


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-4 col-md-6 footer-about">
                    <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-primary p-4">
                        <a href="indexF.html" class="navbar-brand">
                            <h1 class="m-0 text-white"><i class="fa fa-user-tie me-2"></i>Startup</h1>
                        </a>
                        <p class="mt-3 mb-4">Lorem diam sit erat dolor elitr et, diam lorem justo amet clita stet eos sit. Elitr dolor duo lorem, elitr clita ipsum sea. Diam amet erat lorem stet eos. Diam amet et kasd eos duo.</p>
                        <form action="">
                            <div class="input-group">
                                <input type="text" class="form-control border-white p-3" placeholder="Votre e-mail">
                                <button class="btn btn-dark">S'inscrire</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="row gx-5">
                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Contact</h3>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <p class="mb-0">123 Street, New York, USA</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-envelope-open text-primary me-2"></i>
                                <p class="mb-0">info@example.com</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-telephone text-primary me-2"></i>
                                <p class="mb-0">+012 345 67890</p>
                            </div>
                            <div class="d-flex mt-4">
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                                <a class="btn btn-primary btn-square" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Liens rapides</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Accueil</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>À propos</a>
                               
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Notre équipe</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Dernier blog</a>
                                <a class="text-light" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Nous contacter</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Liens populaires</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Accueil</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>À propos</a>

                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Notre équipe</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Dernier blog</a>
                                <a class="text-light" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Nous contacter</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-white" style="background: #061429;">
        <div class="container text-center">
            <div class="row justify-content-end">
                <div class="col-lg-8 col-md-6">
                    <div class="d-flex align-items-center justify-content-center" style="height: 75px;">
                        <p class="mb-0">&copy; <a class="text-white border-bottom" href="#">Nom de votre site</a>. Tous droits réservés. 
						
						<!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
						Designed by <a class="text-white border-bottom" href="https://htmlcodex.com">HTML Codex</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- Challenge Modal -->
    <div class="modal fade" id="challengeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="challengeModalTitle">Créer un défi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="challengeForm">
                        <div class="mb-3">
                            <label class="form-label">Thème *</label>
                            <input type="text" class="form-control" id="challengeTheme" placeholder="10-200 caractères">
                            <div class="text-danger small mt-1" id="challengeThemeError"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" id="challengeDescription" rows="5" placeholder="10-5000 caractères"></textarea>
                            <div class="text-danger small mt-1" id="challengeDescriptionError"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Flair *</label>
                                <select class="form-control" id="challengeFlair">
                                    <option value="Projet">Projet</option>
                                    <option value="Showcast">Showcast</option>
                                    <option value="Débat">Débat</option>
                                    <option value="Pitch">Pitch</option>
                                </select>
                                <div class="text-danger small mt-1" id="challengeFlairError"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                            <label class="form-label">Type de créateur *</label>
                                <select class="form-control" id="challengeCreatorType">
                                    <option value="Formateur">Formateur</option>
                                    <option value="Entreprise">Entreprise</option>
                                </select>
                                <div class="text-danger small mt-1" id="challengeCreatorTypeError"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date de début *</label>
                                <input type="date" class="form-control" id="challengeStartDate">
                                <div class="text-danger small mt-1" id="challengeStartDateError"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date de fin *</label>
                                <input type="date" class="form-control" id="challengeEndDate">
                                <div class="text-danger small mt-1" id="challengeEndDateError"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type de récompense *</label>
                                <select class="form-control" id="challengeRewardType">
                                    <option value="job">job</option>
                                    <option value="course">course</option>
                                </select>
                                <div class="text-danger small mt-1" id="challengeRewardTypeError"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sélectionner un post *</label>
                                <select class="form-control" id="challengePostId">
                                    <option value="">-- Sélectionner --</option>
                                </select>
                                <div class="text-danger small mt-1" id="challengePostIdError"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Titre de la récompense *</label>
                            <input type="text" class="form-control" id="challengeRewardTitle" placeholder="10-200 caractères">
                            <div class="text-danger small mt-1" id="challengeRewardTitleError"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description de la récompense *</label>
                            <textarea class="form-control" id="challengeRewardDescription" rows="4" placeholder="10-5000 caractères"></textarea>
                            <div class="text-danger small mt-1" id="challengeRewardDescriptionError"></div>
                        </div>
                        <input type="hidden" id="challengeId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="saveChallengBtn">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="js/careerlab-public.js"></script>
    <script src="js/careerlab-forms.js"></script>
<script>
(function () {
  var CL = window.CareerLab;
  var currentQuery = '';
  var commentsCache = {};
  var commentsLoading = {};
  var currentSort = 'date_desc';
  var challengesCache = null; // loaded from DB API

  function escapeHtml(s) {
    if (s == null) return '';
    var d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
  }

  function getVisitorId() {
    return CL.getVisitorId();
  }

  function apiListChallenges() {
    return fetch('index.php?c=challenge&a=listBackoffice', { headers: { 'Accept': 'application/json' } })
      .then(function (r) { return r.json(); });
  }

  function apiListPosts() {
    return fetch('index.php?c=post&a=index', { headers: { 'Accept': 'application/json' } })
      .then(function (r) { return r.json(); });
  }

  function apiGetComments(challengeId) {
    return fetch(
      'index.php?c=challenge&a=comments&challenge_id=' + encodeURIComponent(String(challengeId)),
      { headers: { 'Accept': 'application/json' } }
    ).then(function (r) { return r.json(); });
  }

  function apiCreateComment(challengeId, body, imageUrl, videoUrl) {
    var fd = new FormData();
    fd.append('challenge_id', String(challengeId));
    fd.append('body', String(body || ''));
    fd.append('image_url', String(imageUrl || ''));
    fd.append('video_url', String(videoUrl || ''));
    return fetch('index.php?c=challenge&a=commentCreate', {
      method: 'POST',
      body: fd,
      headers: { 'Accept': 'application/json' }
    }).then(function (r) { return r.json(); });
  }

  function apiUpvoteComment(commentId) {
    var fd = new FormData();
    fd.append('comment_id', String(commentId));
    return fetch('index.php?c=challenge&a=commentUpvote', {
      method: 'POST',
      body: fd,
      headers: { 'Accept': 'application/json' }
    }).then(function (r) { return r.json(); });
  }

  function ensureCommentsLoaded(challengeId) {
    var key = String(challengeId);
    if (commentsCache[key] !== undefined || commentsLoading[key]) return;
    commentsLoading[key] = true;
    apiGetComments(challengeId)
      .then(function (res) {
        commentsCache[key] = (res && res.ok && Array.isArray(res.items)) ? res.items : [];
      })
      .catch(function () {
        commentsCache[key] = [];
      })
      .finally(function () {
        commentsLoading[key] = false;
        renderChallenges();
      });
  }

  function loadChallenges() {
    // Returns cached list (populated by fetchAndRender)
    return Array.isArray(challengesCache) ? challengesCache : [];
  }

  function fetchAndRender() {
    apiListChallenges()
      .then(function (res) {
        challengesCache = (res && res.ok && Array.isArray(res.items)) ? res.items : [];
        renderChallenges();
      })
      .catch(function () {
        challengesCache = [];
        renderChallenges();
      });
  }

  function getParams() {
    try {
      return new URLSearchParams(window.location.search || '');
    } catch (e) {
      return new URLSearchParams('');
    }
  }

  function isActiveStatus(s) {
    // Keep old behavior for "active", but we also display "upcoming" challenges
    return CL.isActiveStatus(s);
  }

  function isVisibleStatus(s) {
    var v = String(s || '').toLowerCase();
    return v === 'active' || v === 'actif' || v === 'upcoming';
  }

  function youtubeEmbedUrl(url) {
    if (!url || !String(url).trim()) return null;
    try {
      var u = new URL(url);
      var host = u.hostname.replace(/^www\./, '');
      if (host === 'youtu.be') {
        var id = u.pathname.replace(/^\//, '').split('/')[0];
        return id ? 'https://www.youtube.com/embed/' + id : null;
      }
      if (host.indexOf('youtube.com') !== -1) {
        var v = u.searchParams.get('v');
        if (v) return 'https://www.youtube.com/embed/' + v;
      }
    } catch (e) {}
    return null;
  }

  function renderCommentsBlock(challengeId) {
    var cacheKey = String(challengeId);
    var comments = commentsCache[cacheKey];

    if (comments === undefined) {
      ensureCommentsLoaded(challengeId);
      return (
        '<div class="mt-4 pt-3 border-top">' +
        '<h5 class="mb-3">Commentaires</h5>' +
        '<p class="text-muted small mb-0">Chargement des commentaires…</p>' +
        '</div>'
      );
    }

    var visitorId = getVisitorId();
    var listHtml = '';
    if (!comments.length) {
      listHtml = '<p class="text-muted small mb-3">Aucun commentaire pour le moment. Soyez le premier à réagir.</p>';
    } else {
      listHtml = comments.map(function (c) {
        var cnt = Number(c.upvote_count) || 0;
        var media = '';
        if (c.image_url && CL.isValidUrl(c.image_url)) {
          media += '<img src="' + escapeHtml(c.image_url) + '" class="img-fluid rounded mt-2" alt="" style="max-height:260px;">';
        }
        var emb = youtubeEmbedUrl(c.video_url);
        if (emb) {
          media += '<div class="ratio ratio-16x9 mt-2"><iframe src="' + escapeHtml(emb) + '" title="Vidéo" allowfullscreen></iframe></div>';
        } else if (c.video_url && CL.isValidUrl(c.video_url)) {
          media += '<p class="mt-2 mb-0"><a href="' + escapeHtml(c.video_url) + '" target="_blank" rel="noopener">Ouvrir la vidéo</a></p>';
        }
        return (
          '<div class="border rounded p-3 mb-3 bg-white">' +
          '<p class="mb-2">' + escapeHtml(c.body).replace(/\n/g, '<br>') + '</p>' + media +
          '<div class="d-flex align-items-center flex-wrap gap-2 mt-2">' +
          '<button type="button" class="btn btn-sm btn-outline-primary js-comment-upvote" data-comment-id="' + escapeHtml(String(c.id)) + '">' +
          '<i class="bi bi-hand-thumbs-up me-1"></i> Soutenir <span class="badge bg-light text-primary">' + cnt + '</span></button>' +
          '<span class="small text-muted ms-auto">' + escapeHtml(c.created_at || '') + '</span>' +
          '</div></div>'
        );
      }).join('');
    }
    return (
      '<div class="mt-4 pt-3 border-top">' +
      '<h5 class="mb-3">Commentaires</h5>' +
      '<div class="challenge-comments-list mb-3">' + listHtml + '</div>' +
      '<form class="challenge-comment-form bg-white border rounded p-3" data-challenge-id="' + escapeHtml(String(challengeId)) + '">' +
      '<label class="form-label small mb-1">Votre message <span class="text-muted">(10 à 5000 caractères)</span></label>' +
      '<textarea class="form-control mb-2" name="body" rows="4" required placeholder="Décrivez votre réponse au défi…"></textarea>' +
      '<label class="form-label small mb-1">URL d\'image <span class="text-muted">(optionnel)</span></label>' +
      '<input type="url" class="form-control mb-2" name="imageUrl" placeholder="https://…" autocomplete="off">' +
      '<label class="form-label small mb-1">URL vidéo <span class="text-muted">(optionnel, ex. YouTube)</span></label>' +
      '<input type="url" class="form-control mb-2" name="videoUrl" placeholder="https://…" autocomplete="off">' +
      '<div class="text-danger small comment-form-error mb-2"></div>' +
      '<button type="submit" class="btn btn-primary btn-sm">Publier le commentaire</button>' +
      '</form></div>'
    );
  }ass="form-label small mb-1">URL d’image <span class="text-muted">(optionnel)</span></label>' +
      '<input type="url" class="form-control mb-2" name="imageUrl" placeholder="https://…" autocomplete="off">' +
      '<label class="form-label small mb-1">URL vidéo <span class="text-muted">(optionnel, ex. YouTube)</span></label>' +
      '<input type="url" class="form-control mb-2" name="videoUrl" placeholder="https://…" autocomplete="off">' +
      '<div class="text-danger small comment-form-error mb-2"></div>' +
      '<button type="submit" class="btn btn-primary btn-sm">Publier le commentaire</button>' +
      '</form></div>'
    );
  }

  function addComment(challengeId, body, imageUrl, videoUrl) {
    var wc = CL.wordCount(body);
    if (wc < 10 || wc > 500) {
      return { ok: false, msg: 'Le texte doit contenir entre 10 et 500 mots.' };
    }
    var img = (imageUrl || '').trim();
    var vid = (videoUrl || '').trim();
    if (img && !CL.isValidUrl(img)) {
      return { ok: false, msg: 'L’URL de l’image n’est pas valide.' };
    }
    if (vid && !CL.isValidUrl(vid)) {
      return { ok: false, msg: 'L’URL de la vidéo n’est pas valide.' };
    }
    return apiCreateComment(challengeId, body.trim(), img, vid)
      .then(function (res) {
        if (!res || !res.ok) {
          return { ok: false, msg: (res && res.message) ? String(res.message) : 'Erreur lors de la création du commentaire.' };
        }
        commentsCache[String(challengeId)] = undefined;
        return { ok: true };
      })
      .catch(function () {
        return { ok: false, msg: 'Erreur réseau pendant la création du commentaire.' };
      });
  }

  function upvoteComment(commentId) {
    return apiUpvoteComment(commentId)
      .then(function (res) {
        if (!res || !res.ok || !res.item) return;
        var challengeId = String(res.item.challenge_id || '');
        if (challengeId) commentsCache[challengeId] = undefined;
        renderChallenges();
      })
      .catch(function () {});
  }

  function upvoteChallenge(cid) {
    var vid = getVisitorId();
    var list = loadChallenges();
    var i = list.findIndex(function (c) {
      return String(c.id) === String(cid);
    });
    if (i === -1) return;
    var ch = list[i];
    if (!Array.isArray(ch.upvotedBy)) ch.upvotedBy = [];
    if (ch.upvotedBy.indexOf(vid) !== -1) return;
    ch.upvotedBy.push(vid);
    ch.upvoteCount = (Number(ch.upvoteCount) || 0) + 1;
    list[i] = ch;
    saveChallenges(list);
    renderChallenges();
  }

  function matchesQuery(ch, q) {
    if (!q) return true;
    var needle = String(q).trim().toLowerCase();
    if (!needle) return true;
    var hay = [
      ch.theme,
      ch.description,
      ch.flair,
      ch.creatorType,
      ch.startDate,
      ch.endDate
    ].map(function (x) { return String(x || '').toLowerCase(); }).join(' ');
    return hay.indexOf(needle) !== -1;
  }

  function sortChallengesList(items) {
    var list = items.slice();
    list.sort(function (a, b) {
      if (currentSort === 'date_asc') {
        return String(a.startDate || '').localeCompare(String(b.startDate || ''));
      }
      if (currentSort === 'upvotes_desc') {
        var ub = Number(b.upvoteCount) || 0;
        var ua = Number(a.upvoteCount) || 0;
        if (ub !== ua) return ub - ua;
        return String(b.startDate || '').localeCompare(String(a.startDate || ''));
      }
      if (currentSort === 'theme_asc') {
        return String(a.theme || '').localeCompare(String(b.theme || ''), 'fr', { sensitivity: 'base' });
      }
      if (currentSort === 'theme_desc') {
        return String(b.theme || '').localeCompare(String(a.theme || ''), 'fr', { sensitivity: 'base' });
      }
      return String(b.startDate || '').localeCompare(String(a.startDate || ''));
    });
    return list;
  }

    // Fonctions CRUD des défis
  let challengeModal;

  function clearChallengeErrors() {
    document.querySelectorAll('[id$="Error"]').forEach(el => {
      if (el.id.startsWith('challenge')) el.textContent = '';
    });
  }

  function renderPostOptions() {
    const sel = document.getElementById('challengePostId');
    if (!sel) return;
    const posts = (window.CareerLabForms && window.CareerLabForms.loadPosts)
      ? window.CareerLabForms.loadPosts()
      : (window.CareerLab && window.CareerLab.loadPostsRaw ? window.CareerLab.loadPostsRaw() : []);
    sel.innerHTML = '<option value="">-- Sélectionner --</option>' +
      posts.map(p => 
        '<option value="' + p.id + '">#' + p.id + ' — ' + escapeHtml((p.title || '').slice(0, 60)) + '</option>'
      ).join('');
  }

  function openChallengeModal(challengeId) {
    document.getElementById('challengeModalTitle').textContent = challengeId ? 'Modifier le défi' : 'Créer un défi';
    clearChallengeErrors();
    renderPostOptions();
    
    if (challengeId) {
      const challenges = loadChallenges();
      const challenge = challenges.find(c => c.id === challengeId);
      if (challenge) {
        document.getElementById('challengeId').value = challenge.id;
        document.getElementById('challengeTheme').value = challenge.theme || '';
        document.getElementById('challengeDescription').value = challenge.description || '';
        document.getElementById('challengeFlair').value = challenge.flair || 'Projet';
        document.getElementById('challengeCreatorType').value = challenge.creatorType || 'Formateur';
        document.getElementById('challengeStartDate').value = challenge.startDate || '';
        document.getElementById('challengeEndDate').value = challenge.endDate || '';
        document.getElementById('challengeRewardType').value = challenge.rewardType || 'job';
        document.getElementById('challengeRewardTitle').value = challenge.rewardTitle || '';
        document.getElementById('challengeRewardDescription').value = challenge.rewardDescription || '';
        document.getElementById('challengePostId').value = challenge.postId || '';
      }
    } else {
      document.getElementById('challengeForm').reset();
      document.getElementById('challengeId').value = '';
      const now = new Date().toISOString().split('T')[0];
      document.getElementById('challengeStartDate').value = now;
      document.getElementById('challengeEndDate').value = now;
      document.getElementById('challengeFlair').value = 'Projet';
      document.getElementById('challengeCreatorType').value = 'Formateur';
      document.getElementById('challengeRewardType').value = 'job';
    }
    challengeModal.show();
  }

  function saveChallenge() {
    clearChallengeErrors();
    const CF = window.CareerLabForms || {
      validateChallenge: function (data) {
        const errors = {};
        const t = String(data.theme || '').trim().length;
        const d = String(data.description || '').trim().length;
        const rt = String(data.rewardTitle || '').trim().length;
        const rd = String(data.rewardDescription || '').trim().length;
        if (t < 10 || t > 200) errors.theme = 'Le thème doit contenir entre 10 et 200 caractères.';
        if (d < 10 || d > 5000) errors.description = 'La description doit contenir entre 10 et 5000 caractères.';
        if (!['Projet','Showcast','Débat','Pitch'].includes(data.flair)) errors.flair = 'Flair invalide.';
        if (!['Formateur','Entreprise'].includes(data.creatorType)) errors.creatorType = 'Type de créateur invalide.';
        if (!data.startDate) errors.startDate = 'La date de début est obligatoire.';
        if (!data.endDate) errors.endDate = 'La date de fin est obligatoire.';
        if (data.startDate && data.endDate && data.endDate <= data.startDate) errors.endDate = 'La date de fin doit être après la date de début.';
        if (!['job','course'].includes(data.rewardType)) errors.rewardType = 'Type de récompense invalide.';
        if (rt < 10 || rt > 200) errors.rewardTitle = 'Le titre de la récompense doit contenir entre 10 et 200 caractères.';
        if (rd < 10 || rd > 5000) errors.rewardDescription = 'La description de la récompense doit contenir entre 10 et 5000 caractères.';
        if (!data.postId) errors.postId = 'Vous devez sélectionner un post.';
        return { valid: Object.keys(errors).length === 0, errors };
      }
    };
    const challengeId = document.getElementById('challengeId').value;
    const data = {
      theme: document.getElementById('challengeTheme').value.trim(),
      description: document.getElementById('challengeDescription').value.trim(),
      flair: document.getElementById('challengeFlair').value,
      creatorType: document.getElementById('challengeCreatorType').value,
      startDate: document.getElementById('challengeStartDate').value,
      endDate: document.getElementById('challengeEndDate').value,
      rewardType: document.getElementById('challengeRewardType').value,
      rewardTitle: document.getElementById('challengeRewardTitle').value.trim(),
      rewardDescription: document.getElementById('challengeRewardDescription').value.trim(),
      postId: document.getElementById('challengePostId').value
    };

    const result = CF.validateChallenge(data);
    if (!result.valid) {
      Object.keys(result.errors).forEach(key => {
        const el = document.getElementById('challenge' + key.charAt(0).toUpperCase() + key.slice(1) + 'Error');
        if (el) el.textContent = result.errors[key];
      });
      return;
    }

    const challenges = loadChallenges();
    if (challengeId) {
      const idx = challenges.findIndex(c => c.id === parseInt(challengeId, 10));
      if (idx !== -1) {
        const prev = challenges[idx];
        challenges[idx] = {
          id: prev.id,
          theme: data.theme,
          description: data.description,
          flair: data.flair,
          creatorType: data.creatorType,
          startDate: data.startDate,
          endDate: data.endDate,
          status: prev.status || 'upcoming',
          rewardType: data.rewardType,
          rewardTitle: data.rewardTitle,
          rewardDescription: data.rewardDescription,
          postId: data.postId,
          upvoteCount: prev.upvoteCount || 0,
          upvotedBy: prev.upvotedBy || []
        };
      }
    } else {
      challenges.push({
        id: Date.now(),
        theme: data.theme,
        description: data.description,
        flair: data.flair,
        creatorType: data.creatorType,
        startDate: data.startDate,
        endDate: data.endDate,
        status: 'upcoming',
        rewardType: data.rewardType,
        rewardTitle: data.rewardTitle,
        rewardDescription: data.rewardDescription,
        postId: data.postId,
        upvoteCount: 0,
        upvotedBy: []
      });
    }
    saveChallenges(challenges);
    renderChallenges();
    challengeModal.hide();
  }

  function deleteChallenge(challengeId) {
    if (!confirm('Supprimer ce défi ?')) return;
    const challenges = loadChallenges().filter(c => c.id !== challengeId);
    saveChallenges(challenges);
    renderChallenges();
  }

  function renderChallenges() {
    var root = document.getElementById('challengeList');
    var emptyEl = document.getElementById('challengeEmptyMsg');
    if (!root) return;
    var params = getParams();
    var onlyId = params.get('challengeId');
    
    // If a specific challenge is requested, load from database API
    if (onlyId) {
      fetch('index.php?c=challenge&a=comments&challenge_id=' + encodeURIComponent(String(onlyId)))
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (!data.ok) {
            root.innerHTML = '<div class="alert alert-danger">Défi introuvable.</div>';
            return;
          }
          // Challenge found, render it with comments
          renderSingleChallenge(onlyId, data.items || []);
        })
        .catch(function (err) {
          root.innerHTML = '<div class="alert alert-danger">Erreur lors du chargement du défi: ' + escapeHtml(err) + '</div>';
        });
      return;
    }

    var list = loadChallenges();
    if (!onlyId) {
      list = list
        .filter(function (c) { return isVisibleStatus(c.status); })
        .filter(function (c) { return matchesQuery(c, currentQuery); });
    }
    list = sortChallengesList(list);
    if (!list.length) {
      root.innerHTML = '';
      if (emptyEl) emptyEl.style.display = '';
      if (CL && CL.renderSidebarRecentChallenges) CL.renderSidebarRecentChallenges('sidebarRecentChallenges', 5);
      return;
    }
    if (emptyEl) emptyEl.style.display = 'none';
    var vid = getVisitorId();
    root.innerHTML = list.map(function (ch) {
      var id = ch.id;
      var upvoted = Array.isArray(ch.upvotedBy) && ch.upvotedBy.indexOf(vid) !== -1;
      var count = Number(ch.upvoteCount) || 0;
      var st = String(ch.status || '').toLowerCase();
      var isUpcoming = st === 'upcoming';
      var statusBadge = isUpcoming
        ? '<span class="badge bg-warning text-dark text-uppercase small">à venir</span>'
        : '<span class="badge bg-success text-uppercase small">actif</span>';
      return (
        '<div class="mb-5 bg-light rounded overflow-hidden p-4 wow slideInUp" data-wow-delay="0.1s">' +
        '<div class="d-flex flex-wrap gap-2 mb-2">' +
        '<span class="badge bg-primary">' + escapeHtml(ch.flair || '') + '</span>' +
        '<span class="badge bg-secondary text-uppercase small">' + escapeHtml(ch.creatorType || '') + '</span>' +
        statusBadge +
        '</div>' +
        '<h2 class="mb-3"><a class="text-dark text-decoration-none" href="detail.php?challengeId=' + encodeURIComponent(String(id)) + '">' + escapeHtml(ch.theme || '') + '</a></h2>' +
        '<p class="text-muted small mb-2">' + escapeHtml(ch.startDate || '') + ' → ' + escapeHtml(ch.endDate || '') + '</p>' +
        '<p class="mb-3">' + escapeHtml(ch.description || '').replace(/\n/g, '<br>') + '</p>' +
        '<div class="border-top pt-3 mt-2">' +
        '<p class="mb-1 small text-uppercase text-muted">Récompense</p>' +
        '<p class="fw-semibold mb-1">' + escapeHtml(ch.rewardTitle || '') + '</p>' +
        '<p class="mb-3 small">' + escapeHtml(ch.rewardDescription || '') + '</p>' +
        (onlyId ? '<a href="detail.php" class="btn btn-sm btn-outline-secondary me-2">&larr; Retour aux défis</a>' : '') +
        (onlyId ? '' : '<a href="detail.php?challengeId=' + encodeURIComponent(String(id)) + '" class="btn btn-sm btn-outline-secondary me-2">Voir le défi</a>') +
        '<button type="button" class="btn btn-sm btn-primary js-challenge-upvote"' + (upvoted ? ' disabled' : '') + ' data-challenge-id="' + escapeHtml(String(id)) + '">' +
        '<i class="bi bi-hand-thumbs-up me-1"></i> Soutenir le défi <span class="badge bg-light text-primary">' + count + '</span></button>' +
        (upvoted ? ' <span class="small text-muted">Vous soutenez ce défi.</span>' : '') +
        '</div>' +
        (isUpcoming
          ? '<div class="mt-4 pt-3 border-top"><div class="alert alert-warning mb-0">Ce défi est <b>à venir</b>. Les commentaires seront disponibles quand le post associé atteindra le minimum d’upvotes.</div></div>'
          : renderCommentsBlock(id)) +
        '</div>'
      );
    }).join('');
    if (window.WOW) {
      try {
        new WOW().init();
      } catch (e) {}
    }
    if (CL && CL.renderSidebarRecentChallenges) CL.renderSidebarRecentChallenges('sidebarRecentChallenges', 5);
  }

  document.addEventListener('DOMContentLoaded', function () {
    var root = document.getElementById('challengeList');
    var params = getParams();
    var q = params.get('q');
    if (q && /^https?:\/\//i.test(q)) {
      q = '';
    }
    var onlyId = params.get('challengeId');

    var searchInput = document.getElementById('challengeSearchInput');
    var searchBtn = document.getElementById('challengeSearchBtn');
    var sortSel = document.getElementById('challengeSortSelect');
    var sortParam = params.get('sort');
    if (sortParam) currentSort = String(sortParam);

    function applySort(newSort) {
      currentSort = String(newSort || 'date_desc');
      var next = new URL(window.location.href);
      next.searchParams.set('sort', currentSort);
      window.history.replaceState({}, '', next.toString());
      renderChallenges();
    }

    function applySearch(newQ) {
      currentQuery = String(newQ || '').trim();
      if (currentQuery) {
        var next = new URL(window.location.href);
        next.searchParams.set('q', currentQuery);
        next.searchParams.set('sort', currentSort);
        next.searchParams.delete('challengeId');
        window.history.replaceState({}, '', next.toString());
      } else {
        var next2 = new URL(window.location.href);
        next2.searchParams.delete('q');
        next2.searchParams.set('sort', currentSort);
        next2.searchParams.delete('challengeId');
        window.history.replaceState({}, '', next2.toString());
      }
      renderChallenges();
    }

    if (root) {
      root.addEventListener('click', function (e) {
        var b = e.target.closest('.js-challenge-upvote');
        if (b && !b.disabled) {
          upvoteChallenge(b.getAttribute('data-challenge-id'));
          return;
        }
        var cu = e.target.closest('.js-comment-upvote');
        if (cu && !cu.disabled) {
          cu.disabled = true;
          upvoteComment(cu.getAttribute('data-comment-id')).finally(function () {
            cu.disabled = false;
          });
        }
      });
      root.addEventListener('submit', function (e) {
        var form = e.target.closest('.challenge-comment-form');
        if (!form) return;
        e.preventDefault();
        var err = form.querySelector('.comment-form-error');
        var cid = form.getAttribute('data-challenge-id');
        var body = form.querySelector('[name="body"]').value;
        var imageUrl = form.querySelector('[name="imageUrl"]').value;
        var videoUrl = form.querySelector('[name="videoUrl"]').value;
        var submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;
        addComment(cid, body, imageUrl, videoUrl).then(function (result) {
          if (!result.ok) {
            err.textContent = result.msg;
            return;
          }
          err.textContent = '';
          form.reset();
          renderChallenges();
        }).finally(function () {
          if (submitBtn) submitBtn.disabled = false;
        });
      });
    }
    if (searchInput) {
      var initial = onlyId ? '' : (q || '');
      searchInput.value = initial;
      currentQuery = String(initial || '').trim();
      searchInput.addEventListener('input', function () {
        applySearch(searchInput.value);
      });
      searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          applySearch(searchInput.value);
        }
      });
    }
    if (searchBtn) {
      searchBtn.addEventListener('click', function () {
        applySearch(searchInput ? searchInput.value : '');
      });
    }
    if (sortSel) {
      sortSel.value = currentSort;
      sortSel.addEventListener('change', function () {
        applySort(sortSel.value);
      });
    }

    // Challenge CRUD Event Listeners
    challengeModal = new bootstrap.Modal(document.getElementById('challengeModal'));
    const createChallengeBtn = document.getElementById('createChallengeBtn');
    const saveChallengBtn = document.getElementById('saveChallengBtn');
    if (createChallengeBtn) {
      createChallengeBtn.addEventListener('click', function () {
        openChallengeModal(null);
      });
    }
    if (saveChallengBtn) {
      saveChallengBtn.addEventListener('click', saveChallenge);
    }
    document.getElementById('challengeForm').addEventListener('submit', function (e) {
      e.preventDefault();
      saveChallenge();
    });

    if (onlyId) {
      if (searchInput) {
        searchInput.value = '';
        searchInput.setAttribute('placeholder', 'Recherche... (désactivée en vue défi unique)');
        searchInput.disabled = true;
      }
      if (sortSel) sortSel.disabled = true;
      if (searchBtn) searchBtn.disabled = true;
    }

    renderChallenges();
  });
})();
</script>
<script>
(function () {
  function hideSpinnerSafe() {
    var sp = document.getElementById('spinner');
    if (!sp) return;
    sp.classList.remove('show');
    sp.style.display = 'none';
  }
  document.addEventListener('DOMContentLoaded', hideSpinnerSafe);
  window.addEventListener('load', hideSpinnerSafe);
  setTimeout(hideSpinnerSafe, 1500);
})();
</script>
</body>

</html>