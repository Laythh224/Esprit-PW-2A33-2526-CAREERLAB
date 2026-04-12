<?php
require_once __DIR__ . '/../models/Candidature.php';

class CandidatureController {

    public function postuler(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('offres.php');
            return;
        }

        $nom    = $this->clean($_POST['nom'] ?? '');
        $email  = $this->clean($_POST['email'] ?? '');

        if (empty($nom) || empty($email)) {
            $this->redirect('offres.php?error=champs_requis');
            return;
        }

        // Gestion upload CV
        $cvFilename = null;
        if (!empty($_FILES['cv']['name'])) {
            $uploadDir = __DIR__ . '/../uploads/cv/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['pdf', 'doc', 'docx'])) {
                $this->redirect('offres.php?error=fichier_invalide');
                return;
            }
            $cvFilename = uniqid('cv_') . '.' . $ext;
            move_uploaded_file($_FILES['cv']['tmp_name'], $uploadDir . $cvFilename);
        }

        $model = new Candidature();
        $data  = [
            'offre_id'      => $_POST['offre_id'] ?? null,
            'type_offre'    => $this->clean($_POST['type_offre'] ?? ''),
            'nom'           => $nom,
            'email'         => $email,
            'telephone'     => $this->clean($_POST['telephone'] ?? ''),
            'niveau_etudes' => $this->clean($_POST['niveau_etudes'] ?? ''),
            'motivation'    => $this->clean($_POST['motivation'] ?? ''),
            'cv_filename'   => $cvFilename,
        ];

        if ($model->ajouter($data)) {
            $this->redirect('offres.php?success=candidature');
        } else {
            $this->redirect('offres.php?error=insertion_echouee');
        }
    }

    private function clean(string $v): string {
        return htmlspecialchars(strip_tags(trim($v)));
    }

    private function redirect(string $url): void {
        header("Location: $url");
        exit();
    }
}
?>
