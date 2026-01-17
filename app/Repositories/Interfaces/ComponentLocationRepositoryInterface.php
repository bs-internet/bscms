<?php

namespace App\Repositories\Interfaces;

interface ComponentLocationRepositoryInterface
{
    public function getByComponentId(int $componentId): array;
    public function findById(int $id): ?object;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function deleteByComponent(int $componentId): bool;
}