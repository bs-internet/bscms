<?php

namespace App\Core\Modules\Menu\Repositories;

use App\Core\Modules\Menu\Models\MenuModel;
use App\Core\Modules\Menu\Repositories\Interfaces\MenuRepositoryInterface;

class MenuRepository implements MenuRepositoryInterface
{
    protected MenuModel $model;

    public function __construct(MenuModel $model)
    {
        $this->model = $model;
    }

    public function getAll(): array
    {
        return $this->model->findAll();
    }

    public function findById(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function findByLocation(string $location): ?object
    {
        return $this->model->where('location', $location)->first();
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