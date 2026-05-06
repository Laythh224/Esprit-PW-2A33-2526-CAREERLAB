<?php

class FormateurEntity
{
    private string $nom = '';
    private string $cv = '';
        public function getCv(): string
        {
            return $this->cv;
        }

        public function setCv(string $cv): self
        {
            $this->cv = $cv;
            return $this;
        }
    private string $prenom = '';
    private string $sexe = '';
    private string $email = '';
    private string $telephone = '';
    private string $password = '';
    private string $confirmPassword = '';
    private string $specialite = '';
    private string $diplomes = '';
    private int $diplomeCount = 0;
    private string $experience = '';

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getSexe(): string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;
        return $this;
    }

    public function getSpecialite(): string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;
        return $this;
    }

    public function getDiplomes(): string
    {
        return $this->diplomes;
    }

    public function setDiplomes(string $diplomes): self
    {
        $this->diplomes = $diplomes;
        return $this;
    }

    public function getDiplomeCount(): int
    {
        return $this->diplomeCount;
    }

    public function setDiplomeCount(int $diplomeCount): self
    {
        $this->diplomeCount = $diplomeCount;
        return $this;
    }

    public function getExperience(): string
    {
        return $this->experience;
    }

    public function setExperience(string $experience): self
    {
        $this->experience = $experience;
        return $this;
    }
}

