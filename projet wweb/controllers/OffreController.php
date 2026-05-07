<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/OpportuniteTravail.php';
require_once __DIR__ . '/../models/Experience.php';

class OffreController extends BaseController {

    private function getDb() {
        return Database::getInstance()->getConnection();
    }

    public function index(): void {
        $pdo = $this->getDb();
        $search = trim($_GET['q'] ?? '');
        $sort = $_GET['sort'] ?? 'newest';

        $sqlTravail = "SELECT t.*, e.niveau AS niveau_experience FROM OpportuniteTravail t LEFT JOIN Experience e ON t.experience_id = e.id WHERE 1=1";
        if ($search) $sqlTravail .= " AND (t.titre LIKE :q OR t.entreprise LIKE :q OR t.domaine LIKE :q OR t.localisation LIKE :q)";
        
        switch ($sort) {
            case 'exp_title':
                $sqlTravail .= " ORDER BY FIELD(e.niveau, 'Junior', 'Intermédiaire', 'Confirmé', 'Senior', 'Expert') ASC, t.titre ASC";
                break;
            case 'title_asc':
                $sqlTravail .= " ORDER BY t.titre ASC";
                break;
            case 'exp_asc':
                $sqlTravail .= " ORDER BY FIELD(e.niveau, 'Junior', 'Intermédiaire', 'Confirmé', 'Senior', 'Expert') ASC";
                break;
            case 'oldest':
                $sqlTravail .= " ORDER BY t.id ASC";
                break;
            case 'newest':
            default:
                $sqlTravail .= " ORDER BY t.id DESC";
                break;
        }

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
            'sort'    => $sort,
            'totalTravail' => count($travaux),
            'experiences' => $experiences,
            'entreprise_nom' => $_SESSION['entreprise_nom'] ?? null
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
        $nom = $this->clean($_POST['nom'] ?? '');
        $prenom = $this->clean($_POST['prenom'] ?? '');
        $niveau = $this->clean($_POST['niveau'] ?? '');
        $description = $this->clean($_POST['description'] ?? '');

        if (empty($niveau)) $this->redirect('offres', ['error' => 'niveau_requis']);

        $sql = "INSERT INTO Experience (nom, prenom, niveau, description) VALUES (:nom, :prenom, :niveau, :description)";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':niveau' => $niveau,
            ':description' => $description
        ]);

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
            $sql = "UPDATE Experience SET nom=:nom, prenom=:prenom, niveau=:niveau, description=:description WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute([
                ':id'          => $id,
                ':nom'         => $this->clean($_POST['nom'] ?? ''),
                ':prenom'      => $this->clean($_POST['prenom'] ?? ''),
                ':niveau'      => $this->clean($_POST['niveau'] ?? ''),
                ':description' => $this->clean($_POST['description'] ?? ''),
            ]);
        }

        $redirect = $_POST['redirect'] ?? 'offres';
        $location = ($redirect === 'tables') ? "admin.php?success=update" : "index.php?action=offres&success=update";
        if (!$success) $location = ($redirect === 'tables') ? "admin.php?error=update" : "index.php?action=offres&error=update_echoue";
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
        $location = ($redirect === 'tables') ? "admin.php?success=deleted" : "index.php?action=offres&success=deleted";
        if (!$success) $location = ($redirect === 'tables') ? "admin.php?error=delete" : "index.php?action=offres&error=delete_echoue";
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

    public function apply(): void {
        $pdo = $this->getDb();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['offre_id'] ?? 0);
            if (!$id) { $this->redirect('offres', ['error' => 'id_manquant']); return; }

            $nom_candidat = $this->clean($_POST['nom_candidat'] ?? '');
            $email_candidat = $this->clean($_POST['email_candidat'] ?? '');
            $cv_texte = $this->clean($_POST['cv_texte'] ?? '');
            
            // Calcul du score test dynamique selon le domaine
            $score_test = 0;
            $domaine_qcm = $_POST['qcm_domaine'] ?? '';

            if ($domaine_qcm === 'informatique') {
                if (isset($_POST['info_q1']) && $_POST['info_q1'] === 'php') $score_test++;
                if (isset($_POST['info_q2']) && $_POST['info_q2'] === 'sql') $score_test++;
                if (isset($_POST['info_q3']) && $_POST['info_q3'] === 'js') $score_test++;
                if (isset($_POST['info_q4']) && $_POST['info_q4'] === 'vcs') $score_test++;
                if (isset($_POST['info_q5']) && $_POST['info_q5'] === 'mysql') $score_test++;
            } elseif ($domaine_qcm === 'economie') {
                if (isset($_POST['eco_q1']) && $_POST['eco_q1'] === 'hausse') $score_test++;
                if (isset($_POST['eco_q2']) && $_POST['eco_q2'] === 'pib') $score_test++;
                if (isset($_POST['eco_q3']) && $_POST['eco_q3'] === 'monte') $score_test++;
                if (isset($_POST['eco_q4']) && $_POST['eco_q4'] === 'peu') $score_test++;
                if (isset($_POST['eco_q5']) && $_POST['eco_q5'] === 'bce') $score_test++;
            } elseif ($domaine_qcm === 'architecture') {
                if (isset($_POST['arch_q1']) && $_POST['arch_q1'] === '1m') $score_test++;
                if (isset($_POST['arch_q2']) && $_POST['arch_q2'] === 'autocad') $score_test++;
                if (isset($_POST['arch_q3']) && $_POST['arch_q3'] === 'soutient') $score_test++;
                if (isset($_POST['arch_q4']) && $_POST['arch_q4'] === 'grecque') $score_test++;
                if (isset($_POST['arch_q5']) && $_POST['arch_q5'] === 'maquette') $score_test++;
            } elseif ($domaine_qcm === 'electromecanique') {
                if (isset($_POST['elec_q1']) && $_POST['elec_q1'] === 'uri') $score_test++;
                if (isset($_POST['elec_q2']) && $_POST['elec_q2'] === 'alternatif') $score_test++;
                if (isset($_POST['elec_q3']) && $_POST['elec_q3'] === 'watt') $score_test++;
                if (isset($_POST['elec_q4']) && $_POST['elec_q4'] === 'metal') $score_test++;
                if (isset($_POST['elec_q5']) && $_POST['elec_q5'] === 'vitesse') $score_test++;
            }

            // Récupérer les infos de l'offre pour la compatibilité
            $stmt = $pdo->prepare("SELECT titre, domaine FROM OpportuniteTravail WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $offre = $stmt->fetch();
            $offre_titre = $offre['titre'] ?? '';
            $offre_domaine = $offre['domaine'] ?? '';

            // Analyse IA
            require_once __DIR__ . '/CandidatureAnalyzer.php';
            $analyzer = new CandidatureAnalyzer();
            $ia_results = $analyzer->analyser($cv_texte, $offre_domaine, $offre_titre, $score_test);

            $sql = "INSERT INTO Candidature 
                    (offre_id, nom_candidat, email_candidat, cv_texte, score_test, score_ia, compatibilite, niveau, recommandation, feedback) 
                    VALUES 
                    (:offre_id, :nom, :email, :cv, :test, :score, :comp, :niv, :rec, :feed)";
            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute([
                ':offre_id' => $id,
                ':nom' => $nom_candidat,
                ':email' => $email_candidat,
                ':cv' => $cv_texte,
                ':test' => $score_test,
                ':score' => $ia_results['score_ia'],
                ':comp' => $ia_results['compatibilite'],
                ':niv' => $ia_results['niveau'],
                ':rec' => $ia_results['recommandation'],
                ':feed' => $ia_results['feedback']
            ]);

            if ($success) $this->redirect('offres', ['success' => 'postule']);
            else $this->redirect('offres', ['error' => 'postulation_echouee']);
        } else {
            $this->redirect('offres');
        }
    }

    public function deleteCandidature(): void {
        $pdo = $this->getDb();
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) { header("Location: admin_about.php?error=id_manquant"); exit(); }

        $stmt = $pdo->prepare("DELETE FROM Candidature WHERE id = :id");
        $success = $stmt->execute([':id' => $id]);

        header("Location: admin_about.php?success=" . ($success ? "deleted" : "error"));
        exit();
    }
}
?>
