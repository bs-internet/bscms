<?php

namespace App\Repositories\Interfaces;

interface ComponentInstanceRepositoryInterface
{
    public function getByComponentId(int $componentId): array;
    public function findById(int $id): ?object;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getGlobalInstances(): array;
}