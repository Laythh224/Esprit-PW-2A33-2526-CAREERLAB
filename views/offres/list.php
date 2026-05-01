<!-- Custom styles for Offres page -->
<style>
    html, body {
      min-height: 100%;
      background: linear-gradient(180deg, #d3e5ff 0%, #96bbff 45%, #5d8df5 100%);
      background-attachment: fixed;
      color: #0f172a;
    }
    .form-section { padding: 4rem 0 2rem; }
    .form-card {
      background: rgba(255, 255, 255, 0.94);
      border: 1px solid rgba(15, 23, 42, 0.12);
      border-radius: 2rem;
      box-shadow: 0 28px 75px rgba(15, 23, 42, 0.08);
      padding: 2.5rem;
      margin-bottom: 3rem;
    }
    .form-card h3 { font-size: 1.8rem; margin-bottom: 0.5rem; }
    .form-card .form-label { font-weight: 600; color: #1f2937; }
    .form-card .form-control, .form-card .form-select {
      border-radius: 1rem;
      border: 1px solid rgba(15, 23, 42, 0.16);
      padding: 1rem 1.2rem;
    }
    .form-card .btn { border-radius: 1rem; padding: 1rem 1.5rem; font-weight: 700; transition: transform 0.2s; }
    .form-card .btn-primary { background: linear-gradient(135deg, #5c7cfa, #6d8dff); border: none; }
    .form-card .btn:hover { transform: translateY(-2px); }
    .hidden { display: none !important; }
    .alert-box {
      border-radius: 1rem;
      padding: 1rem 1.4rem;
      font-weight: 600;
      font-size: 0.95rem;
      animation: fadeIn 0.4s ease;
    }
    .alert-success { background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.3); color: #15803d; }
    .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); color: #b91c1c; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

    .offres-hero { padding: 3rem 0 1.5rem; text-align: center; }
    .offres-hero h1 { font-size: 2.6rem; font-weight: 800; color: #fff; text-shadow: 0 2px 12px rgba(0,0,0,.15); }
    .offres-hero p  { color: rgba(255,255,255,.85); font-size: 1.1rem; }
    .search-bar { background: rgba(255,255,255,.92); border-radius: 2rem; box-shadow: 0 8px 32px rgba(15,23,42,.1); padding: 1.5rem 2rem; margin-bottom: 2rem; }
    .search-bar .form-control { border-radius: 1rem; border: 1.5px solid rgba(15,23,42,.14); padding: .85rem 1.2rem; }
    .search-bar .btn { border-radius: 1rem; padding: .85rem 1.8rem; font-weight: 700; }
    .filter-tabs { display: flex; gap: .6rem; flex-wrap: wrap; justify-content: center; margin-bottom: 2rem; }
    .filter-tabs .btn { border-radius: 2rem; font-weight: 600; padding: .5rem 1.4rem; transition: all .2s; }
    .offer-card {
      background: rgba(255,255,255,.94);
      border: 1px solid rgba(15, 23, 42,.1);
      border-radius: 1.5rem;
      box-shadow: 0 8px 32px rgba(15,23,42,.07);
      padding: 1.6rem;
      transition: transform .25s, box-shadow .25s;
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .offer-card:hover { transform: translateY(-5px); box-shadow: 0 20px 50px rgba(15,23,42,.13); }
    .offer-card .badge-type { font-size: .75rem; font-weight: 700; border-radius: .6rem; padding: .35rem .8rem; }
    .badge-travail { background: #4f46e5; color: #fff; }
    .offer-card h5 { font-size: 1.1rem; font-weight: 700; margin: .7rem 0 .4rem; color: #1e293b; }
    .offer-card .company { font-weight: 600; color: #4f46e5; font-size: .95rem; }
    .offer-card .meta    { font-size: .83rem; color: #64748b; }
    .offer-card .meta i  { margin-right: .3rem; }
    .offer-card .description { font-size: .88rem; color: #475569; flex-grow: 1; margin: .6rem 0; line-height: 1.6; }
    .section-title { font-size: 1.3rem; font-weight: 800; color: #fff; margin: 2rem 0 1rem; padding-left: .5rem; border-left: 4px solid rgba(255,255,255,.6); }
    .edit-mode-btn { border-radius: 1rem; font-weight: 700; padding: .6rem 1.5rem; transition: background 0.3s; }
    .btn-manage-toggle { background: #f59e0b; color: #fff; border: none; }
    .btn-manage-toggle:hover { background: #d97706; color: #fff; }
    .btn-manage-toggle.active { background: #ef4444; }
    .edit-controls { display: none; gap: 0.5rem; margin-top: 1rem; border-top: 1px solid rgba(0,0,0,0.05); }
    .manage-active .edit-controls { display: flex; }
    .btn-edit-item { background: #3b82f6; color: #fff; border-radius: .6rem; border: none; padding: .4rem .8rem; font-size: .8rem; }
    .btn-delete-item { background: #ef4444; color: #fff; border-radius: .6rem; border: none; padding: .4rem .8rem; font-size: .8rem; }
    .btn-view-item { 
        background: linear-gradient(135deg, #6366f1, #3b82f6); 
        color: #fff; 
        border-radius: .8rem; 
        border: none; 
        padding: .6rem 1rem; 
        font-size: .9rem; 
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s;
        margin-top: 0.5rem;
    }
    .btn-view-item:hover {
        background: linear-gradient(135deg, #4f46e5, #2563eb);
        color: #fff;
        transform: scale(1.02);
    }
    .badge-exp { background: #f59e0b; color: #fff; }
    /* Toggle Choices Styles */
    .choice-tabs { display: flex; justify-content: center; gap: 1rem; margin-bottom: 2rem; }
    .choice-btn {
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid transparent;
        padding: 0.8rem 2rem;
        border-radius: 1.5rem;
        font-weight: 700;
        color: #4f46e5;
        cursor: pointer;
        transition: all 0.3s;
    }
    .choice-btn.active {
        background: #4f46e5;
        color: #fff;
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
    }
</style>

<!-- Form Section (Publier une offre) -->
<div class="container-fluid form-section">
    <div class="container">
        <?php if (!empty($entreprise_nom)): ?>
            <div class="welcome-banner mb-4">
                <h2 class="text-white fw-bold">Bienvenue, <span class="text-primary-gradient"><?= htmlspecialchars($entreprise_nom) ?></span> 👋</h2>
                <style>
                    .text-primary-gradient {
                        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        display: inline-block;
                    }
                    .welcome-banner {
                        background: rgba(255, 255, 255, 0.95);
                        padding: 2rem;
                        border-radius: 2rem;
                        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                        text-align: center;
                        animation: slideDown 0.6s ease-out;
                    }
                    .welcome-banner h2 {
                        margin: 0;
                        color: #1e293b !important;
                    }
                    @keyframes slideDown {
                        from { transform: translateY(-30px); opacity: 0; }
                        to { transform: translateY(0); opacity: 1; }
                    }
                </style>
            </div>
        <?php endif; ?>
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <!-- Choice Tabs -->
                <div class="choice-tabs">
                    <div class="choice-btn active" data-target="form-job" id="btn-choice-job">📢 Opportunité de travail</div>
                    <div class="choice-btn" data-target="form-exp" id="btn-choice-exp">🎓 Expérience</div>
                </div>

                <div class="form-card">
                    <div class="mb-4 text-center">
                        <h3>📢 Publier une opportunité de travail</h3>
                        <p class="form-note">Remplissez les champs pour une offre d'emploi propre et professionnelle.</p>
                    </div>

                    <!-- Notifications -->
                    <div id="notif-publication" class="hidden alert-box mb-3"></div>

                    <!-- Formulaire Opportunité de Travail -->
                    <form id="form-add-travail" action="index.php?action=publish" method="POST">
                        <input type="hidden" name="type_offre" value="travail">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Titre</label><input type="text" class="form-control" name="titre" placeholder="Titre de l'offre" required></div>
                            <div class="col-md-6"><label class="form-label">Entreprise</label><input type="text" class="form-control" name="entreprise" placeholder="Entreprise" required></div>
                            <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3" placeholder="Description" required></textarea></div>
                            <div class="col-md-6"><label class="form-label">Localisation</label><input type="text" class="form-control" name="localisation" placeholder="Ville" required></div>
                            <div class="col-md-6"><label class="form-label">Type de contrat</label><input type="text" class="form-control" name="type_contrat" placeholder="CDI, CDD..."></div>
                            <div class="col-md-6">
                                <label class="form-label">ID Expérience</label>
                                <select class="form-select" name="experience_id" required>
                                    <option value="">Sélectionnez l'ID...</option>
                                    <?php foreach ($experiences as $exp): ?>
                                        <option value="<?= $exp['id'] ?>"><?= htmlspecialchars($exp['prenom'] ?? '') ?> - <?= htmlspecialchars($exp['niveau']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6"><label class="form-label">Domaine</label><input type="text" class="form-control" name="domaine" placeholder="Domaine"></div>
                            <div class="col-md-6"><label class="form-label">Expiration</label><input type="date" class="form-control" name="date_expiration"></div>
                        </div>
                        <div class="mt-4"><button type="submit" class="btn btn-primary w-100">Publier l'Offre</button></div>
                    </form>

                    <!-- Formulaire Expérience (Caché par défaut) -->
                    <form id="form-add-experience" action="index.php?action=publish" method="POST" class="hidden">
                        <div class="mb-4 text-center">
                            <h4>🎓 Ajouter un niveau d'expérience</h4>
                        </div>
                        <input type="hidden" name="type_offre" value="experience">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control" name="nom" placeholder="Votre nom" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Prénom</label>
                                <input type="text" class="form-control" name="prenom" placeholder="Votre prénom" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Niveau d'expérience</label>
                                <select class="form-select" name="niveau" required>
                                    <option value="">Choisissez un niveau...</option>
                                    <option value="Junior">Junior (0 à 1 an d'expérience)</option>
                                    <option value="Intermédiaire">Intermédiaire (1 à 3 ans d'expérience)</option>
                                    <option value="Confirmé">Confirmé (3 à 5 ans d'expérience)</option>
                                    <option value="Senior">Senior (Plus de 5 ans d'expérience)</option>
                                    <option value="Expert">Expert (Plus de 8 ans avec expertise avancée)</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="2" placeholder="Brève description du niveau"></textarea>
                            </div>
                        </div>
                        <div class="mt-4"><button type="submit" class="btn btn-primary w-100">Ajouter le Niveau</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hero Selection -->
<div class="offres-hero">
  <div class="container">
    <h1>🔍 Trouvez votre opportunité</h1>
    <p><?= $totalTravail ?> offre<?= $totalTravail > 1 ? 's' : '' ?> disponible<?= $totalTravail > 1 ? 's' : '' ?></p>
  </div>
</div>

<!-- Listing -->
<div class="container pb-5">
  <div id="notification" class="hidden alert-box mb-4"></div>

  <!-- Search -->
  <form method="GET" action="index.php" class="search-bar">
    <input type="hidden" name="action" value="offres">
    <div class="row g-2 align-items-center">
      <div class="col-md-7"><input type="text" name="q" class="form-control" placeholder="🔎 Rechercher..." value="<?= htmlspecialchars($search) ?>"></div>
      <div class="col-md-3">
        <select name="sort" class="form-select" style="border-radius: 1rem; border: 1.5px solid rgba(15,23,42,.14); padding: .85rem 1.2rem;" onchange="this.form.submit()">
          <option value="newest" <?= ($sort ?? '') === 'newest' ? 'selected' : '' ?>>📅 Plus récent</option>
          <option value="exp_title" <?= ($sort ?? '') === 'exp_title' ? 'selected' : '' ?>>🎓 Expérience + Titre (A-Z)</option>
          <option value="exp_asc" <?= ($sort ?? '') === 'exp_asc' ? 'selected' : '' ?>>🎓 Niveau (Junior -> Expert)</option>
          <option value="title_asc" <?= ($sort ?? '') === 'title_asc' ? 'selected' : '' ?>>🔤 Titre (A-Z)</option>
          <option value="oldest" <?= ($sort ?? '') === 'oldest' ? 'selected' : '' ?>>📅 Plus ancien</option>
        </select>
      </div>
      <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Rechercher</button></div>
    </div>
  </form>

  <!-- Toggle Edit -->
  <div class="text-center mb-4">
    <button id="toggleEditMode" class="btn btn-manage-toggle edit-mode-btn shadow-sm">
      <i class="fas fa-edit me-2"></i>Gérer mes publications
    </button>
  </div>

  <!-- Emplois -->
  <div class="section-title">💼 Opportunités d'Emploi</div>
  <div class="row g-4 mb-4">
    <?php foreach ($travaux as $t): ?>
    <div class="col-md-6 col-lg-4">
      <div class="offer-card">
        <div class="d-flex justify-content-between align-items-center"><span class="badge-type badge-travail">Emploi</span></div>
        <h5><?= htmlspecialchars($t['titre']) ?></h5>
        <div class="company"><i class="fas fa-building me-1"></i><?= htmlspecialchars($t['entreprise'] ?? '') ?></div>
        <div class="meta"><?= htmlspecialchars($t['localisation'] ?? '') ?> | <?= htmlspecialchars($t['domaine'] ?? '') ?></div>
        <div class="description"><?= htmlspecialchars(substr($t['description'] ?? '', 0, 100)) ?>...</div>
        <a href="index.php?action=viewOffre&id=<?= $t['id'] ?>&type=travail" class="btn-view-item">
          <i class="fas fa-eye"></i> Afficher
        </a>
        <div class="edit-controls">
          <button class="btn-edit-item w-100" data-bs-toggle="modal" data-bs-target="#editModal" data-type="travail" data-id="<?= $t['id'] ?>" 
              data-titre="<?= htmlspecialchars($t['titre']) ?>" 
              data-entreprise="<?= htmlspecialchars($t['entreprise'] ?? '') ?>"
              data-description="<?= htmlspecialchars($t['description'] ?? '') ?>" 
              data-localisation="<?= htmlspecialchars($t['localisation'] ?? '') ?>"
              data-type_contrat="<?= htmlspecialchars($t['type_contrat'] ?? '') ?>"
              data-experience_id="<?= htmlspecialchars($t['experience_id'] ?? '') ?>"
              data-domaine="<?= htmlspecialchars($t['domaine'] ?? '') ?>"
              data-date_expiration="<?= htmlspecialchars($t['date_expiration'] ?? '') ?>">
            Modifier
          </button>
          <button class="btn-delete-item w-100" onclick="confirmDelete(<?= $t['id'] ?>, 'travail')">Supprimer</button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($travaux)): ?>
      <div class="col-12 text-center py-5">
        <p class="text-white-50 fs-5">Aucune offre correspondant à votre recherche.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- Expériences -->
  <div class="section-title">🎓 Niveaux d'Expérience</div>
  <div class="row g-4 mb-4">
    <?php foreach ($experiences as $exp): ?>
    <div class="col-md-6 col-lg-4">
      <div class="offer-card">
        <div class="d-flex justify-content-between align-items-center"><span class="badge-type badge-exp">Expérience</span></div>
        <h5><?= htmlspecialchars($exp['niveau']) ?></h5>
        <div class="description"><?= htmlspecialchars($exp['description'] ?? 'Aucune description.') ?></div>
        
        <a href="index.php?action=viewOffre&id=<?= $exp['id'] ?>&type=experience" class="btn-view-item">
          <i class="fas fa-eye"></i> Afficher
        </a>
        
        <div class="edit-controls">
          <button class="btn-edit-item w-100" data-bs-toggle="modal" data-bs-target="#editExpModal" 
              data-id="<?= $exp['id'] ?>" 
              data-nom="<?= htmlspecialchars($exp['nom'] ?? '') ?>" 
              data-prenom="<?= htmlspecialchars($exp['prenom'] ?? '') ?>" 
              data-niveau="<?= htmlspecialchars($exp['niveau']) ?>" 
              data-description="<?= htmlspecialchars($exp['description'] ?? '') ?>">
            Modifier
          </button>
          <button class="btn-delete-item w-100" onclick="confirmDelete(<?= $exp['id'] ?>, 'experience')">Supprimer</button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($experiences)): ?>
      <div class="col-12 text-center py-4">
        <p class="text-white-50">Aucun niveau d'expérience défini pour le moment.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Modal de Modification -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius: 1.5rem; overflow: hidden; border: none; box-shadow: 0 15px 50px rgba(0,0,0,0.2);">
      <div class="modal-header bg-primary text-white border-0">
        <h5 class="modal-title fw-bold" id="editModalLabel"><i class="fas fa-edit me-2"></i>Modifier l'offre</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="form-edit-offre" action="index.php?action=update" method="POST">
        <div class="modal-body p-4">
          <input type="hidden" name="id" id="edit-id">
          <input type="hidden" name="type_offre" value="travail">
          
          <div id="edit-fields-travail">
            <div class="row g-3">
              <div class="col-md-6"><label class="form-label">Titre</label><input type="text" class="form-control" name="titre" id="edit-titre" required></div>
              <div class="col-md-6"><label class="form-label">Entreprise</label><input type="text" class="form-control" name="entreprise" id="edit-entreprise" required></div>
              <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" id="edit-description" rows="3" required></textarea></div>
              <div class="col-md-6"><label class="form-label">Localisation</label><input type="text" class="form-control" name="localisation" id="edit-localisation" required></div>
              <div class="col-md-6"><label class="form-label">Type de contrat</label><input type="text" class="form-control" name="type_contrat" id="edit-type_contrat"></div>
              <div class="col-md-6">
                <label class="form-label">ID Expérience</label>
                <select class="form-select" name="experience_id" id="edit-experience_id" required>
                    <?php foreach ($experiences as $exp): ?>
                        <option value="<?= $exp['id'] ?>"><?= htmlspecialchars($exp['prenom'] ?? '') ?> - <?= htmlspecialchars($exp['niveau']) ?></option>
                    <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6"><label class="form-label">Domaine</label><input type="text" class="form-control" name="domaine" id="edit-domaine"></div>
              <div class="col-md-6"><label class="form-label">Expiration</label><input type="date" class="form-control" name="date_expiration" id="edit-date_expiration"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 p-4 pt-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 1rem; font-weight: 600;">Annuler</button>
          <button type="submit" class="btn btn-primary" style="border-radius: 1rem; font-weight: 700; padding: .6rem 2rem;">Enregistrer les modifications</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Modification EXPÉRIENCE -->
<div class="modal fade" id="editExpModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius: 1.5rem; overflow: hidden; border: none; box-shadow: 0 15px 50px rgba(0,0,0,0.2);">
      <div class="modal-header bg-warning text-white border-0">
        <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>Modifier l'expérience</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="index.php?action=update" method="POST">
        <div class="modal-body p-4">
          <input type="hidden" name="id" id="edit-exp-id">
          <input type="hidden" name="type_offre" value="experience">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Nom</label>
              <input type="text" class="form-control" name="nom" id="edit-exp-nom" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Prénom</label>
              <input type="text" class="form-control" name="prenom" id="edit-exp-prenom" required>
            </div>
          </div>
          <div class="mb-3">
             <label class="form-label">Niveau d'expérience</label>
             <select class="form-select" name="niveau" id="edit-exp-niveau" required>
                <option value="Junior">Junior (0 à 1 an d'expérience)</option>
                <option value="Intermédiaire">Intermédiaire (1 à 3 ans d'expérience)</option>
                <option value="Confirmé">Confirmé (3 à 5 ans d'expérience)</option>
                <option value="Senior">Senior (Plus de 5 ans d'expérience)</option>
                <option value="Expert">Expert (Plus de 8 ans avec expertise avancée)</option>
             </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" id="edit-exp-description" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer border-0 p-4 pt-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-warning text-white" style="border-radius: 1rem; font-weight: 700;">Mettre à jour</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function confirmDelete(id, type) {
      if (confirm("Supprimer cette offre ?")) {
          window.location.href = "index.php?action=delete&id=" + id + "&type=" + type;
      }
  }

  document.getElementById('toggleEditMode').addEventListener('click', function() {
      document.body.classList.toggle('manage-active');
  });

  // Gestion du Modal de Modification
  const editModal = document.getElementById('editModal');
  if (editModal) {
      editModal.addEventListener('show.bs.modal', function (event) {
          const button = event.relatedTarget;
          document.getElementById('edit-id').value = button.getAttribute('data-id');
          document.getElementById('edit-titre').value = button.getAttribute('data-titre') || '';
          document.getElementById('edit-entreprise').value = button.getAttribute('data-entreprise') || '';
          document.getElementById('edit-description').value = button.getAttribute('data-description') || '';
          document.getElementById('edit-localisation').value = button.getAttribute('data-localisation') || '';
          document.getElementById('edit-type_contrat').value = button.getAttribute('data-type_contrat') || '';
          document.getElementById('edit-experience_id').value = button.getAttribute('data-experience_id') || '';
          document.getElementById('edit-domaine').value = button.getAttribute('data-domaine') || '';
          document.getElementById('edit-date_expiration').value = button.getAttribute('data-date_expiration') || '';
      });
  }

  // Pre-fill Exp Modal
  const editExpModal = document.getElementById('editExpModal');
  if (editExpModal) {
      editExpModal.addEventListener('show.bs.modal', function (event) {
          const button = event.relatedTarget;
          document.getElementById('edit-exp-id').value = button.getAttribute('data-id');
          document.getElementById('edit-exp-nom').value = button.getAttribute('data-nom') || '';
          document.getElementById('edit-exp-prenom').value = button.getAttribute('data-prenom') || '';
          document.getElementById('edit-exp-niveau').value = button.getAttribute('data-niveau') || '';
          document.getElementById('edit-exp-description').value = button.getAttribute('data-description') || '';
      });
  }

  // --- CONTROLE DE SAISIE (Validation) ---
  function validateTravailForm(form) {
      const title = form.querySelector('[name="titre"]').value.trim();
      const company = form.querySelector('[name="entreprise"]').value.trim();
      const desc = form.querySelector('[name="description"]').value.trim();
      const loc = form.querySelector('[name="localisation"]').value.trim();
      const expDate = form.querySelector('[name="date_expiration"]').value;

      if (!title || !company || !desc || !loc) {
          alert("Veuillez remplir tous les champs obligatoires.");
          return false;
      }

      if (expDate) {
          const today = new Date().toISOString().split('T')[0];
          if (expDate < today) {
              alert("La date d'expiration ne peut pas être dans le passé.");
              return false;
          }
      }
      return true;
  }

  function validateExperienceForm(form) {
      const level = form.querySelector('[name="niveau"]').value;
      const desc = form.querySelector('[name="description"]').value.trim();
      const nom = form.querySelector('[name="nom"]').value.trim();
      const prenom = form.querySelector('[name="prenom"]').value.trim();

      if (!level || !desc || !nom || !prenom) {
          alert("Veuillez remplir tous les champs (Nom, Prénom, Niveau, Description).");
          return false;
      }
      return true;
  }

  // Event Listeners for Validation
  document.getElementById('form-add-travail')?.addEventListener('submit', function(e) {
      if (!validateTravailForm(this)) e.preventDefault();
  });

  document.getElementById('form-edit-offre')?.addEventListener('submit', function(e) {
      if (!validateTravailForm(this)) e.preventDefault();
  });

  document.getElementById('form-add-experience')?.addEventListener('submit', function(e) {
      if (!validateExperienceForm(this)) e.preventDefault();
  });

  document.querySelector('#editExpModal form')?.addEventListener('submit', function(e) {
      if (!validateExperienceForm(this)) e.preventDefault();
  });

  // Auto-ouverture du modal si edit_id est présent dans l'URL
  document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const editId = urlParams.get('edit_id');
      const editType = urlParams.get('edit_type');
      
      if (editId && editType === 'travail') {
          setTimeout(() => {
              const btn = document.querySelector(`.btn-edit-item[data-id="${editId}"][data-type="travail"]`);
              if (btn) {
                  document.body.classList.add('manage-active');
                  const modal = new bootstrap.Modal(editModal);
                  modal.show();
              }
          }, 500);
      }
  });

  // Toggle Forms Logic
  const btnJob = document.getElementById('btn-choice-job');
  const btnExp = document.getElementById('btn-choice-exp');
  const formJob = document.getElementById('form-add-travail');
  const formExp = document.getElementById('form-add-experience');

  btnJob.addEventListener('click', () => {
      btnJob.classList.add('active');
      btnExp.classList.remove('active');
      formJob.classList.remove('hidden');
      formExp.classList.add('hidden');
  });

  btnExp.addEventListener('click', () => {
      btnExp.classList.add('active');
      btnJob.classList.remove('active');
      formExp.classList.remove('hidden');
      formJob.classList.add('hidden');
  });
</script>
