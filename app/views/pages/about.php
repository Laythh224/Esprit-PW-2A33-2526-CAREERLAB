<div class="container-fluid position-relative p-0">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>
    <?php require __DIR__ . '/../partials/page-header.php'; ?>
</div>

<div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="section-title position-relative pb-3 mb-5">
                    <h5 class="fw-bold text-primary text-uppercase">About Us</h5>
                    <h1 class="mb-0"><?= htmlspecialchars((string) $about['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
                </div>
                <p class="mb-4"><?= htmlspecialchars((string) $about['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                <div class="row g-0 mb-3">
                    <div class="col-sm-6 wow zoomIn" data-wow-delay="0.2s">
                        <h5 class="mb-3"><i class="fa fa-check text-primary me-3"></i><?= htmlspecialchars((string) $about['feature1'], ENT_QUOTES, 'UTF-8'); ?></h5>
                        <h5 class="mb-3"><i class="fa fa-check text-primary me-3"></i><?= htmlspecialchars((string) $about['feature2'], ENT_QUOTES, 'UTF-8'); ?></h5>
                    </div>
                    <div class="col-sm-6 wow zoomIn" data-wow-delay="0.4s">
                        <h5 class="mb-3"><i class="fa fa-check text-primary me-3"></i><?= htmlspecialchars((string) $about['feature3'], ENT_QUOTES, 'UTF-8'); ?></h5>
                        <h5 class="mb-3"><i class="fa fa-check text-primary me-3"></i><?= htmlspecialchars((string) $about['feature4'], ENT_QUOTES, 'UTF-8'); ?></h5>
                    </div>
                </div>
                <a href="index.php?route=contact" class="btn btn-primary py-3 px-5 mt-3">Nous contacter</a>
            </div>
            <div class="col-lg-5" style="min-height: 500px;">
                <div class="position-relative h-100">
                    <img class="position-absolute w-100 h-100 rounded wow zoomIn" data-wow-delay="0.9s" src="img/about.jpg" style="object-fit: cover;" alt="About Career Lab">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
            <h5 class="fw-bold text-primary text-uppercase">Team Members</h5>
            <h1 class="mb-0">Des experts dedies a votre reussite</h1>
        </div>
        <div class="row g-5">
            <?= $teamHtml; ?>
        </div>
    </div>
</div>
