<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Plugins extends BaseConfig
{
    /**
     * List of active plugins (Folder names in app/Plugins)
     */
    public array $activePlugins = [
        'HelloWorld',
    ];
}
