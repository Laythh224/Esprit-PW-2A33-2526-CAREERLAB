<?php

class SignupRegistrationService
{
    private PDO $conn;
    private AccountRegistry $accountRegistry;

    public function __construct(PDO $conn, AccountRegistry $accountRegistry)
    {
        $this->conn = $conn;
        $this->accountRegistry = $accountRegistry;
    }

    public function registerUser(UserRepositoryInterface $repository, string $email, array $payload): void
    {
        $this->runTransactionalReservation('utilisateur', $email, function () use ($repository, $payload): void {
            $repository->create($payload);
        });
    }

    public function registerFormateur(FormateurRepositoryInterface $repository, string $email, array $payload): void
    {
        $this->runTransactionalReservation('formateur', $email, function () use ($repository, $payload): void {
            $repository->create($payload);
        });
    }

    public function registerEntreprise(EntrepriseRepositoryInterface $repository, string $email, array $payload): void
    {
        $this->runTransactionalReservation('entreprise', $email, function () use ($repository, $payload): void {
            $repository->create($payload);
        });
    }

    private function runTransactionalReservation(string $accountType, string $email, callable $operation): void
    {
        $this->conn->beginTransaction();

        try {
            $this->accountRegistry->reserveGlobalEmail($email, $accountType);
            $operation();
            $this->conn->commit();
        } catch (Throwable $exception) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw $exception;
        }
    }
}