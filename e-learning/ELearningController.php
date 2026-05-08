<?php

declare(strict_types=1);

/**
 * Module e-learning : catalogue formations + sessions (tables formation, session).
 */
class ELearningController
{
    public function __construct(private PDO $conn)
    {
    }

    public function index(): void
    {
        $formations = $this->loadFormations();
        $sessions = $this->loadSessions();
        $partialErrors = [];
        if ($formations === null) {
            $partialErrors[] = 'catalogue des formations';
            $formations = [];
        }
        if ($sessions === null) {
            $partialErrors[] = 'planning des sessions';
            $sessions = [];
        }

        require __DIR__ . '/views/catalog.view.php';
    }

    /** @return array<int, array<string, mixed>>|null null if query failed */
    private function loadFormations(): ?array
    {
        try {
            $stmt = $this->conn->query(
                'SELECT nom_formation, specialite, description, niveau FROM formation ORDER BY nom_formation ASC'
            );

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log('[ELearningController] formations: ' . $e->getMessage());

            return null;
        }
    }

    /** @return array<int, array<string, mixed>>|null */
    private function loadSessions(): ?array
    {
        try {
            $stmt = $this->conn->query(
                'SELECT id, nom_formation, type, lien, duree_online, adresse, salle, duree_presentiel, date_debut, date_fin, nb_place
                 FROM `session`
                 ORDER BY date_debut ASC'
            );

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log('[ELearningController] sessions: ' . $e->getMessage());

            return null;
        }
    }
}
