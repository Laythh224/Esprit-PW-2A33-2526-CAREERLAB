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
    .form-card .btn-success { background: linear-gradient(135deg, #22c55e, #16a34a); border: none; }
    .form-card .btn:hover { transform: translateY(-2px); }
    .option-card {
      background: rgba(59, 130, 246, 0.08);
      border: 1px solid rgba(59, 130, 246, 0.14);
      border-radius: 1.25rem;
      padding: 1rem 1.2rem;
      color: #1e3a8a;
    }
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
    .badge-stage   { background: #0ea5e9; color: #fff; }
    .badge-statut-dispo { background: #d1fae5; color: #065f46; font-size:.72rem; border-radius:.5rem; padding:.2rem .7rem; }
    .badge-statut-ferme { background: #fee2e2; color: #991b1b; font-size:.72rem; border-radius:.5rem; padding:.2rem .7rem; }
    .offer-card h5 { font-size: 1.1rem; font-weight: 700; margin: .7rem 0 .4rem; color: #1e293b; }
    .offer-card .company { font-weight: 600; color: #4f46e5; font-size: .95rem; }
    .offer-card .meta    { font-size: .83rem; color: #64748b; }
    .offer-card .meta i  { margin-right: .3rem; }
    .offer-card .description { font-size: .88rem; color: #475569; flex-grow: 1; margin: .6rem 0; line-height: 1.6; }
    .empty-state { text-align: center; padding: 4rem 1rem; color: rgba(255,255,255,.75); }
    .empty-state i { font-size: 3rem; margin-bottom: 1rem; }
    .section-title { font-size: 1.3rem; font-weight: 800; color: #fff; margin: 2rem 0 1rem; padding-left: .5rem; border-left: 4px solid rgba(255,255,255,.6); }
    .edit-mode-btn { border-radius: 1rem; font-weight: 700; padding: .6rem 1.5rem; transition: background 0.3s; }
    .btn-manage-toggle { background: #f59e0b; color: #fff; border: none; }
    .btn-manage-toggle:hover { background: #d97706; color: #fff; }
    .btn-manage-toggle.active { background: #ef4444; }
    .edit-controls { display: none; gap: 0.5rem; margin-top: 1rem; border-top: 1px solid rgba(0,0,0,0.05); }
    .manage-active .edit-controls { display: flex; }
    .btn-edit-item { background: #3b82f6; color: #fff; border-radius: .6rem; border: none; padding: .4rem .8rem; font-size: .8rem; }
    .btn-delete-item { background: #ef4444; color: #fff; border-radius: .6rem; border: none; padding: .4rem .8rem; font-size: .8rem; }
</style>

<!-- Form Section (Publier une offre) -->
<div class="container-fluid form-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <div class="form-card">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h3>📢 Publier une offre</h3>
                            <p class="form-note">Choisissez le type d'offre et remplissez les champs pour une annonce propre et professionnelle.</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Type d'offre</label>
                        <select id="type" class="form-select" onchange="toggleForm()">
                            <option value="">-- Choisir --</option>
                            <option value="travail">Opportunité de travail</option>
                            <option value="stage">Stage</option>
                        </select>
                    </div>

                    <!-- Notifications -->
                    <div id="notif-publication" class="hidden alert-box mb-3"></div>

                    <div id="travailForm" class="hidden">
                        <form action="index.php?action=publish" method="POST">
                            <input type="hidden" name="type_offre" value="travail">
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Titre</label><input type="text" class="form-control" name="titre" placeholder="Titre de l'offre"></div>
                                <div class="col-md-6"><label class="form-label">Entreprise</label><input type="text" class="form-control" name="entreprise" placeholder="Entreprise"></div>
                                <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3" placeholder="Description"></textarea></div>
                                <div class="col-md-6"><label class="form-label">Localisation</label><input type="text" class="form-control" name="localisation" placeholder="Ville"></div>
                                <div class="col-md-6"><label class="form-label">Type de contrat</label><input type="text" class="form-control" name="type_contrat" placeholder="CDI, CDD..."></div>
                                <div class="col-md-6"><label class="form-label">Expérience</label><input type="text" class="form-control" name="niveau_experience" placeholder="Junior, Senior..."></div>
                                <div class="col-md-6"><label class="form-label">Domaine</label><input type="text" class="form-control" name="domaine" placeholder="Domaine"></div>
                                <div class="col-md-6"><label class="form-label">Expiration</label><input type="date" class="form-control" name="date_expiration"></div>
                            </div>
                            <div class="mt-4"><button type="submit" class="btn btn-primary w-100">Publier Offre Travail</button></div>
                        </form>
                    </div>

                    <div id="stageForm" class="hidden">
                        <form action="index.php?action=publish" method="POST">
                            <input type="hidden" name="type_offre" value="stage">
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Société</label><input type="text" class="form-control" name="nom_societe" placeholder="Nom"></div>
                                <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email_contact" placeholder="Email"></div>
                                <div class="col-md-6"><label class="form-label">Téléphone</label><input type="tel" class="form-control" name="telephone" placeholder="Tel"></div>
                                <div class="col-md-6"><label class="form-label">Adresse</label><input type="text" class="form-control" name="adresse" placeholder="Adresse"></div>
                                <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3" placeholder="Description"></textarea></div>
                                <div class="col-md-4"><label class="form-label">Ville</label><input type="text" class="form-control" name="ville" placeholder="Ville"></div>
                                <div class="col-md-4"><label class="form-label">Durée</label><input type="text" class="form-control" name="duree" placeholder="3 mois..."></div>
                                <div class="col-md-4"><label class="form-label">Niveau d'étude</label><input type="text" class="form-control" name="niveau_etude" placeholder="Licence..."></div>
                                <div class="col-md-4"><label class="form-label">Début</label><input type="date" class="form-control" name="date_debut"></div>
                                <div class="col-md-4"><label class="form-label">Fin</label><input type="date" class="form-control" name="date_fin"></div>
                                <div class="col-md-4">
                                    <label class="form-label">Statut</label>
                                    <select class="form-select" name="statut"><option value="disponible">Disponible</option><option value="rempli">Fermé</option></select>
                                </div>
                            </div>
                            <div class="mt-4"><button type="submit" class="btn btn-success w-100">Publier Stage</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hero Selection -->
<div class="offres-hero">
  <div class="container">
    <h1>🔍 Trouvez votre opportunité</h1>
    <p><?= $totalTravail + $totalStage ?> offre<?= ($totalTravail + $totalStage) > 1 ? 's' : '' ?> disponible<?= ($totalTravail + $totalStage) > 1 ? 's' : '' ?></p>
  </div>
</div>

<!-- Listing -->
<div class="container pb-5">
  <div id="notification" class="hidden alert-box mb-4"></div>

  <!-- Search -->
  <form method="GET" action="index.php" class="search-bar">
    <input type="hidden" name="action" value="offres">
    <div class="row g-2 align-items-center">
      <div class="col-md-10"><input type="text" name="q" class="form-control" placeholder="🔎 Rechercher..." value="<?= htmlspecialchars($search) ?>"></div>
      <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Rechercher</button></div>
    </div>
  </form>

  <!-- Toggle Edit -->
  <div class="text-center mb-4">
    <button id="toggleEditMode" class="btn btn-manage-toggle edit-mode-btn shadow-sm">
      <i class="fas fa-edit me-2"></i>Gérer mes publications
    </button>
  </div>

  <!-- Filters -->
  <div class="filter-tabs">
    <a href="index.php?action=offres<?= $search ? '&q='.urlencode($search) : '' ?>" class="btn <?= $filtre === 'all' ? 'btn-light' : 'btn-outline-light' ?>">Tout</a>
    <a href="index.php?action=offres&filtre=travail<?= $search ? '&q='.urlencode($search) : '' ?>" class="btn <?= $filtre === 'travail' ? 'btn-light' : 'btn-outline-light' ?>">Emplois</a>
    <a href="index.php?action=offres&filtre=stage<?= $search ? '&q='.urlencode($search) : '' ?>" class="btn <?= $filtre === 'stage' ? 'btn-light' : 'btn-outline-light' ?>">Stages</a>
  </div>

  <!-- Emplois -->
  <?php if ($filtre !== 'stage'): ?>
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
          <div class="edit-controls">
            <button class="btn-edit-item w-100" data-bs-toggle="modal" data-bs-target="#editModal" data-type="travail" data-id="<?= $t['id'] ?>" 
                data-titre="<?= htmlspecialchars($t['titre']) ?>" data-entreprise="<?= htmlspecialchars($t['entreprise'] ?? '') ?>"
                data-description="<?= htmlspecialchars($t['description'] ?? '') ?>" data-localisation="<?= htmlspecialchars($t['localisation'] ?? '') ?>">
              Modifier
            </button>
            <button class="btn-delete-item w-100" onclick="confirmDelete(<?= $t['id'] ?>, 'travail')">Supprimer</button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- Stages -->
  <?php if ($filtre !== 'travail'): ?>
    <div class="section-title">🎓 Stages</div>
    <div class="row g-4">
      <?php foreach ($stages as $s): ?>
      <div class="col-md-6 col-lg-4">
        <div class="offer-card">
          <div class="d-flex justify-content-between align-items-center"><span class="badge-type badge-stage">Stage</span></div>
          <h5><?= htmlspecialchars($s['nom_societe']) ?></h5>
          <div class="meta"><?= htmlspecialchars($s['ville'] ?? '') ?> | <?= htmlspecialchars($s['duree'] ?? '') ?></div>
          <div class="description"><?= htmlspecialchars(substr($s['description'] ?? '', 0, 100)) ?>...</div>
          <div class="edit-controls">
            <button class="btn-edit-item w-100" data-bs-toggle="modal" data-bs-target="#editModal" data-type="stage" data-id="<?= $s['id'] ?>"
                data-nom_societe="<?= htmlspecialchars($s['nom_societe']) ?>" data-ville="<?= htmlspecialchars($s['ville'] ?? '') ?>">
               Modifier
            </button>
            <button class="btn-delete-item w-100" onclick="confirmDelete(<?= $s['id'] ?>, 'stage')">Supprimer</button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<!-- Modal etc. would go here, preserved from original -->

<script>
  function toggleForm() {
    var type = document.getElementById('type').value;
    document.getElementById('travailForm').classList.toggle('hidden', type !== 'travail');
    document.getElementById('stageForm').classList.toggle('hidden', type !== 'stage');
  }

  function confirmDelete(id, type) {
      if (confirm("Supprimer cette offre ?")) {
          window.location.href = "index.php?action=delete&id=" + id + "&type=" + type;
      }
  }

  document.getElementById('toggleEditMode').addEventListener('click', function() {
      document.body.classList.toggle('manage-active');
  });
</script>
