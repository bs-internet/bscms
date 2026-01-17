<?php

namespace App\Repositories;

use App\Models\FormModel;
use App\Repositories\Interfaces\FormRepositoryInterface;

class FormRepository implements FormRepositoryInterface
{
    protected FormModel $model;

    public function __construct(FormModel $model)
    {
        $this->model = $model;
    }

    public function getAll(): array
    {
        return $this->model->orderBy('name', 'ASC')->findAll();
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
}