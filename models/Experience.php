<?php

class Experience {
    private $id;
    private $niveau;
    private $description;

    public function __construct(array $data = []) {
        if (!empty($data['id'])) $this->id = (int)$data['id'];
        if (!empty($data['niveau'])) $this->niveau = $data['niveau'];
        if (!empty($data['description'])) $this->description = $data['description'];
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNiveau(): ?string { return $this->niveau; }
    public function getDescription(): ?string { return $this->description; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setNiveau(string $niveau): void { $this->niveau = $niveau; }
    public function setDescription(?string $description): void { $this->description = $description; }
}
?>
