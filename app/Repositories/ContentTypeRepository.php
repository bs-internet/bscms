<?php

namespace App\Repositories;

use App\Models\ContentTypeModel;
use App\Repositories\Interfaces\ContentTypeRepositoryInterface;

class ContentTypeRepository implements ContentTypeRepositoryInterface
{
    protected ContentTypeModel $model;

    public function __construct(ContentTypeModel $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->model->builder();

        if (isset($filters['visible'])) {
            $builder->where('visible', $filters['visible']);
        }

        $orderBy = $filters['order_by'] ?? 'name';
        $order = $filters['order'] ?? 'ASC';
        $builder->orderBy($orderBy, $order);

        if (isset($filters['limit'])) {
            $builder->limit($filters['limit']);
        }

        if (isset($filters['offset'])) {
            $builder->offset($filters['offset']);
        }

        return $builder->get()->getResult($this->model->returnType);
    }

    public function getVisible(): array
    {
        return $this->model
            ->where('visible', 1)
            ->orderBy('name', 'ASC')
            ->findAll();
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
