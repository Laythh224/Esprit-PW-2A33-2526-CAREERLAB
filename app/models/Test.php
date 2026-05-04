<?php

declare(strict_types=1);

namespace App\Models;

final class Test
{
    private ?int $id;
    private string $date;
    private string $heureDebut;
    private string $heureFin;
    private int $idMetier;
    private int $idQuestion;
    private ?string $userName;
    private ?string $userEmail;

    public function __construct(?int $id, string $date, string $heureDebut, string $heureFin, int $idMetier, int $idQuestion, ?string $userName = null, ?string $userEmail = null)
    {
        $this->id = $id;
        $this->date = $date;
        $this->heureDebut = $heureDebut;
        $this->heureFin = $heureFin;
        $this->idMetier = $idMetier;
        $this->idQuestion = $idQuestion;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getHeureDebut(): string
    {
        return $this->heureDebut;
    }

    public function getHeureFin(): string
    {
        return $this->heureFin;
    }

    public function getIdMetier(): int
    {
        return $this->idMetier;
    }

    public function getIdQuestion(): int
    {
        return $this->idQuestion;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }
}
