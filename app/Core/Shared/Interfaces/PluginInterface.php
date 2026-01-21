<?php

namespace App\Core\Shared\Interfaces;

/**
 * Plugin Interface
 * 
 * All plugins must implement this interface to be recognized by the PluginManager.
 */
interface PluginInterface
{
    /**
     * Register plugin hooks and event listeners
     * 
     * This method is called when the plugin is activated.
     * Use this to register hooks and event listeners.
     * 
     * @return void
     */
    public function register(): void;

    /**
     * Boot the plugin
     * 
     * This method is called after registration.
     * Use this to load routes, views, and other resources.
     * 
     * @return void
     */
    public function boot(): void;

    /**
     * Get plugin name
     * 
     * @return string
     */
    public function getName(): string;

    /**
     * Get plugin version
     * 
     * @return string
     */
    public function getVersion(): string;

    /**
     * Get plugin author
     * 
     * @return string
     */
    public function getAuthor(): string;

    /**
     * Get plugin description
     * 
     * @return string
     */
    public function getDescription(): string;
}
