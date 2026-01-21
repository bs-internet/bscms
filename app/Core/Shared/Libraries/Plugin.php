<?php

namespace App\Core\Shared\Libraries;

use App\Core\Shared\Interfaces\PluginInterface;

/**
 * Base Plugin Class
 * 
 * Provides common functionality for all plugins.
 * Plugins should extend this class for convenience.
 */
abstract class Plugin implements PluginInterface
{
    /**
     * Plugin name
     */
    protected string $name;

    /**
     * Plugin version
     */
    protected string $version;

    /**
     * Plugin author
     */
    protected string $author;

    /**
     * Plugin description
     */
    protected string $description;

    /**
     * Plugin configuration
     */
    protected array $config = [];

    /**
     * Register plugin hooks and listeners
     * 
     * Override this method to register hooks and event listeners
     */
    abstract public function register(): void;

    /**
     * Boot the plugin
     * 
     * Override this method to load routes, views, etc.
     */
    abstract public function boot(): void;

    /**
     * Get plugin name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get plugin version
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Get plugin author
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Get plugin description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Register a hook
     * 
     * @param string $name Hook name
     * @param callable $callback Callback function
     * @param int $priority Priority (lower = earlier)
     * @return void
     */
    protected function addHook(string $name, callable $callback, int $priority = 10): void
    {
        HookManager::register($name, $callback, $priority);
    }

    /**
     * Register an event listener
     * 
     * @param string $event Event name
     * @param callable $listener Listener callback
     * @param int $priority Priority (lower = earlier)
     * @return void
     */
    protected function listen(string $event, callable $listener, int $priority = 10): void
    {
        EventDispatcher::listen($event, $listener, $priority);
    }

    /**
     * Load plugin configuration
     * 
     * @return array
     */
    protected function loadConfig(): array
    {
        $configFile = APPPATH . 'Plugins/' . $this->getPluginSlug() . '/Config/plugin.php';

        if (file_exists($configFile)) {
            return include $configFile;
        }

        return [];
    }

    /**
     * Get plugin slug (directory name)
     * 
     * @return string
     */
    protected function getPluginSlug(): string
    {
        $reflection = new \ReflectionClass($this);
        $namespace = $reflection->getNamespaceName();

        // Extract plugin name from namespace (App\Plugins\PluginName)
        $parts = explode('\\', $namespace);
        return end($parts);
    }
}
