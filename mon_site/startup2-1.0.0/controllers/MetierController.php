<?php
// controllers/MetierController.php - Contrôleur pour gérer les métiers

require_once '../config.php';

class MetierController {
    private $metierManager;

    public function __construct() {
        $this->metierManager = new MetierManager();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? 'list';
        $method = $_SERVER['REQUEST_METHOD'];

        // Préparer les données communes
        $data = $this->prepareCommonData();

        switch ($action) {
            case 'list':
                return $this->prepareListView($data);
            case 'create':
                return $method === 'POST' ? $this->handleCreate($data) : $this->prepareCreateForm($data);
            case 'edit':
                return $method === 'POST' ? $this->handleUpdate($data) : $this->prepareEditForm($data);
            case 'delete':
                return $this->handleDelete($data);
            case 'search':
                return $this->prepareSearchView($data);
            case 'stats':
                return $this->prepareStatsView($data);
            default:
                return $this->prepareListView($data);
        }
    }

    private function prepareCommonData() {
        return [
            'messages' => $this->getFlashMessages(),
            'errors' => $this->getFlashErrors(),
            'current_action' => $_GET['action'] ?? 'list',
            'search_query' => $_GET['q'] ?? ''
        ];
    }

    private function getFlashMessages() {
        $messages = [];
        if (isset($_GET['success'])) {
            $messageMap = [
                'create' => 'Métier ajouté avec succès !',
                'update' => 'Métier modifié avec succès !',
                'delete' => 'Métier supprimé avec succès !'
            ];
            $messages[] = $messageMap[$_GET['success']] ?? 'Opération réussie !';
        }
        return $messages;
    }

    private function getFlashErrors() {
        $errors = [];
        if (isset($_GET['error'])) {
            $errorMap = [
                'delete' => 'Erreur lors de la suppression du métier.',
                'validation' => 'Veuillez corriger les erreurs ci-dessous.',
                'save' => 'Erreur lors de la sauvegarde du métier.',
                'not_found' => 'Métier non trouvé.'
            ];
            $errors[] = $errorMap[$_GET['error']] ?? 'Une erreur est survenue.';
        }
        return $errors;
    }

    private function prepareListView($data) {
        $metiers = $this->metierManager->getAllMetiers();
        $data['metiers'] = $metiers;
        $data['total_metiers'] = count($metiers);
        $data['view'] = 'list';
        return $data;
    }

    private function prepareCreateForm($data) {
        $data['mode'] = 'create';
        $data['metier'] = null;
        $data['form_action'] = '?action=create';
        $data['form_method'] = 'POST';
        $data['title'] = 'Ajouter un Métier';
        $data['submit_text'] = 'Ajouter le Métier';
        $data['view'] = 'form';
        return $data;
    }

    private function handleCreate($data) {
        $validation = $this->validateMetierData($_POST);
        if (!$validation['valid']) {
            $data['mode'] = 'create';
            $data['metier'] = null;
            $data['form_action'] = '?action=create';
            $data['form_method'] = 'POST';
            $data['title'] = 'Ajouter un Métier';
            $data['submit_text'] = 'Ajouter le Métier';
            $data['validation_errors'] = $validation['errors'];
            $data['old_input'] = $_POST;
            $data['view'] = 'form';
            return $data;
        }

        $result = $this->metierManager->ajouterMetier(
            $_POST['nom'],
            $_POST['description'] ?? '',
            $_POST['salaire_moyen'] ?? null,
            $_POST['secteur'] ?? ''
        );

        if ($result) {
            header('Location: index.php?success=create');
            exit;
        } else {
            $data['mode'] = 'create';
            $data['metier'] = null;
            $data['form_action'] = '?action=create';
            $data['form_method'] = 'POST';
            $data['title'] = 'Ajouter un Métier';
            $data['submit_text'] = 'Ajouter le Métier';
            $data['errors'][] = 'Erreur lors de la création du métier';
            $data['old_input'] = $_POST;
            $data['view'] = 'form';
            return $data;
        }
    }

