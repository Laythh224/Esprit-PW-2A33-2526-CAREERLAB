<style>
    html, body {
      min-height: 100%;
      background: linear-gradient(180deg, #d3e5ff 0%, #96bbff 45%, #5d8df5 100%);
      background-attachment: fixed;
      color: #0f172a;
    }
    .detail-container {
      padding: 4rem 0;
    }
    .detail-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 2rem;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .detail-header {
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      color: white;
      padding: 3rem 2rem;
      position: relative;
    }
    .badge-type {
      display: inline-block;
      padding: 0.5rem 1.25rem;
      border-radius: 9999px;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.05em;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      background: rgba(255, 255, 255, 0.2); 
      border: 1px solid rgba(255, 255, 255, 0.4);
    }
    
    .detail-title {
      font-size: 2.5rem;
      font-weight: 800;
      margin-bottom: 1rem;
      line-height: 1.2;
    }
    .detail-company {
      font-size: 1.25rem;
      opacity: 0.9;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .detail-body {
      padding: 3rem 2.5rem;
    }
    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 2rem;
      margin-bottom: 3rem;
      padding-bottom: 2rem;
      border-bottom: 1px solid #e2e8f0;
    }
    .info-item {
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
    }
    .info-label {
      font-size: 0.875rem;
      text-transform: uppercase;
      font-weight: 600;
      color: #64748b;
      letter-spacing: 0.025em;
    }
    .info-value {
      font-size: 1.125rem;
      font-weight: 700;
      color: #1e293b;
    }
    .detail-description {
      font-size: 1.1rem;
      line-height: 1.8;
      color: #334155;
      white-space: pre-line;
    }
    .description-title {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      color: #1e293b;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      background: white;
      color: #4f46e5;
      border-radius: 1rem;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.2s;
      margin-bottom: 2rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .btn-back:hover {
      transform: translateX(-5px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
      color: #4338ca;
    }
</style>

<div class="container detail-container">
    <a href="index.php?action=offres" class="btn-back">
        <i class="fas fa-arrow-left"></i> Retour aux offres
    </a>

    <div class="detail-card">
        <div class="detail-header">
            <span class="badge-type"><?= $type === 'travail' ? "Offre d'Emploi" : "Niveau d'Expérience" ?></span>
            <h1 class="detail-title"><?= htmlspecialchars($offre['titre']) ?></h1>
            <div class="detail-company">
                <i class="fas fa-building"></i> <?= htmlspecialchars($offre['entreprise']) ?>
            </div>
        </div>

        <div class="detail-body">
            <div class="info-grid">
                <?php if ($type === 'travail'): ?>
                <div class="info-item">
                    <span class="info-label">Localisation</span>
                    <span class="info-value"><i class="fas fa-map-marker-alt me-2 text-primary"></i><?= htmlspecialchars($offre['localisation'] ?? 'Non spécifiée') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Domaine</span>
                    <span class="info-value"><i class="fas fa-briefcase me-2 text-primary"></i><?= htmlspecialchars($offre['domaine'] ?? 'Non spécifié') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Contrat</span>
                    <span class="info-value"><i class="fas fa-file-contract me-2 text-primary"></i><?= htmlspecialchars($offre['type_contrat'] ?? 'Non spécifié') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Expérience</span>
                    <span class="info-value"><i class="fas fa-user-graduate me-2 text-primary"></i><?= htmlspecialchars($offre['niveau_experience'] ?? 'Non spécifié') ?></span>
                </div>
                <?php if (!empty($offre['date_expiration'])): ?>
                <div class="info-item">
                    <span class="info-label">Expire le</span>
                    <span class="info-value"><i class="fas fa-calendar-times me-2 text-danger"></i><?= date('d/m/Y', strtotime($offre['date_expiration'])) ?></span>
                </div>
                <?php endif; ?>
                <?php else: ?>
                <div class="info-item">
                    <span class="info-label">Type d'entité</span>
                    <span class="info-value"><i class="fas fa-graduation-cap me-2 text-primary"></i>Configuration Carrière</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Catégorie</span>
                    <span class="info-value"><i class="fas fa-tags me-2 text-primary"></i>Niveau hiérarchique</span>
                </div>
                <?php endif; ?>
            </div>

            <div class="description-section">
                <h3 class="description-title">
                    <i class="fas fa-align-left text-primary"></i> <?= $type === 'travail' ? "Description du poste" : "Détails du niveau" ?>
                </h3>
                <div class="detail-description">
                    <?= nl2br(htmlspecialchars($offre['description'])) ?>
                </div>
            </div>
        </div>
    </div>
</div>
