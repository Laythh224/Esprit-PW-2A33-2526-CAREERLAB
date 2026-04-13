<!-- Navbar Start -->
<div class="container-fluid position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
        <a href="index.php?action=home" class="navbar-brand p-0">
            <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Carrer Lab</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="index.php?action=home" class="nav-item nav-link <?php echo ($action === 'home') ? 'active' : ''; ?>">Home</a>
                <a href="index.php?action=about" class="nav-item nav-link <?php echo ($action === 'about') ? 'active' : ''; ?>">About</a>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Blog</a>
                    <div class="dropdown-menu m-0">
                        <a href="index.php?action=blog" class="dropdown-item">Blog Grid</a>
                        <a href="index.php?action=detail" class="dropdown-item">Blog Detail</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle <?php echo in_array($action, ['price', 'feature', 'team', 'offres', 'quote']) ? 'active' : ''; ?>" data-bs-toggle="dropdown">Services</a>
                    <div class="dropdown-menu m-0">
                        <a href="index.php?action=price" class="dropdown-item">Utilisateur</a>
                        <a href="index.php?action=feature" class="dropdown-item">Métiers</a>
                        <a href="index.php?action=team" class="dropdown-item">Evaluation</a>
                        <a href="index.php?action=offres" class="dropdown-item">Les offres</a>
                        <a href="index.php?action=quote" class="dropdown-item">E-learning</a>
                    </div>
                </div>
                <a href="index.php?action=contact" class="nav-item nav-link <?php echo ($action === 'contact') ? 'active' : ''; ?>">Contact</a>
            </div>
            <button type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i></button>
            <a href="#" class="btn btn-primary py-2 px-4 ms-3">Connexion</a>
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
