<?php

namespace App\Http\Controllers\Backend\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\MenuRequest;
use App\Models\Backend\Menu;
use App\Services\Content\MenuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function __construct(private readonly MenuService $menuService)
    {
    }

    public function index(): View
    {
        $menus = $this->menuService->all();

        return view('admin.menu.index', compact('menus'));
    }

    public function store(MenuRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->menuService->create($data, $data['items'] ?? []);

        return redirect()->route('admin.menu.index')->with('status', 'Menü oluşturuldu.');
    }

    public function update(MenuRequest $request, Menu $menu): RedirectResponse
    {
        $data = $request->validated();

        $this->menuService->update($menu, $data, $data['items'] ?? []);

        return redirect()->route('admin.menu.index')->with('status', 'Menü güncellendi.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $this->menuService->delete($menu);

        return redirect()->route('admin.menu.index')->with('status', 'Menü silindi.');
    }
}
