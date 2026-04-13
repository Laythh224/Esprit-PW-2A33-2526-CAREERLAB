<?php

class UserApiController extends AbstractApiController
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function collectionKey(): string
    {
        return 'users';
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
        return 'Utilisateur ajouté avec succès.';
    }

    protected function updateMessage(): string
    {
        return 'Utilisateur modifié avec succès.';
    }

    protected function deleteMessage(): string
    {
        return 'Utilisateur supprimé avec succès.';
    }
}
