<?php

namespace App\Core\Shared\Libraries;

use App\Core\Shared\Interfaces\PluginInterface;
use App\Core\Shared\Exceptions\PluginException;

/**
 * Plugin Manager
 * 
 * Manages plugin discovery, activation, and lifecycle.
 */
class PluginManager
{
    /**
     * Discovered plugins
     * 
     * @var array<string, string> Plugin name => Plugin class
     */
    private array $plugins = [];

    /**
     * Active plugin instances
     * 
     * @var array<string, PluginInterface>
     */
    private array $activePlugins = [];

    /**
     * Plugin base path
     */
    private string $pluginPath;

    public function __construct()
    {
        $this->pluginPath = APPPATH . 'Plugins/';
    }

    /**
     * Discover all available plugins
     * 
     * @return void
     */
    public function discover(): void
    {
        if (!is_dir($this->pluginPath)) {
            return;
        }

        $pluginDirs = glob($this->pluginPath . '*', GLOB_ONLYDIR);

        foreach ($pluginDirs as $dir) {
            $pluginName = basename($dir);
            $pluginClass = $this->loadPluginClass($pluginName);

            if ($pluginClass) {
                $this->plugins[$pluginName] = $pluginClass;
            }
        }
    }

    /**
     * Load plugin class
     * 
     * @param string $pluginName
     * @return string|null
     */
    private function loadPluginClass(string $pluginName): ?string
    {
        $pluginFile = $this->pluginPath . $pluginName . '/Plugin.php';

        if (!file_exists($pluginFile)) {
            return null;
        }

        $pluginClass = "App\\Plugins\\{$pluginName}\\Plugin";

        if (!class_exists($pluginClass)) {
            return null;
        }

        return $pluginClass;
    }

    /**
     * Activate a plugin
     * 
     * @param string $pluginName
     * @return void
     * @throws PluginException
     */
    public function activate(string $pluginName): void
    {
        if (!isset($this->plugins[$pluginName])) {
            throw new PluginException("Plugin not found: {$pluginName}");
        }

        if (isset($this->activePlugins[$pluginName])) {
            return; // Already active
        }

        $pluginClass = $this->plugins[$pluginName];
        $plugin = new $pluginClass();

        if (!$plugin instanceof PluginInterface) {
            throw new PluginException("Plugin must implement PluginInterface: {$pluginName}");
        }

        // Register and boot plugin
        $plugin->register();
        $plugin->boot();

        $this->activePlugins[$pluginName] = $plugin;

        log_message('info', "Plugin activated: {$pluginName}");
    }

    /**
     * Deactivate a plugin
     * 
     * @param string $pluginName
     * @return void
     */
    public function deactivate(string $pluginName): void
    {
        if (!isset($this->activePlugins[$pluginName])) {
            return;
        }

        unset($this->activePlugins[$pluginName]);

        log_message('info', "Plugin deactivated: {$pluginName}");
    }

    /**
     * Get all discovered plugins
     * 
     * @return array<string, string>
     */
    public function getDiscovered(): array
    {
        return $this->plugins;
    }

    /**
     * Get all active plugins
     * 
     * @return array<string, PluginInterface>
     */
    public function getActive(): array
    {
        return $this->activePlugins;
    }

    /**
     * Check if a plugin is active
     * 
     * @param string $pluginName
     * @return bool
     */
    public function isActive(string $pluginName): bool
    {
        return isset($this->activePlugins[$pluginName]);
    }

    /**
     * Get plugin metadata
     * 
     * @param string $pluginName
     * @return array|null
     */
    public function getMetadata(string $pluginName): ?array
    {
        $configFile = $this->pluginPath . $pluginName . '/Config/plugin.php';

        if (!file_exists($configFile)) {
            return null;
        }

        return include $configFile;
    }
}
