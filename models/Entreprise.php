<?php

class Entreprise
{
    public int $id = 0;
    public string $nomEntreprise = '';
    public string $email = '';
    public string $telephone = '';
    public string $adresse = '';
    public string $ville = '';
    public string $secteur = '';
    public string $description = '';
    public string $siteWeb = '';

    public static function fromArray(array $data): self
    {
        $item = new self();
        $item->id = (int) ($data['id'] ?? 0);
        $item->nomEntreprise = trim((string) ($data['nom_entreprise'] ?? ''));
        $item->email = trim((string) ($data['email'] ?? ''));
        $item->telephone = trim((string) ($data['telephone'] ?? ''));
        $item->adresse = trim((string) ($data['adresse'] ?? ''));
        $item->ville = trim((string) ($data['ville'] ?? ''));
        $item->secteur = trim((string) ($data['secteur'] ?? ''));
        $item->description = trim((string) ($data['description'] ?? ''));
        $item->siteWeb = trim((string) ($data['site_web'] ?? ''));

        return $item;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom_entreprise' => $this->nomEntreprise,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'adresse' => $this->adresse,
            'ville' => $this->ville,
            'secteur' => $this->secteur,
            'description' => $this->description,
            'site_web' => $this->siteWeb,
        ];
    }
}
