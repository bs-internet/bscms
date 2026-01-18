<?php

namespace App\Repositories;

use App\Models\ContentModel;
use App\Repositories\Interfaces\ContentRepositoryInterface;
use App\Enums\ContentStatus;

class ContentRepository implements ContentRepositoryInterface
{
    protected ContentModel $model;

    public function __construct(ContentModel $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->model->builder();

        if (isset($filters['content_type_id'])) {
            $builder->where('content_type_id', $filters['content_type_id']);
        }

        if (isset($filters['status'])) {
            if ($filters['status'] instanceof ContentStatus) {
                $builder->where('status', $filters['status']->value);
            } else {
                $builder->where('status', $filters['status']);
            }
        }

        if (isset($filters['limit'])) {
            $builder->limit($filters['limit']);
        }

        if (isset($filters['offset'])) {
            $builder->offset($filters['offset']);
        }

        $orderBy = $filters['order_by'] ?? 'created_at';
        $order = $filters['order'] ?? 'DESC';
        $builder->orderBy($orderBy, $order);

        return $builder->get()->getResult($this->model->returnType);
    }

    public function findById(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function findBySlug(string $slug): ?object
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function findByIdWithRelations(int $id, array $relationKeys = []): ?object
    {
        $content = $this->findById($id);

        if (!$content || empty($relationKeys)) {
            return $content;
        }

        $metaRepo = service('contentMetaRepository');

        foreach ($relationKeys as $key) {
            $metaValue = $metaRepo->getByKey($id, $key)?->meta_value;

            if (!$metaValue) {
                $content->{$key . '_relation'} = null;
                continue;
            }

            $decoded = json_decode($metaValue, true);

            if (is_array($decoded)) {
                $related = [];
                foreach ($decoded as $relatedId) {
                    $relatedContent = $this->findById($relatedId);
                    if ($relatedContent) {
                        $related[] = $relatedContent;
                    }
                }
                $content->{$key . '_relation'} = $related;
            } else {
                $content->{$key . '_relation'} = $this->findById((int)$metaValue);
            }
        }

        return $content;
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
