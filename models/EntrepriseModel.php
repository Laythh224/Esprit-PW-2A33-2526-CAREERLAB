<?php

class EntrepriseModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function all(): array
    {
        $stmt = $this->conn->prepare('SELECT id, nom_entreprise, email, telephone, adresse, ville, secteur, description, site_web FROM entreprise ORDER BY id DESC');
        $stmt->execute();

        return $stmt->fetchAll() ?: [];
    }

    public function count(): int
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) AS total FROM entreprise');
        $stmt->execute();

        return (int) ($stmt->fetchColumn() ?: 0);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM entreprise WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $account = $stmt->fetch();

        return $account !== false ? $account : null;
    }

    public function registerFromSignup(array $post): void
    {
        $entity = $this->hydrateSignupEntity($post);
        $errors = $this->validateSignup($entity);

        if ($this->hasValidationErrors($errors)) {
            throw new RuntimeException($this->firstValidationError($errors));
        }

        $this->registerFromEntity($entity);
    }

    public function validateSignup(EntrepriseEntity $entity): array
    {
        $errors = [
            'nom' => '',
            'email' => '',
            'telephone' => '',
            'adresse' => '',
            'ville' => '',
            'secteur' => '',
            'description' => '',
            'site' => '',
            'password' => '',
            'confirm_password' => '',
        ];

        if ($entity->getNom() === '') {
            $errors['nom'] = "Le nom de l'entreprise est obligatoire.";
        }
        if ($entity->getEmail() === '') {
            $errors['email'] = "L'email est obligatoire.";
        } elseif (!filter_var($entity->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Veuillez saisir une adresse email valide.';
        } elseif ($this->emailExistsInAnyAccount($entity->getEmail())) {
            $errors['email'] = 'Cet email est deja utilise.';
        }
        if ($entity->getTelephone() === '') {
            $errors['telephone'] = 'Le telephone est obligatoire.';
        } elseif (!preg_match('/^\d{8}$/', $entity->getTelephone())) {
            $errors['telephone'] = 'Le telephone doit contenir 8 chiffres.';
        }
        if ($entity->getAdresse() === '') {
            $errors['adresse'] = "L'adresse est obligatoire.";
        }
        if ($entity->getVille() === '') {
            $errors['ville'] = 'La ville est obligatoire.';
        }
        if ($entity->getSecteur() === '') {
            $errors['secteur'] = "Le secteur d'activite est obligatoire.";
        }
        if ($entity->getDescription() === '') {
            $errors['description'] = 'La description est obligatoire.';
        }
        if ($entity->getPassword() === '') {
            $errors['password'] = 'Le mot de passe est obligatoire.';
        } elseif (mb_strlen($entity->getPassword()) < 6) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 6 caracteres.';
        }
        if ($entity->getConfirmPassword() === '') {
            $errors['confirm_password'] = 'La confirmation du mot de passe est obligatoire.';
        } elseif ($entity->getPassword() !== '' && $entity->getConfirmPassword() !== $entity->getPassword()) {
            $errors['confirm_password'] = 'La confirmation ne correspond pas au mot de passe.';
        }
        if ($entity->getSite() !== '' && !filter_var($entity->getSite(), FILTER_VALIDATE_URL)) {
            $errors['site'] = 'Le site web doit etre une URL valide (https://...).';
        }

        return $errors;
    }

    public function registerFromEntity(EntrepriseEntity $entity): void
    {
        if ($this->emailExistsInAnyAccount($entity->getEmail())) {
            throw new RuntimeException('Cet email est deja utilise sur le site.');
        }

        $this->create([
            'nom_entreprise' => $entity->getNom(),
            'email' => $entity->getEmail(),
            'password' => $entity->getPassword(),
            'telephone' => $entity->getTelephone(),
            'adresse' => $entity->getAdresse(),
            'ville' => $entity->getVille(),
            'secteur' => $entity->getSecteur(),
            'description' => $entity->getDescription(),
            'site_web' => $entity->getSite(),
        ]);
    }

    public function create(array $data): void
    {
        $nomEntreprise = trim((string) ($data['nom_entreprise'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $telephone = trim((string) ($data['telephone'] ?? ''));
        $adresse = trim((string) ($data['adresse'] ?? ''));
        $ville = trim((string) ($data['ville'] ?? ''));
        $secteur = trim((string) ($data['secteur'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));
        $siteWeb = trim((string) ($data['site_web'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        if ($nomEntreprise === '' || $email === '' || $password === '') {
            throw new InvalidArgumentException('Nom entreprise, email et mot de passe sont obligatoires.');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare('INSERT INTO entreprise (nom_entreprise, email, password, telephone, adresse, ville, secteur, description, site_web) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nomEntreprise, $email, $passwordHash, $telephone, $adresse, $ville, $secteur, $description, $siteWeb]);
    }

    public function update(array $data): void
    {
        $id = (int) ($data['id'] ?? 0);
        $nomEntreprise = trim((string) ($data['nom_entreprise'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $telephone = trim((string) ($data['telephone'] ?? ''));
        $adresse = trim((string) ($data['adresse'] ?? ''));
        $ville = trim((string) ($data['ville'] ?? ''));
        $secteur = trim((string) ($data['secteur'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));
        $siteWeb = trim((string) ($data['site_web'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        if ($id <= 0 || $nomEntreprise === '' || $email === '') {
            throw new InvalidArgumentException('Données de mise à jour invalides.');
        }

        if ($password !== '') {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare('UPDATE entreprise SET nom_entreprise = ?, email = ?, telephone = ?, adresse = ?, ville = ?, secteur = ?, description = ?, site_web = ?, password = ? WHERE id = ?');
            $stmt->execute([$nomEntreprise, $email, $telephone, $adresse, $ville, $secteur, $description, $siteWeb, $passwordHash, $id]);
            return;
        }

        $stmt = $this->conn->prepare('UPDATE entreprise SET nom_entreprise = ?, email = ?, telephone = ?, adresse = ?, ville = ?, secteur = ?, description = ?, site_web = ? WHERE id = ?');
        $stmt->execute([$nomEntreprise, $email, $telephone, $adresse, $ville, $secteur, $description, $siteWeb, $id]);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID invalide.');
        }

        $stmt = $this->conn->prepare('DELETE FROM entreprise WHERE id = ?');
        $stmt->execute([$id]);
    }

    private function emailExistsInAnyAccount(string $email): bool
    {
        $stmt = $this->conn->prepare(
            'SELECT 1
             FROM (
                SELECT email FROM utilisateur WHERE email = ?
                UNION ALL
                SELECT email FROM formateur WHERE email = ?
                UNION ALL
                SELECT email FROM entreprise WHERE email = ?
             ) AS comptes
             LIMIT 1'
        );
        $stmt->execute([$email, $email, $email]);

        return $stmt->fetchColumn() !== false;
    }

    private function hydrateSignupEntity(array $post): EntrepriseEntity
    {
        $entity = new EntrepriseEntity();

        $entity
            ->setNom(trim((string) ($post['nom'] ?? '')))
            ->setEmail(trim((string) ($post['email'] ?? '')))
            ->setTelephone(trim((string) ($post['telephone'] ?? '')))
            ->setAdresse(trim((string) ($post['adresse'] ?? '')))
            ->setVille(trim((string) ($post['ville'] ?? '')))
            ->setSecteur(trim((string) ($post['secteur'] ?? '')))
            ->setDescription(trim((string) ($post['description'] ?? '')))
            ->setSite(trim((string) ($post['site'] ?? '')))
            ->setPassword((string) ($post['password'] ?? ''))
            ->setConfirmPassword((string) ($post['confirm_password'] ?? ''));

        return $entity;
    }

    private function hasValidationErrors(array $errors): bool
    {
        foreach ($errors as $error) {
            if ((string) $error !== '') {
                return true;
            }
        }

        return false;
    }

    private function firstValidationError(array $errors): string
    {
        foreach ($errors as $error) {
            if ((string) $error !== '') {
                return (string) $error;
            }
        }

        return 'Le formulaire contient des erreurs.';
    }
}
