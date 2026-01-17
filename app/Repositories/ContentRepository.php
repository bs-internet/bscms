<?php

namespace App\Repositories;

use App\Models\ContentModel;
use App\Repositories\Interfaces\ContentRepositoryInterface;
use App\Enums\ContentStatus;

class ContentRepository implements ContentRepositoryInterface
{
    protected ContentModel $model;

    public function __construct(ContentModel $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->model->builder();

        if (isset($filters['status'])) {
            if ($filters['status'] instanceof ContentStatus) {
                $builder->where('status', $filters['status']->value);
            } else {
                $builder->where('status', $filters['status']);
            }
        }

        if (isset($filters['content_type_id'])) {
            $builder->where('content_type_id', $filters['content_type_id']);
        }

        if (isset($filters['limit'])) {
            $builder->limit($filters['limit']);
        }

        if (isset($filters['offset'])) {
            $builder->offset($filters['offset']);
        }

        $orderBy = $filters['order_by'] ?? 'created_at';
        $order = $filters['order'] ?? 'DESC';
        $builder->orderBy($orderBy, $order);

        return $builder->get()->getResult($this->model->returnType);
    }

    public function findById(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function findBySlug(string $slug, ?int $contentTypeId = null): ?object
    {
        $builder = $this->model->where('slug', $slug);
        
        if ($contentTypeId) {
            $builder->where('content_type_id', $contentTypeId);
        }
        
        return $builder->first();
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

    public function getByContentType(int $contentTypeId, array $filters = []): array
    {
        $filters['content_type_id'] = $contentTypeId;
        return $this->getAll($filters);
    }

    public function getByCategory(int $categoryId, array $filters = []): array
    {
        $builder = $this->model->builder();
        $builder->join('content_categories', 'contents.id = content_categories.content_id');
        $builder->where('content_categories.category_id', $categoryId);

        if (isset($filters['status'])) {
            if ($filters['status'] instanceof ContentStatus) {
                $builder->where('contents.status', $filters['status']->value);
            } else {
                $builder->where('contents.status', $filters['status']);
            }
        }

        if (isset($filters['limit'])) {
            $builder->limit($filters['limit']);
        }

        if (isset($filters['offset'])) {
            $builder->offset($filters['offset']);
        }

        $orderBy = $filters['order_by'] ?? 'contents.created_at';
        $order = $filters['order'] ?? 'DESC';
        $builder->orderBy($orderBy, $order);

        return $builder->get()->getResult($this->model->returnType);
    }
}