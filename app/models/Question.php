<?php

declare(strict_types=1);

namespace App\Models;

final class Question
{
    private ?int $id;
    private string $texte;
    private int $idMetier;

    public function __construct(?int $id, string $texte, int $idMetier)
    {
        $this->id = $id;
        $this->texte = $texte;
        $this->idMetier = $idMetier;
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

    public function getIdMetier(): int
    {
        return $this->idMetier;
    }

    public function setIdMetier(int $idMetier): void
    {
        $this->idMetier = $idMetier;
    }
}
