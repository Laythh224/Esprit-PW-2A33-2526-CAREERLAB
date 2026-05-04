<?php

class InscriptionEntrepriseController
{
    private InscriptionEntrepriseModel $inscriptionModel;
    private UserModel $userModel;
    private EntrepriseModel $entrepriseModel;

    public function __construct(
        InscriptionEntrepriseModel $inscriptionModel, 
        UserModel $userModel, 
        EntrepriseModel $entrepriseModel
    ) {
        $this->inscriptionModel = $inscriptionModel;
        $this->userModel = $userModel;
        $this->entrepriseModel = $entrepriseModel;
    }

    public function index(): void
    {
        $message = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'add') {
                // Validation côté serveur sans s'appuyer sur HTML5
                $id_user = trim((string)($_POST['id_user'] ?? ''));
                $id_entreprise = trim((string)($_POST['id_entreprise'] ?? ''));

                if ($id_user === '' || $id_entreprise === '') {
                    $message = "Erreur : Veuillez sélectionner obligatoirement un utilisateur et une entreprise.";
                } else {
                    try {
                        $this->inscriptionModel->create((int)$id_user, (int)$id_entreprise);
                        $message = "Inscription entreprise ajoutée avec succès.";
                    } catch (Exception $e) {
                        $message = $e->getMessage();
                    }
                }
            } elseif ($action === 'delete') {
                $id_user = (int)($_POST['id_user'] ?? 0);
                $id_entreprise = (int)($_POST['id_entreprise'] ?? 0);
                if ($id_user > 0 && $id_entreprise > 0) {
                    $this->inscriptionModel->delete($id_user, $id_entreprise);
                    $message = "Inscription entreprise supprimée avec succès.";
                }
            }
        }

        $utilisateurs = $this->userModel->all();
        $entreprises = $this->entrepriseModel->all();
        $inscriptions = $this->inscriptionModel->all();

        require __DIR__ . '/../Views/inscription-entreprise.view.php';
    }
}
