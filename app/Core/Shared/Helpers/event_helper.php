<?php

/**
 * Event Helper Functions
 * 
 * Provides convenient functions for working with the event system.
 */

use App\Core\Shared\Libraries\EventDispatcher;

if (!function_exists('event')) {
    /**
     * Dispatch an event
     * 
     * @param string $name Event name
     * @param mixed $data Event data
     * @return void
     */
    function event(string $name, mixed $data = null): void
    {
        EventDispatcher::dispatch($name, $data);
    }
}

if (!function_exists('listen')) {
    /**
     * Register an event listener
     * 
     * @param string $name Event name
     * @param callable $listener Listener callback
     * @param int $priority Execution priority (lower = earlier)
     * @return void
     */
    function listen(string $name, callable $listener, int $priority = 10): void
    {
        EventDispatcher::listen($name, $listener, $priority);
    }
}

if (!function_exists('has_listeners')) {
    /**
     * Check if an event has any listeners
     * 
     * @param string $name Event name
     * @return bool
     */
    function has_listeners(string $name): bool
    {
        return EventDispatcher::hasListeners($name);
    }
}
