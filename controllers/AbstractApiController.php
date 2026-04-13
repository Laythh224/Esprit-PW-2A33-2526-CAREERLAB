<?php

abstract class AbstractApiController
{
    public function handle(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'GET') {
            JsonResponse::send(true, [
                $this->collectionKey() => $this->allItems(),
                'stats' => [
                    'total' => $this->countItems(),
                ],
            ]);
        }

        if ($method !== 'POST') {
            JsonResponse::send(false, ['message' => 'Méthode non autorisée.'], 405);
        }

        $input = $this->decodeJsonInput();
        if (!is_array($input)) {
            JsonResponse::send(false, ['message' => 'Payload JSON invalide.'], 400);
        }

        $action = trim((string) ($input['action'] ?? ''));

        try {
            if ($action === 'create') {
                $this->createItem($input);
                JsonResponse::send(true, ['message' => $this->createMessage()]);
            }

            if ($action === 'update') {
                $this->updateItem($input);
                JsonResponse::send(true, ['message' => $this->updateMessage()]);
            }

            if ($action === 'delete') {
                $this->deleteItem((int) ($input['id'] ?? 0));
                JsonResponse::send(true, ['message' => $this->deleteMessage()]);
            }

            JsonResponse::send(false, ['message' => 'Action non supportée.'], 400);
        } catch (InvalidArgumentException $exception) {
            JsonResponse::send(false, ['message' => $exception->getMessage()], 422);
        }
    }

    protected function decodeJsonInput(): ?array
    {
        return json_decode((string) file_get_contents('php://input'), true);
    }

    abstract protected function collectionKey(): string;

    abstract protected function allItems(): array;

    abstract protected function countItems(): int;

    abstract protected function createItem(array $input): void;

    abstract protected function updateItem(array $input): void;

    abstract protected function deleteItem(int $id): void;

    abstract protected function createMessage(): string;

    abstract protected function updateMessage(): string;

    abstract protected function deleteMessage(): string;
}
