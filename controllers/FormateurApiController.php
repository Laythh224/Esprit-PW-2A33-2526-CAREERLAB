<?php

class FormateurApiController extends AbstractApiController
{
    private FormateurRepository $repository;

    public function __construct(FormateurRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function collectionKey(): string
    {
        return 'formateurs';
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
        return 'Formateur ajouté avec succès.';
    }

    protected function updateMessage(): string
    {
        return 'Formateur modifié avec succès.';
    }

    protected function deleteMessage(): string
    {
        return 'Formateur supprimé avec succès.';
    }
}
