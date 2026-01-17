<?php

namespace App\Repositories;

use App\Models\FormSubmissionDataModel;
use App\Repositories\Interfaces\FormSubmissionDataRepositoryInterface;

class FormSubmissionDataRepository implements FormSubmissionDataRepositoryInterface
{
    protected FormSubmissionDataModel $model;

    public function __construct(FormSubmissionDataModel $model)
    {
        $this->model = $model;
    }

    public function getBySubmissionId(int $submissionId): array
    {
        return $this->model->where('submission_id', $submissionId)->findAll();
    }

    public function create(array $data): ?object
    {
        $id = $this->model->insert($data);
        return $id ? $this->model->find($id) : null;
    }

    public function deleteBySubmissionId(int $submissionId): bool
    {
        return $this->model->where('submission_id', $submissionId)->delete();
    }
}