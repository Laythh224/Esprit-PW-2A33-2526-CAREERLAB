<?php

class EntrepriseEntity
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
    private string $email = '';
    private string $telephone = '';
    private string $adresse = '';
    private string $ville = '';
    private string $secteur = '';
    private string $description = '';
    private string $site = '';
    private string $codeFiscal = '';
    private string $password = '';
    private string $confirmPassword = '';

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
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

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getVille(): string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;
        return $this;
    }

    public function getSecteur(): string
    {
        return $this->secteur;
    }

    public function setSecteur(string $secteur): self
    {
        $this->secteur = $secteur;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getSite(): string
    {
        return $this->site;
    }

    public function setSite(string $site): self
    {
        $this->site = $site;
        return $this;
    }

    public function getCodeFiscal(): string
    {
        return $this->codeFiscal;
    }

    public function setCodeFiscal(string $codeFiscal): self
    {
        $this->codeFiscal = $codeFiscal;
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
}

