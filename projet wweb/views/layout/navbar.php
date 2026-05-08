<!-- Navbar Start -->
<div class="container-fluid position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
        <a href="index.php?page=accueil" class="navbar-brand p-0">
            <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Carrer Lab</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="index.php?page=accueil" class="nav-item nav-link <?php echo ($action === 'home') ? 'active' : ''; ?>">Home</a>
                
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                    <div class="dropdown-menu m-0">
                        <a href="index.php?page=offres&action=price" class="dropdown-item">Utilisateur</a>
                        <a href="index.php?page=offres&action=feature" class="dropdown-item">Métiers</a>
                        <a href="index.php?page=offres&action=team" class="dropdown-item">Evaluation</a>
                        <a href="index.php?page=offres&action=offres" class="dropdown-item">Les offres</a>
                        <a href="index.php?page=offres&action=quote" class="dropdown-item">E_learning</a>
                    </div>
                </div>
            </div>
            <?php if (!empty($_SESSION['is_logged_in'])): ?>
                <a href="index.php?page=logout" class="nav-item nav-link">Déconnexion</a>
            <?php else: ?>
                <a href="index.php?page=login" class="nav-item nav-link">Connexion</a>
            <?php endif; ?>
            <button type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i></button>

        </div>
    </nav>
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
                    <input type="text" class="form-control bg-transparent border-primary p-3" placeholder="Tapez votre recherche">
                    <button class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Full Screen Search End -->
