<?php

namespace App\Repositories\Interfaces;

interface ContentMetaRepositoryInterface
{
    public function getByContentId(int $contentId): array;
    public function getByKey(int $contentId, string $key): ?object;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function deleteByContentId(int $contentId): bool;
    public function upsert(int $contentId, string $key, $value): bool;
}