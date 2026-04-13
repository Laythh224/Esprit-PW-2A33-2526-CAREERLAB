<?php

class FormateurSignupData extends AbstractSignupData
{
    private string $nom;
    private string $prenom;
    private string $email;
    private string $telephone;
    private string $specialite;
    private string $diplomes;
    private string $experience;
    private string $password;

    private function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $telephone,
        string $specialite,
        string $diplomes,
        string $experience,
        string $password
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->specialite = $specialite;
        $this->diplomes = $diplomes;
        $this->experience = $experience;
        $this->password = $password;
    }

    public static function fromPost(array $post): self
    {
        return new self(
            self::text($post, 'nom'),
            self::text($post, 'prenom'),
            self::text($post, 'email'),
            self::text($post, 'telephone'),
            self::text($post, 'specialite'),
            self::text($post, 'diplomes'),
            self::text($post, 'experience'),
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
            'specialite' => $this->specialite,
            'diplomes' => $this->diplomes,
            'experience' => $this->experience,
            'cv' => $cvPath,
        ];
    }
}
