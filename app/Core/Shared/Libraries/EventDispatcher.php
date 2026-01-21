<?php

namespace App\Core\Shared\Libraries;

/**
 * Event Dispatcher
 * 
 * Manages event listeners and dispatching for the event-driven architecture.
 * Allows loose coupling between modules through events.
 */
class EventDispatcher
{
    /**
     * Registered event listeners
     * 
     * @var array<string, array>
     */
    private static array $listeners = [];

    /**
     * Register an event listener
     * 
     * @param string $event Event name
     * @param callable $listener Callback function
     * @param int $priority Execution priority (lower = earlier)
     * @return void
     */
    public static function listen(string $event, callable $listener, int $priority = 10): void
    {
        if (!isset(self::$listeners[$event])) {
            self::$listeners[$event] = [];
        }

        self::$listeners[$event][] = [
            'listener' => $listener,
            'priority' => $priority
        ];

        // Sort by priority
        usort(self::$listeners[$event], fn($a, $b) => $a['priority'] <=> $b['priority']);
    }

    /**
     * Dispatch an event to all registered listeners
     * 
     * @param string $event Event name
     * @param mixed $data Event data
     * @return void
     */
    public static function dispatch(string $event, mixed $data = null): void
    {
        if (!isset(self::$listeners[$event])) {
            return;
        }

        foreach (self::$listeners[$event] as $item) {
            call_user_func($item['listener'], $data);
        }
    }

    /**
     * Check if an event has any listeners
     * 
     * @param string $event Event name
     * @return bool
     */
    public static function hasListeners(string $event): bool
    {
        return isset(self::$listeners[$event]) && !empty(self::$listeners[$event]);
    }

    /**
     * Get all registered listeners
     * 
     * @return array<string, array>
     */
    public static function getAll(): array
    {
        return self::$listeners;
    }

    /**
     * Remove all listeners for an event
     * 
     * @param string $event Event name
     * @return void
     */
    public static function forget(string $event): void
    {
        unset(self::$listeners[$event]);
    }

    /**
     * Remove all event listeners
     * 
     * @return void
     */
    public static function flush(): void
    {
        self::$listeners = [];
    }
}
