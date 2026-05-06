<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$aiSupportFlash = $_SESSION['ai_support_flash'] ?? null;
if ($aiSupportFlash !== null) {
    unset($_SESSION['ai_support_flash']);
}
$aiSupportOld = is_array($aiSupportFlash['old'] ?? null) ? $aiSupportFlash['old'] : [];
$aiSupportErrors = is_array($aiSupportFlash['errors'] ?? null) ? $aiSupportFlash['errors'] : [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Carrer Lab </title>
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
    <link href="Views/assets/css/owl.carousel.min.css" rel="stylesheet">
    <link href="Views/assets/css/animate.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="Views/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="Views/assets/css/style.css" rel="stylesheet">
    <style>
        .navbar-user-line {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-profile-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            margin-left: 1rem;
            margin-right: 0.5rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            text-decoration: none;
            transition: all 0.2s ease;
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
            width: 20px;
            height: 20px;
            border-radius: 999px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #fff;
            font-size: 12px;
            line-height: 1;
            box-shadow: 0 6px 14px rgba(59, 130, 246, 0.32);
            flex: 0 0 auto;
        }

        .ai-support-response {
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 8px;
            padding: 14px;
            text-align: left;
        }
    </style>
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
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>Esprit, Ghazela</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+216 98 542 321</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>careerlab@gmail.com</small>
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


    <!-- Navbar & Carousel Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="index.php?page=accueil" class="navbar-brand p-0">
                <img src="Views/assets/img/image_2026-04-11_005109464-removebg-preview.png" alt="CareerLab" style="height: 52px; max-width: 100%;" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.php?page=accueil" class="nav-item nav-link active">Accueil</a>
                    <a href="startup2-1.0.0/index.php?action=metiers" class="nav-item nav-link">Métiers</a>
                    <a href="index.php?page=evaluation" class="nav-item nav-link">Evaluation</a>
                    <a href="index.php?page=offres" class="nav-item nav-link">Offres</a>
                    <a href="index.php?page=e-learnings" class="nav-item nav-link">E-learnings</a>
                    <a href="index.php?page=blog" class="nav-item nav-link">Blog</a>
                </div>
                <?php if (isset($_SESSION['nom']) && $_SESSION['nom'] !== ''): ?>
                    <a href="index.php?page=profile" class="navbar-profile-btn navbar-user-line">
                        <span><?php echo htmlspecialchars($_SESSION['nom'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php if (!empty($_SESSION['account_verified'])): ?>
                            <span class="navbar-verified-badge" title="Compte vérifié">✔</span>
                        <?php endif; ?>
                    </a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="index.php?page=dashboard-admin" class="btn btn-warning py-2 px-3 me-2">BackOffice</a>
                    <?php endif; ?>
                    <a href="index.php?page=logout" class="btn btn-outline-light py-2 px-3">Se déconnecter</a>
                <?php else: ?>
                <a href="index.php?page=login" class="btn btn-outline-light py-2 px-3 ms-lg-3 me-2">Se connecter</a>
                <a href="index.php?page=creer-compte" class="btn btn-primary py-2 px-3">Créer un compte</a>
                <?php endif; ?>
                <butaton type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i></butaton>

            </div>
        </nav>

        <div id="header-carousel" class="carousel slide carousel-fade mt-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100" src="Views/assets/img/carousel-1.jpg" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h5 class="text-white text-uppercase mb-3 animated slideInDown">CareerLab</h5>
                            <h1 class="display-1 text-white mb-md-4 animated zoomIn">Explorez les metiers et trouvez votre voie</h1>
                            <a href="index.php?page=login" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Se connecter</a>
                            <a href="#site-footer" class="btn btn-outline-light py-md-3 px-md-5 animated slideInRight">Contactez-nous</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="Views/assets/img/carousel-2.jpg" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h5 class="text-white text-uppercase mb-3 animated slideInDown">Orientation intelligente</h5>
                            <h1 class="display-1 text-white mb-md-4 animated zoomIn">Testez votre futur metier avant de le choisir</h1>
                            <a href="index.php?page=login" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Se connecter</a>
                            <a href="#site-footer" class="btn btn-outline-light py-md-3 px-md-5 animated slideInRight">Contactez-nous</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- Navbar & Carousel End -->

    <!-- Hero Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="section-title position-relative pb-3 mb-4">
                        <h5 class="fw-bold text-primary text-uppercase">CareerLab</h5>
                        <h1 class="mb-0">Testez votre futur metier avant de le choisir</h1>
                    </div>
                    <p class="mb-4">Decouvrez differents metiers a travers des simulations interactives et recevez un feedback intelligent grace a l\'IA.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="index.php?page=creer-compte" class="btn btn-primary py-3 px-5">Commencer</a>
                        <a href="#pourquoi-careerlab" class="btn btn-outline-primary py-3 px-5">Decouvrir les metiers</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <img class="img-fluid rounded w-100 shadow wow zoomIn" data-wow-delay="0.2s" src="Views/assets/img/carousel-1.jpg" alt="Simulation metier">
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Pourquoi CareerLab Start -->
    <div id="pourquoi-careerlab" class="container-fluid py-5 bg-light wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 700px;">
                <h5 class="fw-bold text-primary text-uppercase">Pourquoi CareerLab ?</h5>
                <h1 class="mb-0">Une orientation concrete, moderne et utile</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="bg-white rounded p-4 h-100 shadow-sm">
                        <div class="bg-primary rounded d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                            <i class="fa fa-laptop-code text-white"></i>
                        </div>
                        <h5 class="mb-2">Simulations interactives</h5>
                        <p class="mb-0">Mettez-vous en situation reelle pour mieux comprendre chaque metier.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="bg-white rounded p-4 h-100 shadow-sm">
                        <div class="bg-primary rounded d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                            <i class="fa fa-robot text-white"></i>
                        </div>
                        <h5 class="mb-2">Intelligence artificielle</h5>
                        <p class="mb-0">Feedback personnalise pour vous aider a mieux vous orienter.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="bg-white rounded p-4 h-100 shadow-sm">
                        <div class="bg-primary rounded d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                            <i class="fa fa-rocket text-white"></i>
                        </div>
                        <h5 class="mb-2">Metiers du futur</h5>
                        <p class="mb-0">Explorez les secteurs porteurs avec des parcours concrets.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="bg-white rounded p-4 h-100 shadow-sm">
                        <div class="bg-primary rounded d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                            <i class="fa fa-clock text-white"></i>
                        </div>
                        <h5 class="mb-2">Gain de temps</h5>
                        <p class="mb-0">Prenez une meilleure decision de carriere plus rapidement.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pourquoi CareerLab End -->

    <!-- Comment ca marche Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 700px;">
                <h5 class="fw-bold text-primary text-uppercase">Comment ca marche ?</h5>
                <h1 class="mb-0">3 etapes simples</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="bg-light rounded p-4 h-100 text-center">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold mb-3" style="width: 56px; height: 56px;">1</div>
                        <h5 class="mb-2">Choisir un metier</h5>
                        <p class="mb-0">Selectionnez un domaine qui vous attire.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-light rounded p-4 h-100 text-center">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold mb-3" style="width: 56px; height: 56px;">2</div>
                        <h5 class="mb-2">Tester une simulation</h5>
                        <p class="mb-0">Realisez des missions interactives proches de la realite.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-light rounded p-4 h-100 text-center">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold mb-3" style="width: 56px; height: 56px;">3</div>
                        <h5 class="mb-2">Recevoir un feedback personnalise</h5>
                        <p class="mb-0">Obtenez une analyse claire pour valider votre orientation.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Comment ca marche End -->

    <!-- Footer Start -->
    <div id="site-footer" class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-4 col-md-6 footer-about">
                    <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-primary p-4">
                        <a href="index.php?page=accueil" class="navbar-brand">
                            <h1 class="m-0 text-white"><i class="fa fa-user-tie me-2"></i>Startup</h1>
                        </a>
                        <p class="mt-3 mb-4">Decrivez votre probleme et l\'assistant IA CareerLab vous propose une reponse adaptee.</p>
                        <form id="support-ia" action="index.php?page=ai-support" method="POST" class="w-100 text-start" novalidate>
                            <?php if ($aiSupportFlash !== null): ?>
                                <div class="alert alert-<?= htmlspecialchars((string) ($aiSupportFlash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8') ?> py-2">
                                    <?= htmlspecialchars((string) ($aiSupportFlash['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($aiSupportFlash['ai_response'])): ?>
                                <div class="ai-support-response mb-3">
                                    <strong>Reponse IA :</strong>
                                    <p class="mb-0 mt-2"><?= nl2br(htmlspecialchars((string) $aiSupportFlash['ai_response'], ENT_QUOTES, 'UTF-8')) ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="mb-2">
                                <input type="text" name="name" class="form-control border-white p-3" placeholder="Votre nom (optionnel)" value="<?= htmlspecialchars((string) ($aiSupportOld['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                <?php if (($aiSupportErrors['name'] ?? '') !== ''): ?>
                                    <small class="text-white d-block mt-1"><?= htmlspecialchars((string) $aiSupportErrors['name'], ENT_QUOTES, 'UTF-8') ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="email" class="form-control border-white p-3" placeholder="Votre email" value="<?= htmlspecialchars((string) ($aiSupportOld['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                <?php if (($aiSupportErrors['email'] ?? '') !== ''): ?>
                                    <small class="text-white d-block mt-1"><?= htmlspecialchars((string) $aiSupportErrors['email'], ENT_QUOTES, 'UTF-8') ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <textarea name="message" class="form-control border-white p-3" rows="4" placeholder="Expliquez votre probleme"><?= htmlspecialchars((string) ($aiSupportOld['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                                <?php if (($aiSupportErrors['message'] ?? '') !== ''): ?>
                                    <small class="text-white d-block mt-1"><?= htmlspecialchars((string) $aiSupportErrors['message'], ENT_QUOTES, 'UTF-8') ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-dark" type="submit">Envoyer a l\'assistant IA</button>
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
                                <p class="mb-0">Esprit, Ghazela</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-envelope-open text-primary me-2"></i>
                                <p class="mb-0">careerlab@gmail.com</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-telephone text-primary me-2"></i>
                                <p class="mb-0">+216 98 542 321</p>
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
						
						<!--/*** This template is free as long as you keep the footer author\'s credit link/attribution link/backlink. If you\'d like to use the template without the footer author\'s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
						Designed by <a class="text-white border-bottom" href="https://htmlcodex.com">HTML Codex</a></p>
                        <br>Distributed By: <a class="border-bottom" href="https://themewagon.com" target="_blank">ThemeWagon</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="Views/assets/js/wow.min.js"></script>
    <script src="Views/assets/js/easing.min.js"></script>
    <script src="Views/assets/js/waypoints.min.js"></script>
    <script src="Views/assets/js/counterup.min.js"></script>
    <script src="Views/assets/js/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="Views/assets/js/main.js"></script>
</body>

</html>
