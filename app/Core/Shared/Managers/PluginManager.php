<?php

namespace App\Core\Shared\Managers;

use App\Core\Shared\Interfaces\PluginInterface;
use Config\Plugins;

class PluginManager
{
    protected $activePlugins = [];
    protected $pluginPath;

    public function __construct()
    {
        $this->pluginPath = APPPATH . 'Plugins/'; // Default to app/Plugins for now

        // Optional: Support writable/plugins if needed later
        // $writablePath = ROOTPATH . 'writable/plugins/';
        // if (is_dir($writablePath)) { ... }

        $this->loadActivePlugins();
    }

    protected function loadActivePlugins()
    {
        $config = config('Plugins');
        $this->activePlugins = $config->activePlugins ?? []; // List of folder names
    }

    public function bootAll()
    {
        foreach ($this->activePlugins as $pluginName) {
            $this->bootPlugin($pluginName);
        }
    }

    protected function bootPlugin($pluginName)
    {
        $className = "App\\Plugins\\{$pluginName}\\Plugin";

        if (class_exists($className)) {
            $plugin = new $className();
            if ($plugin instanceof PluginInterface) {
                $plugin->boot();
            }
        }
    }

    public function getAllPlugins()
    {
        // Scan directory for plugins
        $plugins = [];
        if (!is_dir($this->pluginPath))
            return [];

        $dirs = scandir($this->pluginPath);
        foreach ($dirs as $dir) {
            if ($dir === '.' || $dir === '..')
                continue;
            if (is_dir($this->pluginPath . $dir) && file_exists($this->pluginPath . $dir . '/Plugin.php')) {
                $plugins[] = [
                    'name' => $dir,
                    'active' => in_array($dir, $this->activePlugins),
                    'path' => $this->pluginPath . $dir
                ];
            }
        }
        return $plugins;
    }
}
