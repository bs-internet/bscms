<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SettingRequest;
use App\Services\Content\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(private readonly SettingService $settingService)
    {
    }

    public function index(): View
    {
        $settings = $this->settingService->forGroup('general');

        return view('admin.settings.settings.index', compact('settings'));
    }

    public function update(SettingRequest $request): RedirectResponse
    {
        $this->settingService->updateGroup('general', $request->validated()['settings']);

        return redirect()->route('admin.settings.index')->with('status', 'Genel ayarlar güncellendi.');
    }
}
