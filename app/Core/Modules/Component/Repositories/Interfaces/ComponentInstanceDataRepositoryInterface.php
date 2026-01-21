<?php

namespace App\Core\Modules\Component\Repositories\Interfaces;

interface ComponentInstanceDataRepositoryInterface
{
    public function getByInstanceId(int $instanceId): array;
    public function findById(int $id): ?object;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function upsert(int $instanceId, string $fieldKey, $value): bool;
}