<?php

namespace App\Core\Modules\Content\Repositories\Interfaces;

interface ContentMetaRepositoryInterface
{
    public function getAll(array $filters = []): array;
    public function findById(int $id): ?object;
    public function getByKey(int $contentId, string $key): ?object;
    public function getAllByContentId(int $contentId): array;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function upsert(int $contentId, string $key, $value): bool;
}
