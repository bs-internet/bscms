<?php

namespace App\Repositories;

use App\Models\UserModel;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected UserModel $model;

    public function __construct(UserModel $model)
    {
        $this->model = $model;
    }

    public function getAll(): array
    {
        return $this->model->findAll();
    }

    public function findById(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function findByUsername(string $username): ?object
    {
        return $this->model->where('username', $username)->first();
    }

    public function findByEmail(string $email): ?object
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByResetToken(string $token): ?object
    {
        return $this->model->where('reset_token', $token)->first();
    }

    public function findByValidResetToken(string $token): ?object
    {
        return $this->model
            ->where('reset_token', $token)
            ->where('reset_expires_at >', date('Y-m-d H:i:s'))
            ->first();
    }

    public function create(array $data): ?object
    {
        $id = $this->model->insert($data);
        return $id ? $this->findById($id) : null;
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }
}