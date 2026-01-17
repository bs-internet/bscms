<?php

namespace App\Repositories;

use App\Models\ContentComponentModel;
use App\Repositories\Interfaces\ContentComponentRepositoryInterface;

class ContentComponentRepository implements ContentComponentRepositoryInterface
{
    protected ContentComponentModel $model;

    public function __construct(ContentComponentModel $model)
    {
        $this->model = $model;
    }

    public function getByContentId(int $contentId): array
    {
        return $this->model
            ->where('content_id', $contentId)
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

    public function deleteByContent(int $contentId): bool
    {
        return $this->model->where('content_id', $contentId)->delete();
    }
}