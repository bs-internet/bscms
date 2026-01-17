<?php

namespace App\Repositories\Interfaces;

interface FormFieldRepositoryInterface
{
    public function getByFormId(int $formId): array;
    public function findById(int $id): ?object;
    public function create(array $data): ?object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}