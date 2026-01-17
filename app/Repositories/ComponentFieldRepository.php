<?php

namespace App\Repositories;

use App\Models\ComponentFieldModel;
use App\Repositories\Interfaces\ComponentFieldRepositoryInterface;

class ComponentFieldRepository implements ComponentFieldRepositoryInterface
{
    protected ComponentFieldModel $model;

    public function __construct(ComponentFieldModel $model)
    {
        $this->model = $model;
    }

    public function getByComponentId(int $componentId): array
    {
        return $this->model
            ->where('component_id', $componentId)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
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
}