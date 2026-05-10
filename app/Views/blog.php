<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Blog — Career Lab</title>
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
                            <a href="blog.html" class="dropdown-item active">Blog</a>
                            <a href="detail.html" class="dropdown-item">Défis</a>
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
                <a href="blog.html" class="btn btn-primary py-2 px-4 ms-3">Articles</a>
            </div>
        </nav>

        <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
            <div class="row py-5">
                <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                    <h1 class="display-4 text-white animated zoomIn">Blog</h1>
                    <a href="indexF.html" class="h5 text-white">Accueil</a>
                    <i class="far fa-circle text-white px-2"></i>
                    <a href="blog.html" class="h5 text-white">Blog</a>
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
                    <button type="button" class="btn bg-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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


    <!-- Blog Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <!-- Blog list Start -->
                <div class="col-lg-8">
                    <p class="text-muted mb-3" id="blogEmptyMsg" style="display:none;">Aucun article pour le moment. Créez des articles depuis l’administration.</p>
                    <div class="row g-5" id="blogPostsGrid"></div>
                </div>
                <!-- Blog list End -->
    
                <!-- Sidebar Start -->
                <div class="col-lg-4">
                    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s">
                        <button class="btn btn-success w-100 mb-3" type="button" id="createPostBtn">
                            <i class="bi bi-plus-circle me-2"></i>Créer un article
                        </button>
                        <div class="input-group">
                            <input type="search" class="form-control p-3" placeholder="Rechercher…" aria-label="Rechercher" id="blogSearchInput">
                            <button class="btn btn-primary px-4" type="button" id="blogSearchBtn" aria-label="Lancer la recherche"><i class="bi bi-search"></i></button>
                        </div>
                        <p class="small text-muted mt-2 mb-0">Recherchez par titre, contenu, flair ou auteur.</p>
                    </div>
                    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s">
                        <div class="section-title section-title-sm position-relative pb-3 mb-4">
                            <h3 class="mb-0">Articles récents</h3>
                        </div>
                        <div id="sidebarRecentPosts"></div>
                    </div>
                </div>
                <!-- Sidebar End -->
            </div>
        </div>
    </div>
    <!-- Blog End -->


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
                                <input type="text" class="form-control border-white p-3" placeholder="Your Email">
                                <button class="btn btn-dark">Sign Up</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="row gx-5">
                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Get In Touch</h3>
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
                                <h3 class="text-light mb-0">Quick Links</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
  
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Meet The Team</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest Blog</a>
                                <a class="text-light" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Popular Links</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>

                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Meet The Team</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest Blog</a>
                                <a class="text-light" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
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
                        <p class="mb-0">&copy; <a class="text-white border-bottom" href="#">Your Site Name</a>. All Rights Reserved. 
						
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

    <!-- Post Modal -->
    <div class="modal fade" id="postModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postModalTitle">Créer un article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="postForm">
                        <div class="mb-3">
                            <label class="form-label">Titre *</label>
                            <input type="text" class="form-control" id="postTitle" placeholder="10-200 caractères">
                            <div class="text-danger small mt-1" id="postTitleError"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contenu *</label>
                            <textarea class="form-control" id="postBody" rows="5" placeholder="10-5000 caractères"></textarea>
                            <div class="text-danger small mt-1" id="postBodyError"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">URL Photo</label>
                            <input type="url" class="form-control" id="postPhotoUrl" placeholder="https://...">
                            <div class="text-danger small mt-1" id="postPhotoUrlError"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Flair *</label>
                                <select class="form-control" id="postFlair">
                                    <option value="Question">Question</option>
                                    <option value="Thread">Thread</option>
                                    <option value="Disclaimer">Disclaimer</option>
                                </select>
                                <div class="text-danger small mt-1" id="postFlairError"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type d'auteur *</label>
                                <select class="form-control" id="postAuthorType">
                                    <option value="Utilisateur">Utilisateur</option>
                                    <option value="Formateur">Formateur</option>
                                    <option value="Entreprise">Entreprise</option>
                                </select>
                                <div class="text-danger small mt-1" id="postAuthorTypeError"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Statut *</label>
                                <select class="form-control" id="postStatus">
                                    <option value="active">Actif</option>
                                    <option value="locked">Verrouillé</option>
                                </select>
                                <div class="text-danger small mt-1" id="postStatusError"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Upvotes min pour défi</label>
                                <input type="number" class="form-control" id="postMinUpvotes" value="0" min="0">
                                <div class="text-danger small mt-1" id="postMinUpvotesError"></div>
                            </div>
                        </div>
                        <input type="hidden" id="postId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="savePostBtn">Enregistrer</button>
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
  const POST_KEY = 'blogPosts';
  var CL = window.CareerLab;
  var currentQuery = '';

  function escapeHtml(s) {
    if (s == null) return '';
    const d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
  }

  function getParams() {
    try {
      return new URLSearchParams(window.location.search || '');
    } catch (e) {
      return new URLSearchParams('');
    }
  }

  function getVisitorId() {
    let id = localStorage.getItem('careerLabVisitorId');
    if (!id) {
      id = 'v_' + Date.now() + '_' + Math.random().toString(36).slice(2, 11);
      localStorage.setItem('careerLabVisitorId', id);
    }
    return id;
  }

  function loadPostsRaw() {
    try {
      const raw = localStorage.getItem(POST_KEY);
      return raw ? JSON.parse(raw) : [];
    } catch (e) {
      return [];
    }
  }

  function savePosts(posts) {
    localStorage.setItem(POST_KEY, JSON.stringify(posts));
  }

  function isActiveStatus(s) {
    return String(s || '').toLowerCase() === 'active';
  }

  function safeImgUrl(url) {
    if (!url || !String(url).trim()) return 'img/blog-1.jpg';
    try {
      new URL(url);
      return url;
    } catch (e) {
      return 'img/blog-1.jpg';
    }
  }

  function excerpt(body, maxWords) {
    const w = String(body || '').trim().split(/\s+/).filter(Boolean);
    if (w.length <= maxWords) return escapeHtml(w.join(' '));
    return escapeHtml(w.slice(0, maxWords).join(' ') + '…');
  }

  function upvoteBlogPost(postId) {
    const vid = getVisitorId();
    const posts = loadPostsRaw();
    const i = posts.findIndex(function (p) { return String(p.id) === String(postId); });
    if (i === -1) return;
    const p = posts[i];
    if (!Array.isArray(p.upvotedBy)) p.upvotedBy = [];
    if (p.upvotedBy.indexOf(vid) !== -1) return;
    p.upvotedBy.push(vid);
    p.upvoteCount = (Number(p.upvoteCount) || 0) + 1;
    posts[i] = p;
    savePosts(posts);
    renderBlogPosts();
  }

  function voteBlogPoll(postId, optionIndex) {
    const key = 'blogPollVote_' + postId;
    if (localStorage.getItem(key) !== null) return;
    const posts = loadPostsRaw();
    const i = posts.findIndex(function (p) { return String(p.id) === String(postId); });
    if (i === -1) return;
    const p = posts[i];
    const opts = p.pollOptions || [];
    if (optionIndex < 0 || optionIndex >= opts.length) return;
    if (!Array.isArray(p.pollVotes)) p.pollVotes = opts.map(function () { return 0; });
    while (p.pollVotes.length < opts.length) p.pollVotes.push(0);
    p.pollVotes[optionIndex] = (Number(p.pollVotes[optionIndex]) || 0) + 1;
    localStorage.setItem(key, String(optionIndex));
    posts[i] = p;
    savePosts(posts);
    renderBlogPosts();
  }

  function matchesQuery(post, q) {
    if (!q) return true;
    const needle = String(q).trim().toLowerCase();
    if (!needle) return true;
    const hay = [
      post.title,
      post.body,
      post.flair,
      post.authorType,
      post.createdAt,
    ]
      .map(function (x) { return String(x || '').toLowerCase(); })
      .join(' ');
    return hay.indexOf(needle) !== -1;
  }

  function renderSinglePost(postId) {
    const grid = document.getElementById('blogPostsGrid');
    const emptyEl = document.getElementById('blogEmptyMsg');
    if (!grid) return;

    const posts = loadPostsRaw().filter(function (p) { return isActiveStatus(p.status); });
    const post = posts.find(function (p) { return String(p.id) === String(postId); });
    if (!post) {
      grid.innerHTML = '';
      if (emptyEl) {
        emptyEl.textContent = 'Article introuvable.';
        emptyEl.style.display = '';
      }
      return;
    }
    if (emptyEl) emptyEl.style.display = 'none';

    const pid = post.id;
    const img = safeImgUrl(post.photoUrl);
    const flair = escapeHtml(post.flair || '');
    const author = escapeHtml(post.authorType || '');
    const date = escapeHtml(post.createdAt || '');
    const title = escapeHtml(post.title || '');
    const bodyHtml = escapeHtml(post.body || '').replace(/\n/g, '<br>');
    const count = Number(post.upvoteCount) || 0;
    const vid = getVisitorId();
    const upvoted = Array.isArray(post.upvotedBy) && post.upvotedBy.indexOf(vid) !== -1;

    const pollKey = 'blogPollVote_' + pid;
    const hasVotedPoll = localStorage.getItem(pollKey) !== null;
    const opts = post.pollOptions || [];
    const votes = post.pollVotes || [];
    let pollBlock = '';
    if (opts.length >= 2) {
      const total = opts.reduce(function (s, _, idx) {
        return s + (Number(votes[idx]) || 0);
      }, 0);
      pollBlock = '<div class="mt-3 pt-2 border-top"><div class="small text-muted mb-2">Sondage</div>';
      opts.forEach(function (label, idx) {
        const v = Number(votes[idx]) || 0;
        const pct = total ? Math.round((100 * v) / total) : 0;
        const disabled = hasVotedPoll ? ' disabled' : '';
        pollBlock += '<div class="d-flex align-items-center gap-2 mb-1"><button type="button" class="btn btn-sm btn-outline-primary js-poll-vote' + disabled + '" data-post-id="' + escapeHtml(String(pid)) + '" data-opt="' + idx + '"' + disabled + '>' + escapeHtml(label) + '</button><span class="small text-muted">' + v + ' (' + pct + '%)</span></div>';
      });
      if (hasVotedPoll) pollBlock += '<div class="small text-success mt-1">Merci pour votre vote.</div>';
      pollBlock += '</div>';
    }

    grid.innerHTML =
      '<div class="col-12">' +
      '<div class="bg-light rounded overflow-hidden">' +
      '<div class="position-relative overflow-hidden">' +
      '<img class="img-fluid w-100" src="' + escapeHtml(img) + '" alt="">' +
      '<span class="position-absolute top-0 start-0 bg-primary text-white rounded-end mt-5 py-2 px-4">' + flair + '</span>' +
      '</div>' +
      '<div class="p-4">' +
      '<a href="blog.html" class="text-muted small">&larr; Retour aux articles</a>' +
      '<div class="d-flex mt-3 mb-3 flex-wrap">' +
      '<small class="me-3"><i class="far fa-user text-primary me-2"></i>' + author + '</small>' +
      '<small><i class="far fa-calendar-alt text-primary me-2"></i>' + date + '</small>' +
      '</div>' +
      '<h2 class="mb-3">' + title + '</h2>' +
      '<p class="mb-0">' + bodyHtml + '</p>' +
      pollBlock +
      '<div class="mt-3 pt-2 border-top d-flex align-items-center gap-3">' +
      '<button type="button" class="btn btn-sm btn-primary js-post-upvote"' + (upvoted ? ' disabled' : '') + ' data-post-id="' + escapeHtml(String(pid)) + '"><i class="bi bi-hand-thumbs-up me-1"></i> Soutenir <span class="badge bg-light text-primary">' + count + '</span></button>' +
      (upvoted ? '<span class="small text-muted">Vous avez soutenu cet article.</span>' : '') +
      '</div>' +
      '</div></div></div>';

    if (CL && CL.renderSidebarRecentPosts) CL.renderSidebarRecentPosts('sidebarRecentPosts', 5);
  }

  // CRUD Functions for Posts
  let postModal;

  function clearPostErrors() {
    document.querySelectorAll('[id$="Error"]').forEach(el => {
      if (el.id.startsWith('post')) el.textContent = '';
    });
  }

  function openPostModal(postId) {
    document.getElementById('postModalTitle').textContent = postId ? 'Éditer l\'article' : 'Créer un article';
    clearPostErrors();
    if (postId) {
      const posts = loadPostsRaw();
      const post = posts.find(p => p.id === postId);
      if (post) {
        document.getElementById('postId').value = post.id;
        document.getElementById('postTitle').value = post.title || '';
        document.getElementById('postBody').value = post.body || '';
        document.getElementById('postPhotoUrl').value = post.photoUrl || '';
        document.getElementById('postFlair').value = post.flair || 'Question';
        document.getElementById('postAuthorType').value = post.authorType || 'Utilisateur';
        document.getElementById('postStatus').value = post.status || 'active';
        document.getElementById('postMinUpvotes').value = post.minUpvotesForChallenge || 0;
      }
    } else {
      document.getElementById('postForm').reset();
      document.getElementById('postId').value = '';
      document.getElementById('postStatus').value = 'active';
      document.getElementById('postMinUpvotes').value = 0;
    }
    postModal.show();
  }

  function savePost() {
    clearPostErrors();
    const CF = window.CareerLabForms;
    const postId = document.getElementById('postId').value;
    const data = {
      title: document.getElementById('postTitle').value.trim(),
      body: document.getElementById('postBody').value.trim(),
      photoUrl: document.getElementById('postPhotoUrl').value.trim(),
      flair: document.getElementById('postFlair').value,
      authorType: document.getElementById('postAuthorType').value,
      status: document.getElementById('postStatus').value,
      minUpvotesForChallenge: parseInt(document.getElementById('postMinUpvotes').value, 10) || 0
    };

    const result = CF.validatePost(data);
    if (!result.valid) {
      Object.keys(result.errors).forEach(key => {
        const el = document.getElementById('post' + key.charAt(0).toUpperCase() + key.slice(1) + 'Error');
        if (el) el.textContent = result.errors[key];
      });
      return;
    }

    const posts = loadPostsRaw();
    if (postId) {
      const idx = posts.findIndex(p => p.id === parseInt(postId, 10));
      if (idx !== -1) {
        const prev = posts[idx];
        posts[idx] = {
          id: prev.id,
          title: data.title,
          body: data.body,
          photoUrl: data.photoUrl,
          flair: data.flair,
          authorType: data.authorType,
          status: data.status,
          minUpvotesForChallenge: data.minUpvotesForChallenge,
          createdAt: prev.createdAt,
          upvoteCount: prev.upvoteCount || 0,
          upvotedBy: prev.upvotedBy || [],
          pollOptions: prev.pollOptions || [],
          pollVotes: prev.pollVotes || []
        };
      }
    } else {
      posts.push({
        id: Date.now(),
        title: data.title,
        body: data.body,
        photoUrl: data.photoUrl,
        flair: data.flair,
        authorType: data.authorType,
        status: data.status,
        minUpvotesForChallenge: data.minUpvotesForChallenge,
        createdAt: CF.todayISODate(),
        upvoteCount: 0,
        upvotedBy: [],
        pollOptions: [],
        pollVotes: []
      });
    }
    savePosts(posts);
    renderBlogPosts();
    postModal.hide();
  }

  function deletePost(postId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) return;
    const posts = loadPostsRaw().filter(p => p.id !== postId);
    savePosts(posts);
    renderBlogPosts();
  }

  function renderBlogPosts() {
    const grid = document.getElementById('blogPostsGrid');
    const emptyEl = document.getElementById('blogEmptyMsg');
    if (!grid) return;
    const posts = loadPostsRaw()
      .filter(function (p) { return isActiveStatus(p.status); })
      .filter(function (p) { return matchesQuery(p, currentQuery); });
    posts.sort(function (a, b) {
      return String(b.createdAt || '').localeCompare(String(a.createdAt || ''));
    });
    if (!posts.length) {
      grid.innerHTML = '';
      if (emptyEl) emptyEl.style.display = '';
      return;
    }
    if (emptyEl) emptyEl.style.display = 'none';
    const vid = getVisitorId();
    grid.innerHTML = posts.map(function (post) {
      const pid = post.id;
      const img = safeImgUrl(post.photoUrl);
      const flair = escapeHtml(post.flair || '');
      const author = escapeHtml(post.authorType || '');
      const date = escapeHtml(post.createdAt || '');
      const title = escapeHtml(post.title || '');
      const bodyEx = excerpt(post.body, 40);
      const count = Number(post.upvoteCount) || 0;
      const upvoted = Array.isArray(post.upvotedBy) && post.upvotedBy.indexOf(vid) !== -1;
      const pollKey = 'blogPollVote_' + pid;
      const hasVotedPoll = localStorage.getItem(pollKey) !== null;
      const opts = post.pollOptions || [];
      const votes = post.pollVotes || [];
      let pollBlock = '';
      if (opts.length >= 2) {
        const total = opts.reduce(function (s, _, idx) {
          return s + (Number(votes[idx]) || 0);
        }, 0);
        pollBlock = '<div class="mt-3 pt-2 border-top"><div class="small text-muted mb-2">Sondage</div>';
        opts.forEach(function (label, idx) {
          const v = Number(votes[idx]) || 0;
          const pct = total ? Math.round((100 * v) / total) : 0;
          const disabled = hasVotedPoll ? ' disabled' : '';
          pollBlock += '<div class="d-flex align-items-center gap-2 mb-1"><button type="button" class="btn btn-sm btn-outline-primary js-poll-vote' + disabled + '" data-post-id="' + escapeHtml(String(pid)) + '" data-opt="' + idx + '"' + disabled + '>' + escapeHtml(label) + '</button><span class="small text-muted">' + v + ' (' + pct + '%)</span></div>';
        });
        if (hasVotedPoll) pollBlock += '<div class="small text-success mt-1">Merci pour votre vote.</div>';
        pollBlock += '</div>';
      }
      return (
        '<div class="col-md-6 wow slideInUp" data-wow-delay="0.1s">' +
        '<div class="blog-item bg-light rounded overflow-hidden h-100 d-flex flex-column">' +
        '<div class="blog-img position-relative overflow-hidden">' +
        '<a href="blog.html?postId=' + encodeURIComponent(String(pid)) + '" class="d-block">' +
        '<img class="img-fluid" src="' + escapeHtml(img) + '" alt="">' +
        '</a>' +
        '<span class="position-absolute top-0 start-0 bg-primary text-white rounded-end mt-5 py-2 px-4">' + flair + '</span>' +
        '</div>' +
        '<div class="p-4 flex-grow-1 d-flex flex-column">' +
        '<div class="d-flex mb-3 flex-wrap">' +
        '<small class="me-3"><i class="far fa-user text-primary me-2"></i>' + author + '</small>' +
        '<small><i class="far fa-calendar-alt text-primary me-2"></i>' + date + '</small>' +
        '</div>' +
        '<h4 class="mb-3"><a href="blog.html?postId=' + encodeURIComponent(String(pid)) + '" class="text-dark text-decoration-none">' + title + '</a></h4>' +
        '<p class="flex-grow-1">' + bodyEx + '</p>' +
        pollBlock +
        '<div class="mt-3 pt-2 border-top d-flex align-items-center gap-3">' +
        '<button type="button" class="btn btn-sm btn-primary js-post-upvote"' + (upvoted ? ' disabled' : '') + ' data-post-id="' + escapeHtml(String(pid)) + '"><i class="bi bi-hand-thumbs-up me-1"></i> Soutenir <span class="badge bg-light text-primary">' + count + '</span></button>' +
        (upvoted ? '<span class="small text-muted">Vous avez soutenu cet article.</span>' : '') +
        '</div>' +
        '</div></div></div>'
      );
    }).join('');
    if (window.WOW) {
      try { new WOW().init(); } catch (e) {}
    }
    if (CL && CL.renderSidebarRecentPosts) CL.renderSidebarRecentPosts('sidebarRecentPosts', 5);
  }

  document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('blogPostsGrid');
    const params = getParams();
    const postId = params.get('postId');
    const q = params.get('q');

    const searchInput = document.getElementById('blogSearchInput');
    const searchBtn = document.getElementById('blogSearchBtn');

    function applySearch(newQ) {
      currentQuery = String(newQ || '').trim();
      if (currentQuery) {
        const next = new URL(window.location.href);
        next.searchParams.set('q', currentQuery);
        next.searchParams.delete('postId');
        window.history.replaceState({}, '', next.toString());
      } else {
        const next = new URL(window.location.href);
        next.searchParams.delete('q');
        next.searchParams.delete('postId');
        window.history.replaceState({}, '', next.toString());
      }
      renderBlogPosts();
    }

    if (grid) {
      grid.addEventListener('click', function (e) {
        const u = e.target.closest('.js-post-upvote');
        if (u && !u.disabled) {
          upvoteBlogPost(u.getAttribute('data-post-id'));
          return;
        }
        const pv = e.target.closest('.js-poll-vote');
        if (pv && !pv.disabled) {
          const id = pv.getAttribute('data-post-id');
          const opt = parseInt(pv.getAttribute('data-opt'), 10);
          if (!Number.isNaN(opt)) voteBlogPoll(id, opt);
        }
      });
    }

    if (searchInput) {
      const initial = postId ? '' : (q || '');
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

    // Post CRUD Event Listeners
    postModal = new bootstrap.Modal(document.getElementById('postModal'));
    const createPostBtn = document.getElementById('createPostBtn');
    const savePostBtn = document.getElementById('savePostBtn');
    if (createPostBtn) {
      createPostBtn.addEventListener('click', function () {
        openPostModal(null);
      });
    }
    if (savePostBtn) {
      savePostBtn.addEventListener('click', savePost);
    }
    document.getElementById('postForm').addEventListener('submit', function (e) {
      e.preventDefault();
      savePost();
    });

    if (postId) {
      if (searchInput) {
        searchInput.value = '';
        searchInput.setAttribute('placeholder', 'Rechercher… (désactivé en vue article)');
        searchInput.disabled = true;
      }
      if (searchBtn) searchBtn.disabled = true;
      renderSinglePost(postId);
    } else {
      renderBlogPosts();
    }
    if (CL && CL.renderSidebarRecentPosts) CL.renderSidebarRecentPosts('sidebarRecentPosts', 5);
  });
})();
</script>
</body>

</html>