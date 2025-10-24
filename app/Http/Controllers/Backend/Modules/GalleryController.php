<?php

namespace App\Http\Controllers\Backend\Modules;

class GalleryController extends SingleModuleController
{
    protected function moduleType(): string
    {
        return 'gallery';
    }

    protected function viewName(): string
    {
        return 'admin.modules.gallery.index';
    }

    protected function moduleName(): ?string
    {
        return 'Galeri';
    }
}
