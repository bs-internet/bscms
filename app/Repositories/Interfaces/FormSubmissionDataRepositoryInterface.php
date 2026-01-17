<?php

namespace App\Repositories\Interfaces;

interface FormSubmissionDataRepositoryInterface
{
    public function getBySubmissionId(int $submissionId): array;
    public function create(array $data): ?object;
    public function deleteBySubmissionId(int $submissionId): bool;
}