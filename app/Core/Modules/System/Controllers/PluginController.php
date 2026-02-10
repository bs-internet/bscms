<?php

namespace App\Core\Modules\System\Controllers;

use App\Core\Shared\Controllers\BaseController;
use Config\Plugins;

class PluginController extends BaseController
{
    protected $pluginManager;

    public function __construct()
    {
        $this->pluginManager = service('pluginManager');
    }

    public function index()
    {
        $data = [
            'title' => 'Eklenti Yönetimi',
            'plugins' => $this->pluginManager->getAllPlugins()
        ];

        return view('App\Core\Modules\System\Views\plugins\index', $data);
    }

    public function toggle($pluginName)
    {
        // Security check: simple validation
        if (!ctype_alnum($pluginName)) {
            return redirect()->back()->with('error', 'Geçersiz eklenti adı.');
        }

        $config = config('Plugins');
        $activePlugins = $config->activePlugins;

        if (in_array($pluginName, $activePlugins)) {
            // Deactivate
            $key = array_search($pluginName, $activePlugins);
            unset($activePlugins[$key]);
            $message = 'Eklenti pasifleştirildi.';
        } else {
            // Activate
            $activePlugins[] = $pluginName;
            $message = 'Eklenti aktifleştirildi.';
        }

        // Save config
        $this->saveConfig(array_values($activePlugins));

        return redirect()->back()->with('success', $message);
    }

    protected function saveConfig(array $activePlugins)
    {
        $file = APPPATH . 'Config/Plugins.php';
        $content = "<?php\n\nnamespace Config;\n\nuse CodeIgniter\Config\BaseConfig;\n\nclass Plugins extends BaseConfig\n{\n    /**\n     * List of active plugins (Folder names in app/Plugins)\n     */\n    public array \$activePlugins = [\n";

        foreach ($activePlugins as $plugin) {
            $content .= "        '$plugin',\n";
        }

        $content .= "    ];\n}\n";

        file_put_contents($file, $content);
    }
}
