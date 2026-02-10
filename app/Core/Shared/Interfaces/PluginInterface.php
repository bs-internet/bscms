<?php

namespace App\Core\Shared\Interfaces;

interface PluginInterface
{
    /**
     * Called when the plugin is activated.
     * Run migration or setup logic here.
     */
    public function install(): void;

    /**
     * Called when the plugin is deactivated/uninstalled.
     * Clean up data or settings here.
     */
    public function uninstall(): void;

    /**
     * Called on efficient application start.
     * Register events, hooks, and routes here.
     */
    public function boot(): void;
}
