<?php

class SignupValidator
{
    public function assertRequiredFields(array $fields): void
    {
        foreach ($fields as $value) {
            if (trim((string) $value) === '') {
                throw new RuntimeException('Veuillez remplir tous les champs obligatoires.');
            }
        }
    }

    public function assertValidEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Adresse email invalide.');
        }
    }

    public function assertUniqueEmail(AccountRegistry $accountRegistry, string $email): void
    {
        if ($accountRegistry->emailExistsInAnyAccount($email)) {
            throw new RuntimeException('Cet email est deja utilise sur le site.');
        }
    }

    public function assertValidOptionalUrl(string $url): void
    {
        if ($url !== '' && !filter_var($url, FILTER_VALIDATE_URL)) {
            throw new RuntimeException('Le site web doit etre une URL valide.');
        }
    }
}