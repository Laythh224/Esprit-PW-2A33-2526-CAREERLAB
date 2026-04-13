<?php

class AccountRegistry
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function emailExistsInAnyAccount(string $email): bool
    {
        $requete = $this->connection->prepare(
            "SELECT 1
             FROM (
                 SELECT email FROM global_emails WHERE email = ?
                 UNION ALL
                 SELECT email FROM utilisateur WHERE email = ?
                 UNION ALL
                 SELECT email FROM formateur WHERE email = ?
                 UNION ALL
                 SELECT email FROM entreprise WHERE email = ?
             ) AS comptes
             LIMIT 1"
        );

        $requete->execute([$email, $email, $email, $email]);

        return $requete->fetchColumn() !== false;
    }

    public function reserveGlobalEmail(string $email, string $accountType): void
    {
        $requete = $this->connection->prepare(
            "INSERT INTO global_emails (email, account_type) VALUES (?, ?)"
        );

        $requete->execute([$email, $accountType]);
    }
}
