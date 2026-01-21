<?php

namespace App\Core\Modules\Component\Repositories\Interfaces;

interface ComponentRepositoryInterface
{
    public function getAll(array $filters = []): array;
    public function findById(int $id): ?object;
    public function findBySlug(string $slug): ?object;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByType(string $type): array;
}