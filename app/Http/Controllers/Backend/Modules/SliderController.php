<?php

namespace App\Http\Controllers\Backend\Modules;

class SliderController extends SingleModuleController
{
    protected function moduleType(): string
    {
        return 'slider';
    }

    protected function viewName(): string
    {
        return 'admin.modules.slider.index';
    }

    protected function moduleName(): ?string
    {
        return 'Slider';
    }
}
