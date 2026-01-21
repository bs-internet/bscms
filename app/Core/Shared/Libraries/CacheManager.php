<?php

namespace App\Core\Shared\Libraries;

class CacheManager
{
    protected $cache;
    protected int $ttl = 3600; // 1 saat

    public function __construct()
    {
        $this->cache = \Config\Services::cache();
    }

    public function getSetting(string $key)
    {
        $cacheKey = 'setting_' . $key;
        $value = $this->cache->get($cacheKey);

        if ($value === null) {
            $settingRepository = service('settingRepository');
            $setting = $settingRepository->findByKey($key);
            $value = $setting ? $setting->setting_value : null;

            $this->cache->save($cacheKey, $value, $this->ttl);
        }

        return $value;
    }

    public function getSettings(string $group = null): array
    {
        $cacheKey = $group ? 'settings_group_' . $group : 'settings_all';
        $settings = $this->cache->get($cacheKey);

        if ($settings === null) {
            $settingRepository = service('settingRepository');
            $settings = $group ? $settingRepository->getByGroup($group) : $settingRepository->getAll();

            $this->cache->save($cacheKey, $settings, $this->ttl);
        }

        return $settings;
    }

    public function clearSettings(): void
    {
        $this->cache->deleteMatching('setting_*');
        $this->cache->deleteMatching('settings_*');
    }

    public function getContent(int $id)
    {
        $cacheKey = 'content_' . $id;
        $content = $this->cache->get($cacheKey);

        if ($content === null) {
            $contentRepository = service('contentRepository');
            $content = $contentRepository->findById($id);

            if ($content) {
                $this->cache->save($cacheKey, $content, $this->ttl);
            }
        }

        return $content;
    }

    public function clearContent(int $id): void
    {
        $this->cache->delete('content_' . $id);
    }

    public function clearAllContents(): void
    {
        $this->cache->deleteMatching('content_*');
    }

    public function getMenu(string $location): array
    {
        $cacheKey = 'menu_' . $location;
        $menu = $this->cache->get($cacheKey);

        if ($menu === null) {
            $template = service('template');
            $menu = $template->getMenu($location);

            $this->cache->save($cacheKey, $menu, $this->ttl);
        }

        return $menu;
    }

    public function clearMenus(): void
    {
        $this->cache->deleteMatching('menu_*');
    }
}