<?php

namespace App\Repositories;

use App\Models\CategoryModel;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected CategoryModel $model;

    public function __construct(CategoryModel $model)
    {
        $this->model = $model;
    }

    public function getByContentTypeId(int $contentTypeId): array
    {
        return $this->model->where('content_type_id', $contentTypeId)
                          ->orderBy('sort_order', 'ASC')
                          ->findAll();
    }

    public function findById(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function findBySlug(string $slug, int $contentTypeId): ?object
    {
        return $this->model->where('slug', $slug)
                          ->where('content_type_id', $contentTypeId)
                          ->first();
    }

    public function getChildren(int $parentId): array
    {
        return $this->model->where('parent_id', $parentId)
                          ->orderBy('sort_order', 'ASC')
                          ->findAll();
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