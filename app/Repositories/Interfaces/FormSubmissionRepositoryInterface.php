<?php

namespace App\Repositories\Interfaces;

interface FormSubmissionRepositoryInterface
{
    public function getByFormId(int $formId, array $filters = []): array;
    public function findById(int $id): ?object;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}