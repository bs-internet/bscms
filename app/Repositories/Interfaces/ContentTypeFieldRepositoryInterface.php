<?php

namespace App\Repositories\Interfaces;

interface ContentTypeFieldRepositoryInterface
{
    public function getAll(array $filters = []): array;
    public function findById(int $id): ?object;
    public function getByContentType(int $contentTypeId): array;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
