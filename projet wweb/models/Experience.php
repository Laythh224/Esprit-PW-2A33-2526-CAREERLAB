<?php

class Experience {
    private $id;
    private $nom;
    private $prenom;
    private $niveau;
    private $description;

    public function __construct(array $data = []) {
        if (!empty($data['id'])) $this->id = (int)$data['id'];
        if (!empty($data['nom'])) $this->nom = $data['nom'];
        if (!empty($data['prenom'])) $this->prenom = $data['prenom'];
        if (!empty($data['niveau'])) $this->niveau = $data['niveau'];
        if (!empty($data['description'])) $this->description = $data['description'];
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function getPrenom(): ?string { return $this->prenom; }
    public function getNiveau(): ?string { return $this->niveau; }
    public function getDescription(): ?string { return $this->description; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setNom(string $nom): void { $this->nom = $nom; }
    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }
    public function setNiveau(string $niveau): void { $this->niveau = $niveau; }
    public function setDescription(?string $description): void { $this->description = $description; }
}
?>
