<div class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="row gx-5">
            <div class="col-lg-4 col-md-6 footer-about">
                <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-primary p-4">
                    <a href="index.php?route=home" class="navbar-brand">
                        <h1 class="m-0 text-white"><i class="fa fa-user-tie me-2"></i><?= htmlspecialchars((string) $company['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                    </a>
                    <p class="mt-3 mb-4">Plateforme de mise en relation entre talents, formations et opportunites professionnelles.</p>
                </div>
            </div>
            <div class="col-lg-8 col-md-6">
                <div class="row gx-5">
                    <div class="col-lg-6 col-md-12 pt-5 mb-5">
                        <div class="section-title section-title-sm position-relative pb-3 mb-4">
                            <h3 class="text-light mb-0">Contact</h3>
                        </div>
                        <div class="d-flex mb-2"><i class="bi bi-geo-alt text-primary me-2"></i><p class="mb-0"><?= htmlspecialchars((string) $company['address'], ENT_QUOTES, 'UTF-8'); ?></p></div>
                        <div class="d-flex mb-2"><i class="bi bi-envelope-open text-primary me-2"></i><p class="mb-0"><?= htmlspecialchars((string) $company['email'], ENT_QUOTES, 'UTF-8'); ?></p></div>
                        <div class="d-flex mb-2"><i class="bi bi-telephone text-primary me-2"></i><p class="mb-0"><?= htmlspecialchars((string) $company['phone'], ENT_QUOTES, 'UTF-8'); ?></p></div>
                    </div>
                    <div class="col-lg-6 col-md-12 pt-0 pt-lg-5 mb-5">
                        <div class="section-title section-title-sm position-relative pb-3 mb-4">
                            <h3 class="text-light mb-0">Navigation</h3>
                        </div>
                        <div class="link-animated d-flex flex-column justify-content-start">
                            <a class="text-light mb-2" href="index.php?route=home"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                            <a class="text-light mb-2" href="index.php?route=about"><i class="bi bi-arrow-right text-primary me-2"></i>About</a>
                            <a class="text-light mb-2" href="index.php?route=services"><i class="bi bi-arrow-right text-primary me-2"></i>Services</a>
                            <a class="text-light mb-2" href="index.php?route=team"><i class="bi bi-arrow-right text-primary me-2"></i>Team</a>
                            <a class="text-light" href="index.php?route=contact"><i class="bi bi-arrow-right text-primary me-2"></i>Contact</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
