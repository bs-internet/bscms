<?php

namespace App\Repositories;

use App\Models\FormSubmissionModel;
use App\Repositories\Interfaces\FormSubmissionRepositoryInterface;

class FormSubmissionRepository implements FormSubmissionRepositoryInterface
{
    protected FormSubmissionModel $model;

    public function __construct(FormSubmissionModel $model)
    {
        $this->model = $model;
    }

    public function getByFormId(int $formId, array $filters = []): array
    {
        $builder = $this->model->where('form_id', $formId);

        if (isset($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        if (isset($filters['limit'])) {
            $builder->limit($filters['limit']);
        }

        if (isset($filters['offset'])) {
            $builder->offset($filters['offset']);
        }

        $builder->orderBy('created_at', 'DESC');

        return $builder->findAll();
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