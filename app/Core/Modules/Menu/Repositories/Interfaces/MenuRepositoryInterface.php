<?php

namespace App\Core\Modules\Menu\Repositories\Interfaces;

interface MenuRepositoryInterface
{
    public function getAll(): array;
    public function findById(int $id): ?object;
    public function findByLocation(string $location): ?object;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}