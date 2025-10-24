<?php

namespace App\Http\Controllers\Backend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Backend\Menu;
use App\Models\Backend\Module;
use App\Models\Backend\Page;
use App\Models\Backend\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index', [
            'pageCount' => Page::count(),
            'menuCount' => Menu::count(),
            'moduleCount' => Module::count(),
            'settingCount' => Setting::count(),
        ]);
    }
}
