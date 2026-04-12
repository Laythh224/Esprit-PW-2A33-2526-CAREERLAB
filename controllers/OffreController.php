<?php
require_once __DIR__ . '/../models/OpportuniteTravail.php';
require_once __DIR__ . '/../models/Stage.php';

class OffreController {

    public function publish(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('offres.php');
            return;
        }

        $type = $_POST['type_offre'] ?? '';

        if ($type === 'travail') {
            $this->publierTravail();
        } elseif ($type === 'stage') {
            $this->publierStage();
        } else {
            $this->redirect('offres.php?error=type_invalide');
        }
    }

    // ---------------------------------------------------------------
    private function publierTravail(): void {
        $model = new OpportuniteTravail();

        $data = [
            'titre'             => $this->clean($_POST['titre'] ?? ''),
            'description'       => $this->clean($_POST['description'] ?? ''),
            'entreprise'        => $this->clean($_POST['entreprise'] ?? ''),
            'localisation'      => $this->clean($_POST['localisation'] ?? ''),
            'type_contrat'      => $this->clean($_POST['type_contrat'] ?? ''),
            'date_publication'  => $_POST['date_publication'] ?? date('Y-m-d'),
            'date_expiration'   => $_POST['date_expiration'] ?? '',
            'niveau_experience' => $this->clean($_POST['niveau_experience'] ?? ''),
            'domaine'           => $this->clean($_POST['domaine'] ?? ''),
        ];

        // Validation PHP
        if (empty($data['titre'])) { $this->redirect('offres.php?error=titre_requis'); }
        if (empty($data['entreprise'])) { $this->redirect('offres.php?error=entreprise_requise'); }
        if (empty($data['localisation'])) { $this->redirect('offres.php?error=localisation_requise'); }
        if (strlen($data['description']) < 10) { $this->redirect('offres.php?error=description_requise'); }

        if ($model->ajouter($data)) {
            $this->redirect('offres.php?success=travail');
        } else {
            $this->redirect('offres.php?error=insertion_echouee');
        }
    }

    // ---------------------------------------------------------------
    private function publierStage(): void {
        $model = new Stage();

        $data = [
            'nom_societe'    => $this->clean($_POST['nom_societe'] ?? ''),
            'description'    => $this->clean($_POST['description'] ?? ''),
            'adresse'        => $this->clean($_POST['adresse'] ?? ''),
            'ville'          => $this->clean($_POST['ville'] ?? ''),
            'duree'          => $this->clean($_POST['duree'] ?? ''),
            'date_debut'     => $_POST['date_debut'] ?? '',
            'date_fin'       => $_POST['date_fin'] ?? '',
            'niveau_etude'   => $this->clean($_POST['niveau_etude'] ?? ''),
            'statut'         => $this->clean($_POST['statut'] ?? 'disponible'),
            'email_contact'  => $this->clean($_POST['email_contact'] ?? ''),
            'telephone'      => $this->clean($_POST['telephone'] ?? ''),
        ];

        // Validation PHP
        if (empty($data['nom_societe'])) { $this->redirect('offres.php?error=societe_requise'); }
        if (!filter_var($data['email_contact'], FILTER_VALIDATE_EMAIL)) { $this->redirect('offres.php?error=email_invalide'); }
        if (empty($data['telephone']) || !is_numeric($data['telephone'])) { $this->redirect('offres.php?error=tel_invalide'); }
        if (strlen($data['description']) < 10) { $this->redirect('offres.php?error=description_requise'); }
        if (!empty($data['date_debut']) && !empty($data['date_fin']) && $data['date_fin'] < $data['date_debut']) {
            $this->redirect('offres.php?error=dates_invalides');
        }

        if ($model->ajouter($data)) {
            $this->redirect('offres.php?success=stage');
        } else {
            $this->redirect('offres.php?error=insertion_echouee');
        }
    }

    // ---------------------------------------------------------------
    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('offres.php');
        }

        $id = (int)($_POST['id'] ?? 0);
        $type = $_POST['type_offre'] ?? '';

        if (!$id) {
            $this->redirect('offres.php?error=id_manquant');
        }

        $redirectUrl = (($_GET['redirect'] ?? '') === 'tables' || ($_POST['redirect'] ?? '') === 'tables') ? 'tables/tables.php' : 'offres.php';

        if ($type === 'travail') {
            $model = new OpportuniteTravail();
            $data = [
                'titre'             => $this->clean($_POST['titre'] ?? ''),
                'description'       => $this->clean($_POST['description'] ?? ''),
                'entreprise'        => $this->clean($_POST['entreprise'] ?? ''),
                'localisation'      => $this->clean($_POST['localisation'] ?? ''),
                'type_contrat'      => $this->clean($_POST['type_contrat'] ?? ''),
                'date_expiration'   => $_POST['date_expiration'] ?? '',
                'niveau_experience' => $this->clean($_POST['niveau_experience'] ?? ''),
                'domaine'           => $this->clean($_POST['domaine'] ?? ''),
            ];
            // Validation update
            if (empty($data['titre'])) { $this->redirect($redirectUrl . '?error=titre_requis'); }
            if (strlen($data['description']) < 10) { $this->redirect($redirectUrl . '?error=description_requise'); }
            $success = $model->modifier($id, $data);
        } elseif ($type === 'stage') {
            $model = new Stage();
            $data = [
                'nom_societe'    => $this->clean($_POST['nom_societe'] ?? ''),
                'description'    => $this->clean($_POST['description'] ?? ''),
                'adresse'        => $this->clean($_POST['adresse'] ?? ''),
                'ville'          => $this->clean($_POST['ville'] ?? ''),
                'duree'          => $this->clean($_POST['duree'] ?? ''),
                'date_debut'     => $_POST['date_debut'] ?? '',
                'date_fin'       => $_POST['date_fin'] ?? '',
                'niveau_etude'   => $this->clean($_POST['niveau_etude'] ?? ''),
                'statut'         => $this->clean($_POST['statut'] ?? 'disponible'),
                'email_contact'  => $this->clean($_POST['email_contact'] ?? ''),
                'telephone'      => $this->clean($_POST['telephone'] ?? ''),
            ];
            // Validation update
            if (empty($data['nom_societe'])) { $this->redirect($redirectUrl . '?error=societe_requise'); }
            if (!filter_var($data['email_contact'], FILTER_VALIDATE_EMAIL)) { $this->redirect($redirectUrl . '?error=email_invalide'); }
            if (!empty($data['date_debut']) && !empty($data['date_fin']) && $data['date_fin'] < $data['date_debut']) {
                $this->redirect($redirectUrl . '?error=dates_invalides');
            }
            $success = $model->modifier($id, $data);
        } else {
            $this->redirect($redirectUrl . '?error=type_invalide');
        }

        if ($success) {
            $this->redirect($redirectUrl . '?success=update');
        } else {
            $this->redirect($redirectUrl . '?error=update_echoue');
        }
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        $type = $_GET['type'] ?? '';

        if (!$id || !$type) {
            $this->redirect('offres.php?error=params_manquants');
        }

        if ($type === 'travail') {
            $model = new OpportuniteTravail();
        } elseif ($type === 'stage') {
            $model = new Stage();
        } else {
            $this->redirect('offres.php?error=type_invalide');
        }

        $redirectUrl = ($_GET['redirect'] ?? '') === 'tables' ? 'tables/tables.php' : 'offres.php';

        if ($model->supprimer($id)) {
            $this->redirect($redirectUrl . '?success=deleted');
        } else {
            $this->redirect($redirectUrl . '?error=delete_echoue');
        }
    }

    // ---------------------------------------------------------------
    private function clean(string $value): string {
        return htmlspecialchars(strip_tags(trim($value)));
    }

    private function redirect(string $url): void {
        header("Location: $url");
        exit();
    }
}
?>
