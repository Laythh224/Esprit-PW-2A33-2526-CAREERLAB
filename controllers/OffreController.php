<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/OpportuniteTravail.php';
require_once __DIR__ . '/../models/Experience.php';

class OffreController extends BaseController {

    private function getDb() {
        return Database::getInstance()->getConnection();
    }

    public function index(): void {
        $pdo = $this->getDb();
        $search = trim($_GET['q'] ?? '');

        $sqlTravail = "SELECT t.*, e.niveau AS niveau_experience FROM OpportuniteTravail t LEFT JOIN Experience e ON t.experience_id = e.id WHERE 1=1";
        if ($search) $sqlTravail .= " AND (t.titre LIKE :q OR t.entreprise LIKE :q OR t.domaine LIKE :q OR t.localisation LIKE :q)";
        $sqlTravail .= " ORDER BY t.id DESC";

        $stmtT = $pdo->prepare($sqlTravail);
        if ($search) $stmtT->bindValue(':q', "%$search%");
        $stmtT->execute();
        $travaux = $stmtT->fetchAll();

        // Expériences pour le dropdown
        $stmtE = $pdo->query("SELECT * FROM Experience ORDER BY id ASC");
        $experiences = $stmtE->fetchAll();

        $this->render('offres/list', [
            'action' => 'offres',
            'title'  => 'Les Offres - Career Lab',
            'travaux' => $travaux,
            'search'  => $search,
            'totalTravail' => count($travaux),
            'experiences' => $experiences
        ]);
    }

    public function publish(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('offres');
            return;
        }

        $type = $_POST['type_offre'] ?? '';
        if ($type === 'travail') $this->publierTravail();
        elseif ($type === 'experience') $this->publierExperience();
        else $this->redirect('offres', ['error' => 'type_invalide']);
    }

    private function publierExperience(): void {
        $pdo = $this->getDb();
        $niveau = $this->clean($_POST['niveau'] ?? '');
        $description = $this->clean($_POST['description'] ?? '');

        if (empty($niveau)) $this->redirect('offres', ['error' => 'niveau_requis']);

        $sql = "INSERT INTO Experience (niveau, description) VALUES (:niveau, :description)";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([':niveau' => $niveau, ':description' => $description]);

        if ($success) $this->redirect('offres', ['success' => 'experience']);
        else $this->redirect('offres', ['error' => 'insertion_echouee']);
    }

    private function publierTravail(): void {
        $pdo = $this->getDb();
        $data = [
            ':titre'           => $this->clean($_POST['titre'] ?? ''),
            ':description'     => $this->clean($_POST['description'] ?? ''),
            ':entreprise'      => $this->clean($_POST['entreprise'] ?? ''),
            ':localisation'    => $this->clean($_POST['localisation'] ?? ''),
            ':type_contrat'    => $this->clean($_POST['type_contrat'] ?? ''),
            ':date_expiration' => !empty($_POST['date_expiration']) ? $_POST['date_expiration'] : null,
            ':experience_id'   => (int)($_POST['experience_id'] ?? 0),
            ':domaine'         => $this->clean($_POST['domaine'] ?? ''),
        ];

        if (empty($data[':titre'])) $this->redirect('offres', ['error' => 'titre_requis']);

        $sql = "INSERT INTO OpportuniteTravail 
                    (titre, description, entreprise, localisation, type_contrat, date_expiration, experience_id, domaine)
                VALUES 
                    (:titre, :description, :entreprise, :localisation, :type_contrat, :date_expiration, :experience_id, :domaine)";
        
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($data)) $this->redirect('offres', ['success' => 'travail']);
        else $this->redirect('offres', ['error' => 'insertion_echouee']);
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('offres');

        $pdo = $this->getDb();
        $id = (int)($_POST['id'] ?? 0);
        $type = $_POST['type_offre'] ?? '';
        if (!$id) $this->redirect('offres', ['error' => 'id_manquant']);

        $success = false;
        if ($type === 'travail') {
            $sql = "UPDATE OpportuniteTravail SET titre=:titre, description=:description, entreprise=:entreprise, 
                           localisation=:localisation, type_contrat=:type_contrat, date_expiration=:date_expiration, 
                           experience_id=:experience_id, domaine=:domaine WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute([
                ':id'              => $id,
                ':titre'           => $this->clean($_POST['titre'] ?? ''),
                ':description'     => $this->clean($_POST['description'] ?? ''),
                ':entreprise'      => $this->clean($_POST['entreprise'] ?? ''),
                ':localisation'    => $this->clean($_POST['localisation'] ?? ''),
                ':type_contrat'    => $this->clean($_POST['type_contrat'] ?? ''),
                ':date_expiration' => !empty($_POST['date_expiration']) ? $_POST['date_expiration'] : null,
                ':experience_id'   => (int)($_POST['experience_id'] ?? 0),
                ':domaine'         => $this->clean($_POST['domaine'] ?? ''),
            ]);
        } elseif ($type === 'experience') {
            $sql = "UPDATE Experience SET niveau=:niveau, description=:description WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute([
                ':id'          => $id,
                ':niveau'      => $this->clean($_POST['niveau'] ?? ''),
                ':description' => $this->clean($_POST['description'] ?? ''),
            ]);
        }

        $redirect = $_POST['redirect'] ?? 'offres';
        $location = ($redirect === 'tables') ? "tables/tables.php?success=update" : "index.php?action=offres&success=update";
        if (!$success) $location = ($redirect === 'tables') ? "tables/tables.php?error=update" : "index.php?action=offres&error=update_echoue";
        header("Location: $location");
        exit();
    }

    public function delete(): void {
        $pdo = $this->getDb();
        $id = (int)($_GET['id'] ?? 0);
        $type = $_GET['type'] ?? '';
        if (!$id || !$type) $this->redirect('offres', ['error' => 'params_manquants']);

        $table = ($type === 'travail') ? 'OpportuniteTravail' : 'Experience';
        $stmt = $pdo->prepare("DELETE FROM $table WHERE id = :id");
        $success = $stmt->execute([':id' => $id]);

        $redirect = $_GET['redirect'] ?? 'offres';
        $location = ($redirect === 'tables') ? "tables/tables.php?success=deleted" : "index.php?action=offres&success=deleted";
        if (!$success) $location = ($redirect === 'tables') ? "tables/tables.php?error=delete" : "index.php?action=offres&error=delete_echoue";
        header("Location: $location");
        exit();
    }

    public function show(): void {
        $pdo = $this->getDb();
        $id = (int)($_GET['id'] ?? 0);
        $type = $_GET['type'] ?? '';
        if (!$id || !$type) { $this->redirect('offres', ['error' => 'params_manquants']); return; }

        if ($type === 'travail') {
            $sql = "SELECT t.*, e.niveau AS niveau_experience FROM OpportuniteTravail t LEFT JOIN Experience e ON t.experience_id = e.id WHERE t.id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $offre = $stmt->fetch();
            $title = "Détails Emploi - Career Lab";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM Experience WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $offre = $stmt->fetch();
            $title = "Détails Expérience - Career Lab";
            if ($offre) { $offre['titre'] = $offre['niveau']; $offre['entreprise'] = 'Niveau de Carrière'; }
        }

        if (!$offre) { $this->redirect('offres', ['error' => 'offre_introuvable']); return; }

        $this->render('offres/detail', [ 'action' => 'offres', 'title'  => $title, 'offre'  => $offre, 'type'   => $type ]);
    }
}
?>
