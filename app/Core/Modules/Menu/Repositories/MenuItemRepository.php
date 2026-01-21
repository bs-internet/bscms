<?php

namespace App\Core\Modules\Menu\Repositories;

use App\Core\Modules\Menu\Models\MenuItemModel;
use App\Core\Modules\Menu\Repositories\Interfaces\MenuItemRepositoryInterface;

class MenuItemRepository implements MenuItemRepositoryInterface
{
    protected MenuItemModel $model;

    public function __construct(MenuItemModel $model)
    {
        $this->model = $model;
    }

    public function getByMenuId(int $menuId): array
    {
        return $this->model->where('menu_id', $menuId)
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