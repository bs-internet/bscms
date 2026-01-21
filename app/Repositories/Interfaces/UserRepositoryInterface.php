<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function getAll(): array;
    public function findById(int $id): ?object;
    public function findByUsername(string $username): ?object;
    public function findByEmail(string $email): ?object;
    public function findByResetToken(string $token): ?object;
    public function findByValidResetToken(string $token): ?object;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}