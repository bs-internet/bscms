<?php

namespace App\Repositories;

use App\Models\ContentMetaModel;
use App\Repositories\Interfaces\ContentMetaRepositoryInterface;

class ContentMetaRepository implements ContentMetaRepositoryInterface
{
    protected ContentMetaModel $model;

    public function __construct(ContentMetaModel $model)
    {
        $this->model = $model;
    }

    public function getByContentId(int $contentId): array
    {
        return $this->model->where('content_id', $contentId)->findAll();
    }

    public function getByKey(int $contentId, string $key): ?object
    {
        return $this->model->where('content_id', $contentId)
                          ->where('meta_key', $key)
                          ->first();
    }

    public function create(array $data): ?object
    {
        $id = $this->model->insert($data);
        return $id ? $this->model->find($id) : null;
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    public function deleteByContentId(int $contentId): bool
    {
        return $this->model->where('content_id', $contentId)->delete();
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