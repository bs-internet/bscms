<?php

namespace App\Core\Modules\Content\Repositories;

use App\Core\Modules\Content\Models\ContentTypeFieldModel;
use App\Core\Modules\Content\Repositories\Interfaces\ContentTypeFieldRepositoryInterface;

class ContentTypeFieldRepository implements ContentTypeFieldRepositoryInterface
{
    protected ContentTypeFieldModel $model;

    public function __construct(ContentTypeFieldModel $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->model->builder();

        if (isset($filters['content_type_id'])) {
            $builder->where('content_type_id', $filters['content_type_id']);
        }

        $orderBy = $filters['order_by'] ?? 'sort_order';
        $order = $filters['order'] ?? 'ASC';
        $builder->orderBy($orderBy, $order);

        return $builder->get()->getResult($this->model->returnType);
    }

    public function findById(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function getByContentType(int $contentTypeId): array
    {
        return $this->model
            ->where('content_type_id', $contentTypeId)
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
