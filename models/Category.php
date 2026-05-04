<?php
class Category {
    private ?int $id = null;
    private ?string $name = null;
    private ?string $created_at = null;
    
    public function __construct(array $data = []) {
        $this->hydrate($data);
    }
    
    public function hydrate(array $data): void {
        if (isset($data['id'])) $this->id = (int)$data['id'];
        if (isset($data['name'])) $this->name = trim($data['name']);
        if (isset($data['created_at'])) $this->created_at = $data['created_at'];
    }
    
    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    
    public function setName(string $name): self {
        if (empty(trim($name))) {
            throw new Exception("Le nom de la catégorie ne peut pas être vide");
        }
        $this->name = trim($name);
        return $this;
    }
}
?>