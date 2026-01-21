<?php

namespace App\Core\Modules\Content\Repositories;

use App\Core\Modules\Content\Models\ContentMetaModel;
use App\Core\Modules\Content\Repositories\Interfaces\ContentMetaRepositoryInterface;

class ContentMetaRepository implements ContentMetaRepositoryInterface
{
    protected ContentMetaModel $model;

    public function __construct(ContentMetaModel $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->model->builder();

        if (isset($filters['content_id'])) {
            $builder->where('content_id', $filters['content_id']);
        }

        return $builder->get()->getResult($this->model->returnType);
    }

    public function findById(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function getByKey(int $contentId, string $key): ?object
    {
        return $this->model
            ->where('content_id', $contentId)
            ->where('meta_key', $key)
            ->first();
    }

    public function getAllByContentId(int $contentId): array
    {
        return $this->model
            ->where('content_id', $contentId)
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

    public function upsert(int $contentId, string $key, $value): bool
    {
        $existing = $this->getByKey($contentId, $key);

        if ($existing) {
            return $this->update($existing->id, ['meta_value' => $value]);
        }

        $result = $this->create([
            'content_id' => $contentId,
            'meta_key' => $key,
            'meta_value' => $value
        ]);

        return $result !== null;
    }
}
