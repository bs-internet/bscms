<?php

namespace App\Http\Controllers\Backend\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ModuleUpdateRequest;
use App\Services\Content\ModuleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

abstract class SingleModuleController extends Controller
{
    public function __construct(protected readonly ModuleService $moduleService)
    {
    }

    public function index(): View
    {
        $module = $this->moduleService->getOrCreate($this->moduleType(), $this->moduleName());

        return view($this->viewName(), compact('module'));
    }

    public function update(ModuleUpdateRequest $request): RedirectResponse
    {
        $module = $this->moduleService->getOrCreate($this->moduleType(), $this->moduleName());

        $this->moduleService->updateContent(
            $module,
            $request->validated()['content'],
            $request->validated()['is_active'] ?? true
        );

        return redirect()->route($this->routeName())->with('status', 'Modül güncellendi.');
    }

    abstract protected function moduleType(): string;

    abstract protected function viewName(): string;

    protected function moduleName(): ?string
    {
        return null;
    }

    protected function routeName(): string
    {
        return 'admin.modules.' . $this->moduleType();
    }
}
