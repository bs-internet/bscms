<?php

namespace App\Http\Controllers\Backend\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PageRequest;
use App\Models\Backend\Page;
use App\Services\Content\PageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagesController extends Controller
{
    public function __construct(private readonly PageService $pageService)
    {
    }

    public function index(Request $request): View
    {
        $term = trim((string) $request->input('q'));
        $pages = $this->pageService->paginate($term !== '' ? $term : null);

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.create', ['page' => new Page()]);
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $page = $this->pageService->create($request->validated());

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', 'Sayfa oluşturuldu.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $this->pageService->update($page, $request->validated());

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', 'Sayfa güncellendi.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $this->pageService->delete($page);

        return redirect()
            ->route('admin.pages.index')
            ->with('status', 'Sayfa silindi.');
    }
}
