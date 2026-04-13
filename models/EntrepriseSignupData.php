<?php

class EntrepriseSignupData extends AbstractSignupData
{
    private string $nomEntreprise;
    private string $email;
    private string $telephone;
    private string $adresse;
    private string $ville;
    private string $secteur;
    private string $description;
    private string $siteWeb;
    private string $password;

    private function __construct(
        string $nomEntreprise,
        string $email,
        string $telephone,
        string $adresse,
        string $ville,
        string $secteur,
        string $description,
        string $siteWeb,
        string $password
    ) {
        $this->nomEntreprise = $nomEntreprise;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->secteur = $secteur;
        $this->description = $description;
        $this->siteWeb = $siteWeb;
        $this->password = $password;
    }

    public static function fromPost(array $post): self
    {
        return new self(
            self::text($post, 'nom'),
            self::text($post, 'email'),
            self::text($post, 'telephone'),
            self::text($post, 'adresse'),
            self::text($post, 'ville'),
            self::text($post, 'secteur'),
            self::text($post, 'description'),
            self::text($post, 'site'),
            self::raw($post, 'password')
        );
    }

    public function requiredFields(): array
    {
        return [$this->nomEntreprise, $this->email, $this->password];
    }

    public function email(): string
    {
        return $this->email;
    }

    public function siteWeb(): string
    {
        return $this->siteWeb;
    }

    public function toRepositoryPayload(): array
    {
        return [
            'nom_entreprise' => $this->nomEntreprise,
            'email' => $this->email,
            'password' => $this->password,
            'telephone' => $this->telephone,
            'adresse' => $this->adresse,
            'ville' => $this->ville,
            'secteur' => $this->secteur,
            'description' => $this->description,
            'site_web' => $this->siteWeb,
        ];
    }
}