    private function prepareEditForm($data) {
        $id = $_GET['id'] ?? 0;
        $metier = $this->metierManager->getMetierById($id);

        if (!$metier) {
            $data['view'] = 'error';
            $data['error_message'] = 'Métier non trouvé.';
            return $data;
        }

        $data['mode'] = 'edit';
        $data['metier'] = $metier;
        $data['form_action'] = '?action=edit&id=' . $id;
        $data['form_method'] = 'POST';
        $data['title'] = 'Modifier un Métier';
        $data['submit_text'] = 'Modifier le Métier';
        $data['view'] = 'form';
        return $data;
    }

    private function handleUpdate($data) {
        $id = $_POST['id'] ?? 0;
        $validation = $this->validateMetierData($_POST);
        if (!$validation['valid']) {
            $metier = $this->metierManager->getMetierById($id);
            $data['mode'] = 'edit';
            $data['metier'] = $metier;
            $data['form_action'] = '?action=edit&id=' . $id;
            $data['form_method'] = 'POST';
            $data['title'] = 'Modifier un Métier';
            $data['submit_text'] = 'Modifier le Métier';
            $data['validation_errors'] = $validation['errors'];
            $data['old_input'] = $_POST;
            $data['view'] = 'form';
            return $data;
        }

        $result = $this->metierManager->modifierMetier(
            $id,
            $_POST['nom'],
            $_POST['description'] ?? '',
            $_POST['salaire_moyen'] ?? null,
            $_POST['secteur'] ?? ''
        );

        if ($result) {
            header('Location: index.php?success=update');
            exit;
        } else {
            $metier = $this->metierManager->getMetierById($id);
            $data['mode'] = 'edit';
            $data['metier'] = $metier;
            $data['form_action'] = '?action=edit&id=' . $id;
            $data['form_method'] = 'POST';
            $data['title'] = 'Modifier un Métier';
            $data['submit_text'] = 'Modifier le Métier';
            $data['errors'][] = 'Erreur lors de la modification du métier';
            $data['old_input'] = $_POST;
            $data['view'] = 'form';
            return $data;
        }
    }

    private function handleDelete($data) {
        $id = $_GET['id'] ?? 0;
        $result = $this->metierManager->supprimerMetier($id);

        if ($result) {
            header('Location: index.php?success=delete');
            exit;
        } else {
            header('Location: index.php?error=delete');
            exit;
        }
    }

    private function prepareSearchView($data) {
        $query = $_GET['q'] ?? '';
        $metiers = $this->metierManager->rechercherMetiers($query);
        $data['metiers'] = $metiers;
        $data['total_metiers'] = count($metiers);
        $data['search_query'] = $query;
        $data['view'] = 'list';
        return $data;
    }

    private function prepareStatsView($data) {
        $stats = $this->metierManager->getStatistiques();
        $data['stats'] = $stats;
        $data['view'] = 'stats';
        return $data;
    }

    private function validateMetierData($data) {
        $errors = [];
        $valid = true;

        if (empty(trim($data['nom'] ?? ''))) {
            $errors['nom'] = 'Le nom du métier est obligatoire.';
            $valid = false;
        }

        if (strlen($data['description'] ?? '') > 500) {
            $errors['description'] = 'La description ne peut pas dépasser 500 caractères.';
            $valid = false;
        }

        $salaire = $data['salaire_moyen'] ?? '';
        if (!empty($salaire) && (!is_numeric($salaire) || $salaire < 0 || $salaire > 1000000)) {
            $errors['salaire_moyen'] = 'Le salaire doit être un nombre positif inférieur à 1 000 000 €.';
            $valid = false;
        }

        return ['valid' => $valid, 'errors' => $errors];
    }
}
            exit;
        } else {
            $metier = $this->metierManager->getMetierById($_POST['id']);
            return [
                'view' => 'form',
                'data' => [
                    'mode' => 'edit',
                    'metier' => $metier,
                    'error' => 'Erreur lors de la modification'
                ]
            ];
        }
    }

    private function deleteMetier() {
        $id = $_GET['id'] ?? 0;
        $result = $this->metierManager->supprimerMetier($id);

        if ($result) {
            header('Location: ../views/metiers.php?success=delete');
            exit;
        } else {
            header('Location: ../views/metiers.php?error=delete');
            exit;
        }
    }

    private function searchMetiers() {
        $query = $_GET['q'] ?? '';
        $metiers = $this->metierManager->rechercherMetiers($query);
        return [
            'view' => 'list',
            'data' => ['metiers' => $metiers, 'search' => $query]
        ];
    }
}
?>