<?php

namespace App\Core\Modules\Menu\Controllers;

use App\Core\Shared\Controllers\BaseController;

use App\Core\Modules\Menu\Repositories\Interfaces\MenuRepositoryInterface;
use App\Core\Modules\Menu\Repositories\Interfaces\MenuItemRepositoryInterface;
use App\Core\Modules\Menu\Validation\MenuValidation;

class MenuController extends BaseController
{
    protected MenuRepositoryInterface $menuRepository;
    protected MenuItemRepositoryInterface $menuItemRepository;

    public function __construct()
    {
        $this->menuRepository = service('menuRepository');
        $this->menuItemRepository = service('menuItemRepository');
    }

    public function index()
    {
        $menus = $this->menuRepository->getAll();

        return view('App\Core\Modules\Menu\Views/index', ['menus' => $menus]);
    }

    public function create()
    {
        return view('App\Core\Modules\Menu\Views/create');
    }

    public function store()
    {
        if (!$this->validate(MenuValidation::rules(), MenuValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'location' => $this->request->getPost('location')
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        $menu = $this->menuRepository->create($data);

        if (!$menu) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Menü oluşturulamadı.');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Menü oluşturma başarısız.');
        }

        // ✅ CACHE INVALIDATION
        $this->clearCacheForCreate($menu);

        \CodeIgniter\Events\Events::trigger('menu_created', $menu->id);

        return redirect()->to('/admin/menus')->with('success', 'Menü başarıyla oluşturuldu.');
    }


    public function edit(int $id)
    {
        $menu = $this->menuRepository->findById($id);

        if (!$menu) {
            return redirect()->to('/admin/menus')->with('error', 'Menü bulunamadı.');
        }

        $items = $this->menuItemRepository->getByMenuId($id);

        return view('App\Core\Modules\Menu\Views/edit', [
            'menu' => $menu,
            'items' => $items
        ]);
    }

    public function update(int $id)
    {
        $menu = $this->menuRepository->findById($id);

        if (!$menu) {
            return redirect()->to('/admin/menus')->with('error', 'Menü bulunamadı.');
        }

        if (!$this->validate(MenuValidation::rules(true, $id), MenuValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'location' => $this->request->getPost('location')
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        $result = $this->menuRepository->update($id, $data);

        if (!$result) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Menü güncellenemedi.');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Güncelleme başarısız.');
        }

        // ✅ CACHE INVALIDATION
        $this->clearCacheForUpdate($id, $menu);

        \CodeIgniter\Events\Events::trigger('menu_updated', $id);

        return redirect()->to('/admin/menus')->with('success', 'Menü başarıyla güncellendi.');
    }

    public function delete(int $id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Get menu for cache clearing before deletion
        $menu = $this->menuRepository->findById($id);

        $result = $this->menuRepository->delete($id);

        if (!$result) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Menü silinemedi.');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Silme işlemi başarısız.');
        }

        // ✅ CACHE INVALIDATION
        $this->clearCacheForDelete($id, $menu);

        \CodeIgniter\Events\Events::trigger('menu_deleted', $id);

        return redirect()->to('/admin/menus')->with('success', 'Menü başarıyla silindi.');
    }
    protected function clearCacheForCreate($menu): void
    {
        cache()->deleteMatching("menu_*");
    }

    protected function clearCacheForUpdate(int $id, $oldMenu): void
    {
        cache()->delete("menu_{$id}");
        if ($oldMenu) {
            cache()->delete("menu_{$oldMenu->location}");
        }
        cache()->deleteMatching("menu_*");
    }

    protected function clearCacheForDelete(int $id, $menu): void
    {
        cache()->delete("menu_{$id}");
        if ($menu) {
            cache()->delete("menu_{$menu->location}");
        }
        cache()->deleteMatching("menu_*");
    }
}

