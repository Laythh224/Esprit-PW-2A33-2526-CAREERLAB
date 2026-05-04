<?php
class Metier {
    private ?int $id = null;
    private ?string $session_id = null;
    private ?string $title = null;
    private ?string $description = null;
    private ?string $competences = null;
    private ?string $specialites = null;
    private ?float $salaire = null;
    private ?int $category_id = null;
    private ?string $category_name = null;
    private ?string $created_at = null;
    private ?string $updated_at = null;
    private ?int $views = 0;
    private ?int $likes = 0;
    private ?int $comment_count = 0;
    
    public function __construct(array $data = []) {
        $this->hydrate($data);
    }
    
    public function hydrate(array $data): void {
        if (isset($data['id'])) $this->id = (int)$data['id'];
        if (isset($data['session_id'])) $this->session_id = $data['session_id'];
        if (isset($data['title'])) $this->title = trim($data['title']);
        if (isset($data['description'])) $this->description = trim($data['description']);
        if (isset($data['competences'])) $this->competences = trim($data['competences']);
        if (isset($data['specialites'])) $this->specialites = trim($data['specialites']);
        if (isset($data['salaire'])) $this->salaire = (float)$data['salaire'];
        if (isset($data['category_id'])) $this->category_id = (int)$data['category_id'];
        if (isset($data['category_name'])) $this->category_name = $data['category_name'];
        if (isset($data['created_at'])) $this->created_at = $data['created_at'];
        if (isset($data['updated_at'])) $this->updated_at = $data['updated_at'];
        if (isset($data['views'])) $this->views = (int)$data['views'];
        if (isset($data['likes'])) $this->likes = (int)$data['likes'];
        if (isset($data['comment_count'])) $this->comment_count = (int)$data['comment_count'];
    }
    
    // Getters
    public function getId(): ?int { return $this->id; }
    public function getSessionId(): ?string { return $this->session_id; }
    public function getTitle(): ?string { return $this->title; }
    public function getDescription(): ?string { return $this->description; }
    public function getCompetences(): ?string { return $this->competences; }
    public function getSpecialites(): ?string { return $this->specialites; }
    public function getSalaire(): ?float { return $this->salaire; }
    public function getCategoryId(): ?int { return $this->category_id; }
    public function getCategoryName(): ?string { return $this->category_name; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function getUpdatedAt(): ?string { return $this->updated_at; }
    public function getViews(): ?int { return $this->views; }
    public function getLikes(): ?int { return $this->likes; }
    public function getCommentCount(): ?int { return $this->comment_count; }
    
    // Setters
    public function setTitle(string $title): self {
        if (empty(trim($title))) throw new Exception("Le titre ne peut pas être vide");
        $this->title = trim($title);
        return $this;
    }
    
    public function setDescription(?string $description): self {
        $this->description = $description ? trim($description) : null;
        return $this;
    }
    
    public function setCompetences(?string $competences): self {
        $this->competences = $competences ? trim($competences) : null;
        return $this;
    }
    
    public function setSpecialites(?string $specialites): self {
        $this->specialites = $specialites ? trim($specialites) : null;
        return $this;
    }
    
    public function setSalaire($salaire): self {
        if ($salaire !== null && $salaire !== '') {
            if (!is_numeric($salaire)) throw new Exception("Le salaire doit être un nombre");
            if ($salaire < 0) throw new Exception("Le salaire ne peut pas être négatif");
            $this->salaire = (float)$salaire;
        } else {
            $this->salaire = null;
        }
        return $this;
    }
    
    public function setCategoryId(?int $category_id): self {
        $this->category_id = $category_id;
        return $this;
    }
    
    public function setSessionId(?string $session_id): self {
        $this->session_id = $session_id;
        return $this;
    }
    
    public function setViews(?int $views): self {
        $this->views = $views;
        return $this;
    }
    
    public function setLikes(?int $likes): self {
        $this->likes = $likes;
        return $this;
    }
    
    public function setCommentCount(?int $comment_count): self {
        $this->comment_count = $comment_count;
        return $this;
    }
}
?>