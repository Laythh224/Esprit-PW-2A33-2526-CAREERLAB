<?php

class User
{
    public int $id = 0;
    public string $nom = '';
    public string $prenom = '';
    public string $email = '';
    public string $telephone = '';
    public string $niveauEtude = '';
    public string $domaine = '';

    public static function fromArray(array $data): self
    {
        $user = new self();
        $user->id = (int) ($data['id'] ?? 0);
        $user->nom = trim((string) ($data['nom'] ?? ''));
        $user->prenom = trim((string) ($data['prenom'] ?? ''));
        $user->email = trim((string) ($data['email'] ?? ''));
        $user->telephone = trim((string) ($data['telephone'] ?? ''));
        $user->niveauEtude = trim((string) ($data['niveau_etude'] ?? ''));
        $user->domaine = trim((string) ($data['domaine'] ?? ''));

        return $user;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'niveau_etude' => $this->niveauEtude,
            'domaine' => $this->domaine,
        ];
    }
}
