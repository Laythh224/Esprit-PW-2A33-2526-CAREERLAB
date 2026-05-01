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
            <h1 class="detail-title">
                <?php if ($type === 'experience'): ?>
                    <?= htmlspecialchars(($offre['prenom'] ?? '') . ' ' . ($offre['nom'] ?? '')) ?>
                <?php else: ?>
                    <?= htmlspecialchars($offre['titre']) ?>
                <?php endif; ?>
            </h1>
            <div class="detail-company">
                <?php if ($type === 'experience'): ?>
                    <i class="fas fa-id-badge"></i> Profil Expérience
                <?php else: ?>
                    <i class="fas fa-building"></i> <?= htmlspecialchars($offre['entreprise']) ?>
                <?php endif; ?>
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
                    <span class="info-label">Nom</span>
                    <span class="info-value"><i class="fas fa-user me-2 text-primary"></i><?= htmlspecialchars($offre['nom'] ?? 'Non spécifié') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Prénom</span>
                    <span class="info-value"><i class="fas fa-user me-2 text-primary"></i><?= htmlspecialchars($offre['prenom'] ?? 'Non spécifié') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Niveau</span>
                    <span class="info-value"><i class="fas fa-layer-group me-2 text-primary"></i><?= htmlspecialchars($offre['niveau'] ?? 'Non spécifié') ?></span>
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

                <?php if ($type === 'travail'): ?>
                <div class="mt-5 text-center">
                    <button type="button" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg fw-bold" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border: none;" data-bs-toggle="modal" data-bs-target="#applyModal">
                        <i class="fas fa-paper-plane me-2"></i> Postuler maintenant
                    </button>
                </div>

                <!-- Apply Modal -->
                <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                      <div class="modal-header text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border-bottom: none; padding: 1.5rem 2rem;">
                        <h5 class="modal-title fw-bold" id="applyModalLabel"><i class="fas fa-rocket me-2"></i> Candidature IA - <?= htmlspecialchars($offre['titre']) ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="index.php?action=apply" method="POST">
                          <input type="hidden" name="offre_id" value="<?= $offre['id'] ?>">
                          <div class="modal-body p-4 p-md-5 bg-light">
                              <div class="row g-4 text-start">
                                  <div class="col-md-6">
                                      <label class="form-label fw-bold text-dark"><i class="fas fa-user text-primary me-2"></i> Nom complet</label>
                                      <input type="text" name="nom_candidat" class="form-control form-control-lg rounded-3 border-0 shadow-sm" required placeholder="Ex: Jean Dupont">
                                  </div>
                                  <div class="col-md-6">
                                      <label class="form-label fw-bold text-dark"><i class="fas fa-envelope text-primary me-2"></i> Email</label>
                                      <input type="email" name="email_candidat" class="form-control form-control-lg rounded-3 border-0 shadow-sm" required placeholder="Ex: jean@email.com">
                                  </div>
                                  <div class="col-12">
                                      <label class="form-label fw-bold text-dark"><i class="fas fa-file-alt text-primary me-2"></i> CV (Texte brut)</label>
                                      <textarea name="cv_texte" class="form-control rounded-3 border-0 shadow-sm" rows="6" required placeholder="Copiez-collez le contenu de votre CV ici... N'oubliez pas d'inclure vos compétences (PHP, SQL...) et vos années d'expérience !"></textarea>
                                      <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle text-primary"></i> Notre IA analysera automatiquement vos mots-clés et votre expérience.</small>
                                  </div>
                                  
                                  <div class="col-12 mt-4">
                                      <h5 class="fw-bold text-primary mb-3"><i class="fas fa-brain me-2"></i> Test Technique Dynamique</h5>
                                      
                                      <div class="mb-4">
                                          <label class="form-label fw-bold text-dark">Choisissez votre domaine pour le test :</label>
                                          <select name="qcm_domaine" id="qcmDomaine" class="form-select form-select-lg rounded-3 border-0 shadow-sm" required onchange="showQCM()">
                                              <option value="" disabled selected>Sélectionnez un domaine...</option>
                                              <option value="informatique">Informatique</option>
                                              <option value="economie">Économie</option>
                                              <option value="architecture">Architecture</option>
                                              <option value="electromecanique">Électromécanique</option>
                                          </select>
                                      </div>

                                      <!-- QCM Informatique -->
                                      <div id="qcm_informatique" class="qcm-block" style="display: none;">
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">1. Quel langage est principalement utilisé pour le backend web parmi ceux-ci ?</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="info_q1" value="html"> <label class="form-check-label">HTML</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="info_q1" value="php"> <label class="form-check-label">PHP</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="info_q1" value="css"> <label class="form-check-label">CSS</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">2. Que signifie SQL ?</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="info_q2" value="sql"> <label class="form-check-label">Structured Query Language</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="info_q2" value="simple"> <label class="form-check-label">Simple Query Language</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">3. Lequel est un framework JavaScript ?</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="info_q3" value="laravel"> <label class="form-check-label">Laravel</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="info_q3" value="js"> <label class="form-check-label">React</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">4. Qu'est-ce que Git ?</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="info_q4" value="vcs"> <label class="form-check-label">Un système de contrôle de version</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="info_q4" value="ide"> <label class="form-check-label">Un éditeur de code</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">5. Lequel n'est pas une base de données NoSQL ?</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="info_q5" value="mongo"> <label class="form-check-label">MongoDB</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="info_q5" value="mysql"> <label class="form-check-label">MySQL</label></div>
                                              </div>
                                          </div>
                                      </div>

                                      <!-- QCM Economie -->
                                      <div id="qcm_economie" class="qcm-block" style="display: none;">
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">1. Qu'est-ce que l'inflation ?</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="eco_q1" value="hausse"> <label class="form-check-label">La hausse générale des prix</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="eco_q1" value="baisse"> <label class="form-check-label">La baisse du chômage</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">2. Que signifie PIB ?</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="eco_q2" value="pib"> <label class="form-check-label">Produit Intérieur Brut</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="eco_q2" value="prix"> <label class="form-check-label">Prix Indice de Base</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">3. Selon la loi de l'offre et de la demande, si la demande augmente et l'offre stagne :</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="eco_q3" value="monte"> <label class="form-check-label">Le prix augmente</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="eco_q3" value="descend"> <label class="form-check-label">Le prix diminue</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">4. Qu'est-ce qu'un marché oligopolistique ?</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="eco_q4" value="peu"> <label class="form-check-label">Quelques vendeurs, beaucoup d'acheteurs</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="eco_q4" value="un"> <label class="form-check-label">Un seul vendeur</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">5. Quel organisme gère la politique monétaire en zone euro ?</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="eco_q5" value="bce"> <label class="form-check-label">La BCE</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="eco_q5" value="fmi"> <label class="form-check-label">Le FMI</label></div>
                                              </div>
                                          </div>
                                      </div>

                                      <!-- QCM Architecture -->
                                      <div id="qcm_architecture" class="qcm-block" style="display: none;">
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">1. Sur un plan à l'échelle 1/100, 1 cm représente :</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="arch_q1" value="1m"> <label class="form-check-label">1 mètre</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="arch_q1" value="10m"> <label class="form-check-label">10 mètres</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">2. Quel logiciel est principalement utilisé pour le dessin assisté par ordinateur (DAO) ?</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="arch_q2" value="autocad"> <label class="form-check-label">AutoCAD</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="arch_q2" value="photoshop"> <label class="form-check-label">Photoshop</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">3. Qu'est-ce qu'un mur porteur ?</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="arch_q3" value="soutient"> <label class="form-check-label">Un mur qui soutient la structure</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="arch_q3" value="deco"> <label class="form-check-label">Un mur purement décoratif</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">4. L'ordre Dorique, Ionique et Corinthien font partie de l'architecture :</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="arch_q4" value="grecque"> <label class="form-check-label">Grecque antique</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="arch_q4" value="gothique"> <label class="form-check-label">Gothique</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">5. Le BIM (Building Information Modeling) est :</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="arch_q5" value="maquette"> <label class="form-check-label">Une maquette numérique intelligente</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="arch_q5" value="materiau"> <label class="form-check-label">Un nouveau matériau de construction</label></div>
                                              </div>
                                          </div>
                                      </div>

                                      <!-- QCM Electromecanique -->
                                      <div id="qcm_electromecanique" class="qcm-block" style="display: none;">
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">1. La loi d'Ohm s'écrit :</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="elec_q1" value="uri"> <label class="form-check-label">U = R x I</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="elec_q1" value="uir"> <label class="form-check-label">U = I / R</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">2. Un moteur asynchrone fonctionne avec :</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="elec_q2" value="alternatif"> <label class="form-check-label">Du courant alternatif</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="elec_q2" value="continu"> <label class="form-check-label">Du courant continu</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">3. L'unité de mesure de la puissance électrique est :</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="elec_q3" value="watt"> <label class="form-check-label">Le Watt</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="elec_q3" value="volt"> <label class="form-check-label">Le Volt</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">4. Qu'est-ce qu'un capteur inductif détecte ?</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="elec_q4" value="metal"> <label class="form-check-label">Les objets métalliques</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="elec_q4" value="tout"> <label class="form-check-label">Tous les objets</label></div>
                                              </div>
                                          </div>
                                          <div class="card border-0 shadow-sm rounded-3 mb-3">
                                              <div class="card-body">
                                                  <p class="mb-2 fw-bold">5. Dans un circuit hydraulique, le débit est lié à :</p>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="elec_q5" value="vitesse"> <label class="form-check-label">La vitesse du fluide</label></div>
                                                  <div class="form-check"><input class="form-check-input" type="radio" name="elec_q5" value="pression"> <label class="form-check-label">La pression du fluide</label></div>
                                              </div>
                                          </div>
                                      </div>

                                  </div>
                              </div>
                          </div>
                          <div class="modal-footer bg-white border-0 p-4">
                            <button type="button" class="btn btn-light btn-lg rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border: none;">
                                Postuler
                            </button>
                          </div>
                      </form>
                      <script>
                        function showQCM() {
                            var domaine = document.getElementById('qcmDomaine').value;
                            var blocks = document.getElementsByClassName('qcm-block');
                            for (var i = 0; i < blocks.length; i++) {
                                blocks[i].style.display = 'none';
                                var inputs = blocks[i].querySelectorAll('input');
                                inputs.forEach(inp => inp.required = false);
                            }
                            if (domaine) {
                                var selectedBlock = document.getElementById('qcm_' + domaine);
                                selectedBlock.style.display = 'block';
                                var inputs = selectedBlock.querySelectorAll('input[type="radio"]');
                                var names = new Set();
                                inputs.forEach(inp => names.add(inp.name));
                                names.forEach(name => {
                                    var radios = document.querySelectorAll('input[name="'+name+'"]');
                                    if(radios.length > 0) radios[0].required = true;
                                });
                            }
                        }
                      </script>
                    </div>
                  </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
