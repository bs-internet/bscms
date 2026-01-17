<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\MenuRepositoryInterface;
use App\Repositories\Interfaces\MenuItemRepositoryInterface;
use App\Validation\MenuValidation;

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
        $this->requireAuth();

        $menus = $this->menuRepository->getAll();

        return view('admin/menus/index', ['menus' => $menus]);
    }

    public function create()
    {
        $this->requireAuth();

        return view('admin/menus/create');
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

        $menu = $this->menuRepository->create($data);

        if (!$menu) {
            return redirect()->back()->with('error', 'Menü oluşturulamadı.');
        }

        return redirect()->to('/admin/menus')->with('success', 'Menü başarıyla oluşturuldu.');
    }


    public function edit(int $id)
    {
        $this->requireAuth();

        $menu = $this->menuRepository->findById($id);

        if (!$menu) {
            return redirect()->to('/admin/menus')->with('error', 'Menü bulunamadı.');
        }

        $items = $this->menuItemRepository->getByMenuId($id);

        return view('admin/menus/edit', [
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

        $result = $this->menuRepository->update($id, $data);

        if (!$result) {
            return redirect()->back()->with('error', 'Menü güncellenemedi.');
        }

        return redirect()->to('/admin/menus')->with('success', 'Menü başarıyla güncellendi.');
    }

    public function delete(int $id)
    {
        $this->requireAuth();

        $result = $this->menuRepository->delete($id);

        if (!$result) {
            return redirect()->back()->with('error', 'Menü silinemedi.');
        }

        return redirect()->to('/admin/menus')->with('success', 'Menü başarıyla silindi.');
    }
}