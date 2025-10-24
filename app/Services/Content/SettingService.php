<?php

namespace App\Services\Content;

use App\Models\Backend\Setting;

class SettingService
{
    public function forGroup(string $group): array
    {
        return Setting::query()
            ->where('group', $group)
            ->get()
            ->mapWithKeys(function (Setting $setting) {
                return [$setting->key => $setting->value];
            })
            ->toArray();
    }

    public function updateGroup(string $group, array $values): void
    {
        foreach ($values as $key => $value) {
            Setting::updateOrCreate(
                ['group' => $group, 'key' => $key],
                ['value' => $this->normalizeValue($value)]
            );
        }
    }

    protected function normalizeValue($value)
    {
        if (is_array($value)) {
            return array_filter($value, fn ($item) => $item !== null && $item !== '');
        }

        return $value;
    }
}
