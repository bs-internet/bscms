<?php

namespace App\Repositories;

use App\Models\ComponentInstanceModel;
use App\Repositories\Interfaces\ComponentInstanceRepositoryInterface;

class ComponentInstanceRepository implements ComponentInstanceRepositoryInterface
{
    protected ComponentInstanceModel $model;

    public function __construct(ComponentInstanceModel $model)
    {
        $this->model = $model;
    }

    public function getByComponentId(int $componentId): array
    {
        return $this->model->where('component_id', $componentId)->findAll();
    }

    public function findById(int $id): ?object
    {
        return $this->model->find($id);
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

    public function getGlobalInstances(): array
    {
        return $this->model->where('is_global', 1)->findAll();
    }
}