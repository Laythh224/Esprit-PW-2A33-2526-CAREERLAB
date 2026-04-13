<?php

class Formateur
{
    public int $id = 0;
    public string $nom = '';
    public string $prenom = '';
    public string $email = '';
    public string $telephone = '';
    public string $specialite = '';
    public string $diplomes = '';
    public string $experience = '';

    public static function fromArray(array $data): self
    {
        $item = new self();
        $item->id = (int) ($data['id'] ?? 0);
        $item->nom = trim((string) ($data['nom'] ?? ''));
        $item->prenom = trim((string) ($data['prenom'] ?? ''));
        $item->email = trim((string) ($data['email'] ?? ''));
        $item->telephone = trim((string) ($data['telephone'] ?? ''));
        $item->specialite = trim((string) ($data['specialite'] ?? ''));
        $item->diplomes = trim((string) ($data['diplomes'] ?? ''));
        $item->experience = trim((string) ($data['experience'] ?? ''));

        return $item;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'specialite' => $this->specialite,
            'diplomes' => $this->diplomes,
            'experience' => $this->experience,
        ];
    }
}
