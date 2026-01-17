<?php

namespace App\Repositories;

use App\Models\ComponentLocationModel;
use App\Repositories\Interfaces\ComponentLocationRepositoryInterface;

class ComponentLocationRepository implements ComponentLocationRepositoryInterface
{
    protected ComponentLocationModel $model;

    public function __construct(ComponentLocationModel $model)
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

    public function deleteByComponent(int $componentId): bool
    {
        return $this->model->where('component_id', $componentId)->delete();
    }
}