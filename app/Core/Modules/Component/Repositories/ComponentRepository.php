<?php

namespace App\Core\Modules\Component\Repositories;

use App\Core\Modules\Component\Models\ComponentModel;
use App\Core\Modules\Component\Repositories\Interfaces\ComponentRepositoryInterface;

class ComponentRepository implements ComponentRepositoryInterface
{
    protected ComponentModel $model;

    public function __construct(ComponentModel $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->model->builder();

        if (isset($filters['type'])) {
            $builder->where('type', $filters['type']);
        }

        $builder->orderBy('name', 'ASC');

        return $builder->get()->getResult($this->model->returnType);
    }

    public function findById(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function findBySlug(string $slug): ?object
    {
        return $this->model->where('slug', $slug)->first();
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

    public function getByType(string $type): array
    {
        return $this->model->where('type', $type)->findAll();
    }
}