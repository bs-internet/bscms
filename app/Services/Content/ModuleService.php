<?php

namespace App\Services\Content;

use App\Models\Backend\Module;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ModuleService
{
    protected array $defaultModules = [
        'contact' => 'İletişim',
        'gallery' => 'Galeri',
        'newsletter' => 'Bülten',
        'slider' => 'Slider',
    ];

    public function list(): Collection
    {
        foreach ($this->defaultModules as $type => $name) {
            $this->getOrCreate($type, $name);
        }

        return Module::orderBy('name')->get();
    }

    public function getOrCreate(string $type, ?string $name = null): Module
    {
        return Module::firstOrCreate(
            ['type' => $type],
            [
                'name' => $name ?? $this->defaultModules[$type] ?? Str::title(str_replace('_', ' ', $type)),
                'content' => [],
                'is_active' => true,
            ]
        );
    }

    public function updateContent(Module $module, array $content, bool $isActive = true): Module
    {
        $module->fill([
            'content' => $content,
            'is_active' => $isActive,
        ])->save();

        return $module->refresh();
    }
}
