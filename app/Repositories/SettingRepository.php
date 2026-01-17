<?php

namespace App\Repositories;

use App\Models\SettingModel;
use App\Repositories\Interfaces\SettingRepositoryInterface;

class SettingRepository implements SettingRepositoryInterface
{
    protected SettingModel $model;

    public function __construct(SettingModel $model)
    {
        $this->model = $model;
    }

    public function getAll(): array
    {
        return $this->model->findAll();
    }

    public function getByGroup(string $group): array
    {
        return $this->model->where('setting_group', $group)->findAll();
    }

    public function findByKey(string $key): ?object
    {
        return $this->model->where('setting_key', $key)->first();
    }

    public function create(array $data): ?object
    {
        $id = $this->model->insert($data);
        return $id ? $this->model->find($id) : null;
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    public function upsert(string $key, $value, string $group): bool
    {
        $existing = $this->findByKey($key);

        if ($existing) {
            return $this->update($existing->id, ['setting_value' => $value]);
        }

        $result = $this->create([
            'setting_key' => $key,
            'setting_value' => $value,
            'setting_group' => $group
        ]);

        return $result !== null;
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }
}