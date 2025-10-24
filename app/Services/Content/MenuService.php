<?php

namespace App\Services\Content;

use App\Models\Backend\Menu;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MenuService
{
    public function paginate(): LengthAwarePaginator
    {
        return Menu::with('items')->orderBy('name')->paginate(10);
    }

    public function all(): Collection
    {
        return Menu::with('items')->orderBy('name')->get();
    }

    public function create(array $data, array $items = []): Menu
    {
        $menu = Menu::create($data);
        $this->syncItems($menu, $items);

        return $menu->load('items');
    }

    public function update(Menu $menu, array $data, array $items = []): Menu
    {
        $menu->update($data);
        $this->syncItems($menu, $items);

        return $menu->load('items');
    }

    public function delete(Menu $menu): void
    {
        $menu->delete();
    }

    protected function syncItems(Menu $menu, array $items): void
    {
        $menu->items()->delete();

        $prepared = collect($items)
            ->values()
            ->map(function ($item, $index) {
                $title = trim($item['title'] ?? '');
                $url = trim($item['url'] ?? '');

                if ($title === '' || $url === '') {
                    return null;
                }

                return [
                    'title' => $title,
                    'url' => $url,
                    'target' => $item['target'] ?? '_self',
                    'order_column' => $index,
                ];
            })
            ->filter()
            ->values()
            ->all();

        if (!empty($prepared)) {
            $menu->items()->createMany($prepared);
        }
    }
}
