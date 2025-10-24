<?php

namespace App\Http\Controllers\Backend\Modules;

class NewsletterController extends SingleModuleController
{
    protected function moduleType(): string
    {
        return 'newsletter';
    }

    protected function viewName(): string
    {
        return 'admin.modules.newsletter.index';
    }

    protected function moduleName(): ?string
    {
        return 'Bülten';
    }
}
