<?php

namespace App\Core\Modules\Component\Repositories;

use App\Core\Modules\Component\Models\ComponentInstanceDataModel;
use App\Core\Modules\Component\Repositories\Interfaces\ComponentInstanceDataRepositoryInterface;

class ComponentInstanceDataRepository implements ComponentInstanceDataRepositoryInterface
{
    protected ComponentInstanceDataModel $model;

    public function __construct(ComponentInstanceDataModel $model)
    {
        $this->model = $model;
    }

    public function getByInstanceId(int $instanceId): array
    {
        return $this->model->where('component_instance_id', $instanceId)->findAll();
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

    public function upsert(int $instanceId, string $fieldKey, $value): bool
    {
        $existing = $this->model
            ->where('component_instance_id', $instanceId)
            ->where('field_key', $fieldKey)
            ->first();

        $data = [
            'component_instance_id' => $instanceId,
            'field_key' => $fieldKey,
            'field_value' => is_array($value) ? json_encode($value) : $value
        ];

        if ($existing) {
            return $this->update($existing->id, ['field_value' => $data['field_value']]);
        }

        $result = $this->create($data);
        return $result !== null;
    }
}