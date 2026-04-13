<?php

class UserSignupData extends AbstractSignupData
{
    private string $nom;
    private string $prenom;
    private string $email;
    private string $telephone;
    private string $niveauEtude;
    private string $domaine;
    private string $competences;
    private string $password;

    private function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $telephone,
        string $niveauEtude,
        string $domaine,
        string $competences,
        string $password
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->niveauEtude = $niveauEtude;
        $this->domaine = $domaine;
        $this->competences = $competences;
        $this->password = $password;
    }

    public static function fromPost(array $post): self
    {
        return new self(
            self::text($post, 'nom'),
            self::text($post, 'prenom'),
            self::text($post, 'email'),
            self::text($post, 'telephone'),
            self::text($post, 'niveau'),
            self::text($post, 'domaine'),
            self::text($post, 'competences'),
            self::raw($post, 'password')
        );
    }

    public function requiredFields(): array
    {
        return [$this->nom, $this->prenom, $this->email, $this->password];
    }

    public function email(): string
    {
        return $this->email;
    }

    public function toRepositoryPayload(string $cvPath): array
    {
        return [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'password' => $this->password,
            'telephone' => $this->telephone,
            'niveau_etude' => $this->niveauEtude,
            'domaine' => $this->domaine,
            'competences' => $this->competences,
            'cv' => $cvPath,
        ];
    }
}
