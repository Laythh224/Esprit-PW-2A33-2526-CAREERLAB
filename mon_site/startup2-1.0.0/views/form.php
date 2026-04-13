<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - PHP CRUD</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/plugins.min.css">
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css">
    <link rel="stylesheet" href="../assets/css/demo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-<?php echo $mode === 'edit' ? 'edit' : 'plus'; ?> me-2"></i>
                            <?php echo htmlspecialchars($title); ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Messages d'erreur -->
                        <?php if (!empty($errors)): ?>
                            <?php foreach ($errors as $error): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php echo htmlspecialchars($error); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Erreurs de validation -->
                        <?php if (!empty($validation_errors)): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Veuillez corriger les erreurs suivantes :</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($validation_errors as $field => $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="<?php echo htmlspecialchars($form_method); ?>" action="<?php echo htmlspecialchars($form_action); ?>" id="metierForm">
                            <?php if ($mode === 'edit' && $metier): ?>
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($metier['id']); ?>">
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nom" class="form-label">
                                        <i class="fas fa-briefcase me-1"></i>Nom du Métier <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control <?php echo isset($validation_errors['nom']) ? 'is-invalid' : ''; ?>"
                                           id="nom" name="nom" required
                                           value="<?php echo htmlspecialchars(($old_input['nom'] ?? null) ?: ($metier['nom'] ?? '')); ?>"
                                           placeholder="Ex: Développeur Web, Designer Graphique...">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="secteur" class="form-label">
                                        <i class="fas fa-building me-1"></i>Secteur d'activité
                                    </label>
                                    <select class="form-control <?php echo isset($validation_errors['secteur']) ? 'is-invalid' : ''; ?>"
                                            id="secteur" name="secteur">
                                        <option value="">Sélectionner un secteur</option>
                                        <?php
                                        $secteurs = ['Technologie', 'Santé', 'Finance', 'Éducation', 'Commerce', 'Industrie', 'Services', 'Autre'];
                                        $selectedSecteur = ($old_input['secteur'] ?? null) ?: ($metier['secteur'] ?? '');
                                        foreach ($secteurs as $secteur):
                                        ?>
                                            <option value="<?php echo htmlspecialchars($secteur); ?>" <?php echo $selectedSecteur === $secteur ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($secteur); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Description
                                </label>
                                <textarea class="form-control <?php echo isset($validation_errors['description']) ? 'is-invalid' : ''; ?>"
                                          id="description" name="description" rows="4"
                                          placeholder="Décrivez les principales responsabilités, compétences requises, etc."><?php echo htmlspecialchars(($old_input['description'] ?? null) ?: ($metier['description'] ?? '')); ?></textarea>
                                <div class="form-text">Maximum 500 caractères</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="salaire_moyen" class="form-label">
                                        <i class="fas fa-euro-sign me-1"></i>Salaire Moyen Annuel
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control <?php echo isset($validation_errors['salaire_moyen']) ? 'is-invalid' : ''; ?>"
                                               id="salaire_moyen" name="salaire_moyen" min="0" step="100"
                                               value="<?php echo htmlspecialchars(($old_input['salaire_moyen'] ?? null) ?: ($metier['salaire_moyen'] ?? '')); ?>"
                                               placeholder="Ex: 35000">
                                        <span class="input-group-text">€</span>
                                    </div>
                                    <div class="form-text">Salaire brut annuel moyen</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Date de création
                                    </label>
                                    <input type="text" class="form-control" readonly
                                           value="<?php echo $mode === 'edit' && $metier ? date('d/m/Y H:i', strtotime($metier['date_creation'])) : date('d/m/Y H:i'); ?>">
                                    <div class="form-text">
                                        <?php echo $mode === 'edit' ? 'Date de création du métier' : 'Date d\'ajout du métier'; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <a href="index.php" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                                        </a>
                                        <div>
                                            <button type="reset" class="btn btn-outline-secondary me-2">
                                                <i class="fas fa-undo me-1"></i>Réinitialiser
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i>
                                                <?php echo htmlspecialchars($submit_text); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informations
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Champs obligatoires</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Nom du métier</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Champs optionnels</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-minus text-muted me-2"></i>Description</li>
                                    <li><i class="fas fa-minus text-muted me-2"></i>Secteur</li>
                                    <li><i class="fas fa-minus text-muted me-2"></i>Salaire moyen</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script>
        // Validation côté client
        document.getElementById('metierForm').addEventListener('submit', function(e) {
            const nom = document.getElementById('nom').value.trim();
            const description = document.getElementById('description').value;
            const salaire = document.getElementById('salaire_moyen').value;

            if (!nom) {
                alert('Le nom du métier est obligatoire.');
                e.preventDefault();
                return false;
            }

            if (description.length > 500) {
                alert('La description ne peut pas dépasser 500 caractères.');
                e.preventDefault();
                return false;
            }

            if (salaire && (salaire < 0 || salaire > 1000000)) {
                alert('Le salaire doit être compris entre 0 et 1 000 000 €.');
                e.preventDefault();
                return false;
            }
        });

        // Compteur de caractères pour la description
        document.getElementById('description').addEventListener('input', function() {
            const maxLength = 500;
            const currentLength = this.value.length;
            const remaining = maxLength - currentLength;

            // Créer ou mettre à jour le compteur
            let counter = this.parentNode.querySelector('.char-counter');
            if (!counter) {
                counter = document.createElement('div');
                counter.className = 'form-text char-counter';
                this.parentNode.appendChild(counter);
            }

            counter.innerHTML = `<small class="${remaining < 0 ? 'text-danger' : 'text-muted'}">${currentLength}/${maxLength} caractères</small>`;

            // Changer la classe du textarea si dépassement
            this.classList.toggle('is-invalid', remaining < 0);
        });

        // Formatage automatique du salaire
        document.getElementById('salaire_moyen').addEventListener('blur', function() {
            const value = parseInt(this.value);
            if (!isNaN(value) && value > 0) {
                this.value = value;
            }
        });

        // Auto-focus sur le premier champ
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('nom').focus();
        });
    </script>
</body>
</html>