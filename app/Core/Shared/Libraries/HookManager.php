<?php

namespace App\Core\Shared\Libraries;

/**
 * Hook Manager
 * 
 * Manages hook registration and execution for the plugin system.
 * Allows plugins to hook into core functionality at specific points.
 */
class HookManager
{
    /**
     * Registered hooks
     * 
     * @var array<string, array>
     */
    private static array $hooks = [];

    /**
     * Register a hook callback
     * 
     * @param string $hookName Hook identifier
     * @param callable $callback Function to execute
     * @param int $priority Execution priority (lower = earlier)
     * @return void
     */
    public static function register(string $hookName, callable $callback, int $priority = 10): void
    {
        if (!isset(self::$hooks[$hookName])) {
            self::$hooks[$hookName] = [];
        }

        self::$hooks[$hookName][] = [
            'callback' => $callback,
            'priority' => $priority
        ];

        // Sort by priority (ascending)
        usort(self::$hooks[$hookName], fn($a, $b) => $a['priority'] <=> $b['priority']);
    }

    /**
     * Execute all callbacks registered to a hook
     * 
     * @param string $hookName Hook identifier
     * @param mixed $data Data to pass through hooks
     * @return mixed Modified data after all hooks
     */
    public static function execute(string $hookName, mixed $data = null): mixed
    {
        if (!isset(self::$hooks[$hookName])) {
            return $data;
        }

        foreach (self::$hooks[$hookName] as $hook) {
            $data = call_user_func($hook['callback'], $data);
        }

        return $data;
    }

    /**
     * Check if a hook has any registered callbacks
     * 
     * @param string $hookName Hook identifier
     * @return bool
     */
    public static function has(string $hookName): bool
    {
        return isset(self::$hooks[$hookName]) && !empty(self::$hooks[$hookName]);
    }

    /**
     * Get all registered hooks
     * 
     * @return array<string, array>
     */
    public static function getAll(): array
    {
        return self::$hooks;
    }

    /**
     * Remove all callbacks from a hook
     * 
     * @param string $hookName Hook identifier
     * @return void
     */
    public static function clear(string $hookName): void
    {
        unset(self::$hooks[$hookName]);
    }

    /**
     * Remove all hooks
     * 
     * @return void
     */
    public static function clearAll(): void
    {
        self::$hooks = [];
    }
}
