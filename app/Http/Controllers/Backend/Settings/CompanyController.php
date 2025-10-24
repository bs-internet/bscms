<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SettingRequest;
use App\Services\Content\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function __construct(private readonly SettingService $settingService)
    {
    }

    public function index(): View
    {
        $settings = $this->settingService->forGroup('company');

        return view('admin.settings.company.index', compact('settings'));
    }

    public function update(SettingRequest $request): RedirectResponse
    {
        $this->settingService->updateGroup('company', $request->validated()['settings']);

        return redirect()->route('admin.settings.company.index')->with('status', 'Şirket bilgileri güncellendi.');
    }
}
