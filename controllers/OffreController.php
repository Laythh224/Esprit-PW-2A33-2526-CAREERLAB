<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/OpportuniteTravail.php';
require_once __DIR__ . '/../models/Stage.php';

class OffreController extends BaseController {

    public function index(): void {
        $pdo = Database::getInstance()->getConnection();

        // Filtre de recherche
        $filtre = $_GET['filtre'] ?? 'all';
        $search = trim($_GET['q'] ?? '');

        // Offres de travail
        $sqlTravail = "SELECT *, 'travail' AS type_offre FROM OpportuniteTravail WHERE 1=1";
        if ($search) $sqlTravail .= " AND (titre LIKE :q OR entreprise LIKE :q OR domaine LIKE :q OR localisation LIKE :q)";
        $sqlTravail .= " ORDER BY id DESC";

        // Stages
        $sqlStage = "SELECT *, 'stage' AS type_offre FROM Stage WHERE statut='disponible'";
        if ($search) $sqlStage .= " AND (nom_societe LIKE :q OR ville LIKE :q OR description LIKE :q)";
        $sqlStage .= " ORDER BY id DESC";

        $stmtT = $pdo->prepare($sqlTravail);
        $stmtS = $pdo->prepare($sqlStage);
        if ($search) {
            $stmtT->bindValue(':q', "%$search%");
            $stmtS->bindValue(':q', "%$search%");
        }
        $stmtT->execute();
        $stmtS->execute();
        $travaux = $stmtT->fetchAll();
        $stages  = $stmtS->fetchAll();

        $this->render('offres/list', [
            'action' => 'offres',
            'title'  => 'Les Offres - Career Lab',
            'travaux' => $travaux,
            'stages'  => $stages,
            'filtre'  => $filtre,
            'search'  => $search,
            'totalTravail' => count($travaux),
            'totalStage'   => count($stages)
        ]);
    }

    public function publish(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('offres');
            return;
        }

        $type = $_POST['type_offre'] ?? '';

        if ($type === 'travail') {
            $this->publierTravail();
        } elseif ($type === 'stage') {
            $this->publierStage();
        } else {
            $this->redirect('offres', ['error' => 'type_invalide']);
        }
    }

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

        if (empty($data['titre'])) { $this->redirect('offres', ['error' => 'titre_requis']); }

        if ($model->ajouter($data)) {
            $this->redirect('offres', ['success' => 'travail']);
        } else {
            $this->redirect('offres', ['error' => 'insertion_echouee']);
        }
    }

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

        if (empty($data['nom_societe'])) { $this->redirect('offres', ['error' => 'societe_requise']); }

        if ($model->ajouter($data)) {
            $this->redirect('offres', ['success' => 'stage']);
        } else {
            $this->redirect('offres', ['error' => 'insertion_echouee']);
        }
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('offres');
        }

        $id = (int)($_POST['id'] ?? 0);
        $type = $_POST['type_offre'] ?? '';

        if (!$id) { $this->redirect('offres', ['error' => 'id_manquant']); }

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
            $success = $model->modifier($id, $data);
        } else {
            $this->redirect('offres', ['error' => 'type_invalide']);
        }

        if ($success) {
            $this->redirect('offres', ['success' => 'update']);
        } else {
            $this->redirect('offres', ['error' => 'update_echoue']);
        }
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        $type = $_GET['type'] ?? '';

        if (!$id || !$type) { $this->redirect('offres', ['error' => 'params_manquants']); }

        if ($type === 'travail') {
            $model = new OpportuniteTravail();
        } elseif ($type === 'stage') {
            $model = new Stage();
        } else {
            $this->redirect('offres', ['error' => 'type_invalide']);
        }

        if ($model->supprimer($id)) {
            $this->redirect('offres', ['success' => 'deleted']);
        } else {
            $this->redirect('offres', ['error' => 'delete_echoue']);
        }
    }
}
