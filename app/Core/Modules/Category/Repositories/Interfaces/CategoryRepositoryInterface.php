<?php

namespace App\Core\Modules\Category\Repositories\Interfaces;

interface CategoryRepositoryInterface
{
    public function getByContentTypeId(int $contentTypeId): array;
    public function getAll(array $filters = []): array;
    public function findById(int $id): ?object;
    public function findBySlug(string $slug, int $contentTypeId): ?object;
    public function getChildren(int $parentId): array;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}