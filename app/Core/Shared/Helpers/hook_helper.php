<?php

/**
 * Hook Helper Functions
 * 
 * Provides convenient functions for working with the hook system.
 */

use App\Core\Shared\Libraries\HookManager;

if (!function_exists('hook')) {
    /**
     * Execute a hook
     * 
     * @param string $name Hook name
     * @param mixed $data Data to pass through hooks
     * @return mixed Modified data
     */
    function hook(string $name, mixed $data = null): mixed
    {
        return HookManager::execute($name, $data);
    }
}

if (!function_exists('add_hook')) {
    /**
     * Register a hook callback
     * 
     * @param string $name Hook name
     * @param callable $callback Callback function
     * @param int $priority Execution priority (lower = earlier)
     * @return void
     */
    function add_hook(string $name, callable $callback, int $priority = 10): void
    {
        HookManager::register($name, $callback, $priority);
    }
}

if (!function_exists('has_hook')) {
    /**
     * Check if a hook has any registered callbacks
     * 
     * @param string $name Hook name
     * @return bool
     */
    function has_hook(string $name): bool
    {
        return HookManager::has($name);
    }
}

if (!function_exists('remove_hook')) {
    /**
     * Remove all callbacks from a hook
     * 
     * @param string $name Hook name
     * @return void
     */
    function remove_hook(string $name): void
    {
        HookManager::clear($name);
    }
}
