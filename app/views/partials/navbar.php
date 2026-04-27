<nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
    <a href="index.php?route=front" class="navbar-brand p-0">
        <img src="img/career_lab.png" alt="<?= htmlspecialchars((string) $company['name'], ENT_QUOTES, 'UTF-8'); ?>" style="height: 44px; width: auto; background: #ffffff; padding: 4px 8px; border-radius: 8px;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <?php
        $isServicesActive = ($navServicesClass === 'active' || $navTeamClass === 'active');
        ?>
        <div class="navbar-nav ms-auto py-0">
            <a href="index.php?route=front" class="nav-item nav-link <?= htmlspecialchars((string) $navHomeClass, ENT_QUOTES, 'UTF-8'); ?>">Home</a>
            <a href="index.php?route=about" class="nav-item nav-link <?= htmlspecialchars((string) $navAboutClass, ENT_QUOTES, 'UTF-8'); ?>">About</a>

            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Blog</a>
                <div class="dropdown-menu m-0">
                    <a href="index.php?route=blog-dashboard" class="dropdown-item">Blog Grid</a>
                    <a href="index.php?route=blog-dashboard" class="dropdown-item">Blog Detail</a>
                </div>
            </div>

            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle <?= $isServicesActive ? 'active' : ''; ?>" data-bs-toggle="dropdown">services</a>
                <div class="dropdown-menu m-0">
                    <a href="index.php?route=users" class="dropdown-item">utilisateur</a>
                    <a href="index.php?route=metiers" class="dropdown-item">metiers</a>
                    <a href="index.php?route=team" class="dropdown-item">evaluation</a>
                    <a href="index.php?route=offres" class="dropdown-item">les offres</a>
                    <a href="index.php?route=elearning" class="dropdown-item">E_learning</a>
                    <a href="index.php?route=blog-dashboard" class="dropdown-item">blog</a>
                </div>
            </div>

            <a href="index.php?route=contact" class="nav-item nav-link <?= htmlspecialchars((string) $navContactClass, ENT_QUOTES, 'UTF-8'); ?>">Contact</a>
        </div>
        <button type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i></button>
        <a href="index.php?route=contact" class="btn btn-primary py-2 px-4 ms-3">Download Pro Version</a>
    </div>
</nav>
