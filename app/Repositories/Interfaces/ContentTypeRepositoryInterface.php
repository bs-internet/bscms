<?php

namespace App\Repositories\Interfaces;

interface ContentTypeRepositoryInterface
{
    public function getAll(): array;
    public function findById(int $id): ?object;
    public function findBySlug(string $slug): ?object;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}