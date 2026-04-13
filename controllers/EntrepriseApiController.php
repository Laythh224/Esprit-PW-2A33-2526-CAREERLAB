<?php

class EntrepriseApiController extends AbstractApiController
{
    private EntrepriseRepository $repository;

    public function __construct(EntrepriseRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function collectionKey(): string
    {
        return 'entreprises';
    }

    protected function allItems(): array
    {
        return $this->repository->all();
    }

    protected function countItems(): int
    {
        return $this->repository->countAll();
    }

    protected function createItem(array $input): void
    {
        $this->repository->create($input);
    }

    protected function updateItem(array $input): void
    {
        $this->repository->update($input);
    }

    protected function deleteItem(int $id): void
    {
        $this->repository->delete($id);
    }

    protected function createMessage(): string
    {
        return 'Entreprise ajoutée avec succès.';
    }

    protected function updateMessage(): string
    {
        return 'Entreprise modifiée avec succès.';
    }

    protected function deleteMessage(): string
    {
        return 'Entreprise supprimée avec succès.';
    }

    protected function decodeJsonInput(): ?array
    {
        $rawInput = file_get_contents('php://input');
        if (!mb_check_encoding($rawInput, 'UTF-8')) {
            $rawInput = mb_convert_encoding($rawInput, 'UTF-8', 'Windows-1252,ISO-8859-1,UTF-8');
        }

        return json_decode($rawInput, true);
    }
}
