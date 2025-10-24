<?php

namespace App\Http\Controllers\Backend\Modules;

class ContactController extends SingleModuleController
{
    protected function moduleType(): string
    {
        return 'contact';
    }

    protected function viewName(): string
    {
        return 'admin.modules.contact.index';
    }

    protected function moduleName(): ?string
    {
        return 'İletişim';
    }
}
