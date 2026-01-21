<?php

namespace App\Core\Modules\Media\Repositories;

use App\Core\Modules\Media\Models\MediaModel;
use App\Core\Modules\Media\Repositories\Interfaces\MediaRepositoryInterface;

class MediaRepository implements MediaRepositoryInterface
{
    protected MediaModel $model;

    public function __construct(MediaModel $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->model->builder();

        if (isset($filters['mimetype'])) {
            $builder->like('mimetype', $filters['mimetype']);
        }

        if (isset($filters['limit'])) {
            $builder->limit($filters['limit']);
        }

        if (isset($filters['offset'])) {
            $builder->offset($filters['offset']);
        }

        $builder->orderBy('created_at', 'DESC');

        return $builder->get()->getResult($this->model->returnType);
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

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }
}