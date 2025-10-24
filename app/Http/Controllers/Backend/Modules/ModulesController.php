<?php

namespace App\Http\Controllers\Backend\Modules;

use App\Http\Controllers\Controller;
use App\Services\Content\ModuleService;
use Illuminate\View\View;

class ModulesController extends Controller
{
    public function __construct(private readonly ModuleService $moduleService)
    {
    }

    public function index(): View
    {
        $modules = $this->moduleService->list();

        return view('admin.modules.modules.index', compact('modules'));
    }
}
