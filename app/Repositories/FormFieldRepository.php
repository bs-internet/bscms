<?php

namespace App\Repositories;

use App\Models\FormFieldModel;
use App\Repositories\Interfaces\FormFieldRepositoryInterface;

class FormFieldRepository implements FormFieldRepositoryInterface
{
    protected FormFieldModel $model;

    public function __construct(FormFieldModel $model)
    {
        $this->model = $model;
    }

    public function getByFormId(int $formId): array
    {
        return $this->model->where('form_id', $formId)
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