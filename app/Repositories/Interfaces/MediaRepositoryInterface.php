<?php

namespace App\Repositories\Interfaces;

interface MediaRepositoryInterface
{
    public function getAll(array $filters = []): array;
    public function findById(int $id): ?object;
    public function create(array $data): ?object;
    public function delete(int $id): bool;
}