<?php

class InscriptionFormateurController
{
    private InscriptionFormateurModel $inscriptionModel;
    private UserModel $userModel;
    private FormateurModel $formateurModel;

    public function __construct(
        InscriptionFormateurModel $inscriptionModel, 
        UserModel $userModel, 
        FormateurModel $formateurModel
    ) {
        $this->inscriptionModel = $inscriptionModel;
        $this->userModel = $userModel;
        $this->formateurModel = $formateurModel;
    }

    public function index(): void
    {
        $message = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'add') {
                // Validation côté serveur sans s'appuyer sur HTML5
                $id_user = trim((string)($_POST['id_user'] ?? ''));
                $id_formateur = trim((string)($_POST['id_formateur'] ?? ''));

                if ($id_user === '' || $id_formateur === '') {
                    $message = "Erreur : Veuillez sélectionner obligatoirement un utilisateur et un formateur.";
                } else {
                    try {
                        $this->inscriptionModel->create((int)$id_user, (int)$id_formateur);
                        $message = "Inscription formateur ajoutée avec succès.";
                    } catch (Exception $e) {
                        $message = $e->getMessage();
                    }
                }
            } elseif ($action === 'delete') {
                $id_user = (int)($_POST['id_user'] ?? 0);
                $id_formateur = (int)($_POST['id_formateur'] ?? 0);
                if ($id_user > 0 && $id_formateur > 0) {
                    $this->inscriptionModel->delete($id_user, $id_formateur);
                    $message = "Inscription formateur supprimée avec succès.";
                }
            }
        }

        $utilisateurs = $this->userModel->all();
        $formateurs = $this->formateurModel->all();
        $inscriptions = $this->inscriptionModel->all();

        require __DIR__ . '/../Views/inscription-formateur.view.php';
    }
}
