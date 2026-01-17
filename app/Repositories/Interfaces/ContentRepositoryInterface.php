<?php

namespace App\Repositories\Interfaces;

interface ContentRepositoryInterface
{
    public function getAll(array $filters = []): array;
    public function findById(int $id): ?object;
    public function findBySlug(string $slug, ?int $contentTypeId = null): ?object;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByContentType(int $contentTypeId, array $filters = []): array;
    public function getByCategory(int $categoryId, array $filters = []): array;
}