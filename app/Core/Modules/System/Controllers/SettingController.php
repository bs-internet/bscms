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

        // Sensitive settings protection
        $sensitiveSettings = ['smtp_password', 'api_key', 'encryption_key', 'aws_secret_key'];

        foreach ($postData as $key => $value) {
            if ($key === 'csrf_test_name' || $key === 'confirm_sensitive') {
                continue;
            }

            $parts = explode('__', $key);
            if (count($parts) === 2) {
                // Check if this is a sensitive setting
                if (in_array($parts[1], $sensitiveSettings)) {
                    // If we find ANY sensitive setting being updated, we require confirmation
                    if (!$this->request->getPost('confirm_sensitive')) {
                        return redirect()->back()->with('error', 'Hassas ayarları (Şifre, API Key vb.) değiştirmek için onay kutusunu işaretlemelisiniz.');
                    }
                }
            }
        }

        foreach ($postData as $key => $value) {
            if ($key === 'csrf_test_name' || $key === 'confirm_sensitive') {
                continue;
            }

            $parts = explode('__', $key);
            if (count($parts) === 2) {
                $group = $parts[0];
                $settingKey = $parts[1];
                $this->settingRepository->upsert($settingKey, $value, $group);
            }
        }

        // Cache temizle
        cache()->delete('settings');

        return redirect()->back()->with('success', 'Ayarlar başarıyla güncellendi.');
    }

    /**
     * Clear all caches (page cache + app cache)
     */
    public function clearCache()
    {
        $cleared = [];

        // 1. Clear CI4 page cache
        $cachePath = WRITEPATH . 'cache/';
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '*');
            $count = 0;
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== 'index.html') {
                    unlink($file);
                    $count++;
                }
            }
            $cleared[] = "$count dosya önbelleği";
        }

        // 2. Clear CI4 cache store
        cache()->clean();
        $cleared[] = 'uygulama önbelleği';

        $message = 'Temizlendi: ' . implode(', ', $cleared);

        return redirect()->back()->with('success', $message);
    }
}
