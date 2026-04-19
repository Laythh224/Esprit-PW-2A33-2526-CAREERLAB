<?php

class OpportuniteTravail {
    private $id;
    private $titre;
    private $entreprise;
    private $description;
    private $localisation;
    private $type_contrat;
    private $experience_id;
    private $domaine;
    private $date_expiration;

    public function __construct(array $data = []) {
        if (!empty($data['id'])) $this->id = (int)$data['id'];
        if (!empty($data['titre'])) $this->titre = $data['titre'];
        if (!empty($data['entreprise'])) $this->entreprise = $data['entreprise'];
        if (!empty($data['description'])) $this->description = $data['description'];
        if (!empty($data['localisation'])) $this->localisation = $data['localisation'];
        if (!empty($data['type_contrat'])) $this->type_contrat = $data['type_contrat'];
        if (!empty($data['experience_id'])) $this->experience_id = (int)$data['experience_id'];
        if (!empty($data['domaine'])) $this->domaine = $data['domaine'];
        if (!empty($data['date_expiration'])) $this->date_expiration = $data['date_expiration'];
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getTitre(): ?string { return $this->titre; }
    public function getEntreprise(): ?string { return $this->entreprise; }
    public function getDescription(): ?string { return $this->description; }
    public function getLocalisation(): ?string { return $this->localisation; }
    public function getTypeContrat(): ?string { return $this->type_contrat; }
    public function getExperienceId(): ?int { return $this->experience_id; }
    public function getDomaine(): ?string { return $this->domaine; }
    public function getDateExpiration(): ?string { return $this->date_expiration; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setTitre(string $titre): void { $this->titre = $titre; }
    public function setEntreprise(?string $entreprise): void { $this->entreprise = $entreprise; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function setLocalisation(?string $localisation): void { $this->localisation = $localisation; }
    public function setTypeContrat(?string $type_contrat): void { $this->type_contrat = $type_contrat; }
    public function setExperienceId(?int $experience_id): void { $this->experience_id = $experience_id; }
    public function setDomaine(?string $domaine): void { $this->domaine = $domaine; }
    public function setDateExpiration(?string $date_expiration): void { $this->date_expiration = $date_expiration; }
}
?>
