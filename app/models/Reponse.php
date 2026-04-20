<?php

declare(strict_types=1);

namespace App\Models;

final class Reponse
{
    private ?int $id;
    private string $texte;
    private bool $estCorrecte;
    private int $idQuestion;

    public function __construct(?int $id, string $texte, bool $estCorrecte, int $idQuestion)
    {
        $this->id = $id;
        $this->texte = $texte;
        $this->estCorrecte = $estCorrecte;
        $this->idQuestion = $idQuestion;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTexte(): string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): void
    {
        $this->texte = $texte;
    }

    public function isEstCorrecte(): bool
    {
        return $this->estCorrecte;
    }

    public function setEstCorrecte(bool $estCorrecte): void
    {
        $this->estCorrecte = $estCorrecte;
    }

    public function getIdQuestion(): int
    {
        return $this->idQuestion;
    }

    public function setIdQuestion(int $idQuestion): void
    {
        $this->idQuestion = $idQuestion;
    }
}
