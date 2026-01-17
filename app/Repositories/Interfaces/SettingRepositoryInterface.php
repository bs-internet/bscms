<?php

namespace App\Repositories\Interfaces;

interface SettingRepositoryInterface
{
    public function getAll(): array;
    public function getByGroup(string $group): array;
    public function findByKey(string $key): ?object;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function upsert(string $key, $value, string $group): bool;
    public function delete(int $id): bool;
}