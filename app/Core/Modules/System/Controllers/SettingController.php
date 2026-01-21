<?php

namespace App\Core\Modules\System\Controllers;

use App\Core\Shared\Controllers\BaseController;

use App\Core\Modules\System\Repositories\Interfaces\SettingRepositoryInterface;

class SettingController extends BaseController
{
    protected SettingRepositoryInterface $settingRepository;

    public function __construct()
    {
        $this->settingRepository = service('settingRepository');
    }

    public function index()
    {
        $settings = $this->settingRepository->getAll();

        $groupedSettings = [];
        foreach ($settings as $setting) {
            $groupedSettings[$setting->setting_group][] = $setting;
        }

        return view('App\Core\Modules\System\Views/settings/index', ['groupedSettings' => $groupedSettings]);
    }

    public function update()
    {
        $postData = $this->request->getPost();

        foreach ($postData as $key => $value) {
            if ($key === 'csrf_test_name') {
                continue;
            }

            $parts = explode('__', $key);
            if (count($parts) === 2) {
                $group = $parts[0];
                $settingKey = $parts[1];

                $this->settingRepository->upsert($settingKey, $value, $group);
            }
        }

        $cacheManager = new \App\Core\Shared\Libraries\CacheManager();
        $cacheManager->clearSettings();

        return redirect()->to('/admin/settings')->with('success', 'Ayarlar başarıyla güncellendi.');
    }
}

