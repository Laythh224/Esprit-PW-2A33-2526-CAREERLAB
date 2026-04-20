<div class="container-fluid position-relative p-0">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>
    <?php require __DIR__ . '/../partials/page-header.php'; ?>
</div>

<div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
            <h5 class="fw-bold text-primary text-uppercase">Our Services</h5>
            <h1 class="mb-0">Des services dynamiques charges depuis le modele</h1>
        </div>
        <div class="row g-5">
            <?= $servicesHtml; ?>
        </div>
    </div>
</div>
