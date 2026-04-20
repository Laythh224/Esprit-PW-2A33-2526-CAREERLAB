<div class="container-fluid position-relative p-0">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>
    <?php require __DIR__ . '/../partials/page-header.php'; ?>
</div>

<div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
            <h5 class="fw-bold text-primary text-uppercase">Contact Us</h5>
            <h1 class="mb-0">Si vous avez une question, ecrivez-nous</h1>
        </div>

        <?php if (!empty($flashMessage)): ?>
            <div class="alert alert-<?= htmlspecialchars((string) $flashType, ENT_QUOTES, 'UTF-8'); ?>" role="alert">
                <?= htmlspecialchars((string) $flashMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <div class="row g-5 mb-5">
            <div class="col-lg-4">
                <div class="d-flex align-items-center wow fadeIn" data-wow-delay="0.1s">
                    <div class="bg-primary d-flex align-items-center justify-content-center rounded" style="width: 60px; height: 60px;">
                        <i class="fa fa-phone-alt text-white"></i>
                    </div>
                    <div class="ps-4">
                        <h5 class="mb-2">Appelez-nous</h5>
                        <h4 class="text-primary mb-0"><?= htmlspecialchars((string) $company['phone'], ENT_QUOTES, 'UTF-8'); ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex align-items-center wow fadeIn" data-wow-delay="0.4s">
                    <div class="bg-primary d-flex align-items-center justify-content-center rounded" style="width: 60px; height: 60px;">
                        <i class="fa fa-envelope-open text-white"></i>
                    </div>
                    <div class="ps-4">
                        <h5 class="mb-2">Email</h5>
                        <h4 class="text-primary mb-0"><?= htmlspecialchars((string) $company['email'], ENT_QUOTES, 'UTF-8'); ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex align-items-center wow fadeIn" data-wow-delay="0.8s">
                    <div class="bg-primary d-flex align-items-center justify-content-center rounded" style="width: 60px; height: 60px;">
                        <i class="fa fa-map-marker-alt text-white"></i>
                    </div>
                    <div class="ps-4">
                        <h5 class="mb-2">Adresse</h5>
                        <h4 class="text-primary mb-0"><?= htmlspecialchars((string) $company['address'], ENT_QUOTES, 'UTF-8'); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-lg-8 wow slideInUp" data-wow-delay="0.3s">
                <form method="post" action="index.php?route=contact/submit">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control border-0 bg-light px-4" name="name" placeholder="Votre nom" style="height: 55px;" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" class="form-control border-0 bg-light px-4" name="email" placeholder="Votre email" style="height: 55px;" required>
                        </div>
                        <div class="col-12">
                            <input type="text" class="form-control border-0 bg-light px-4" name="subject" placeholder="Sujet" style="height: 55px;" required>
                        </div>
                        <div class="col-12">
                            <textarea class="form-control border-0 bg-light px-4 py-3" rows="4" name="message" placeholder="Message" required></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-3" type="submit">Envoyer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
