<?php

namespace App\Plugins\HelloWorld;

use App\Core\Shared\Interfaces\PluginInterface;
use CodeIgniter\Events\Events;

class Plugin implements PluginInterface
{
    public function install(): void
    {
        // Migration logic would go here
    }

    public function uninstall(): void
    {
        // Cleanup logic would go here
    }

    public function boot(): void
    {
        // Hook into the system
        Events::on('post_controller_constructor', function () {
            log_message('info', 'HelloWorld Plugin: I am alive!');
        });
    }
}
