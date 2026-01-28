<?php

namespace App\Core\Modules\Auth\Repositories;

use App\Core\Modules\Auth\Models\UserModel;
use App\Core\Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;

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

    /**
     * Update token fields directly (bypasses allowedFields protection)
     * Used for remember_token and reset_token updates
     */
    public function updateTokenFields(int $id, array $data): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');

        // Only allow token-related fields
        $allowedTokenFields = ['remember_token', 'remember_expires_at', 'reset_token', 'reset_expires_at'];
        $filteredData = array_intersect_key($data, array_flip($allowedTokenFields));

        if (empty($filteredData)) {
            return false;
        }

        return $builder->where('id', $id)->update($filteredData);
    }

    /**
     * Clear all token fields for a user
     */
    public function clearTokenFields(int $id): bool
    {
        return $this->updateTokenFields($id, [
            'remember_token' => null,
            'remember_expires_at' => null,
            'reset_token' => null,
            'reset_expires_at' => null
        ]);
    }
}