<?php

namespace App\Core\Modules\System\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class PublishAssets extends BaseCommand
{
    protected $group = 'BSCMS';
    protected $name = 'assets:publish';
    protected $description = 'Modüler asset dosyalarını public klasörüne kopyalar';

    public function run(array $params)
    {
        CLI::write('Assetler yayınlanıyor...', 'yellow');

        $this->publishSharedAssets();
        $this->publishModuleAssets();

        CLI::write('Assetler başarıyla yayınlandı!', 'green');
    }

    protected function publishSharedAssets()
    {
        $source = APPPATH . 'Core/Shared/Assets';
        $target = FCPATH . 'assets/admin';

        if (is_dir($source)) {
            $this->copyRecursive($source, $target);
            CLI::write('Paylaşılan assetler yayınlandı.', 'cyan');
        }
    }

    protected function publishModuleAssets()
    {
        $modulesPath = APPPATH . 'Core/Modules';
        $modules = array_filter(glob($modulesPath . '/*'), 'is_dir');

        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);
            $source = $modulePath . '/Assets';

            if (!is_dir($source))
                continue;

            // Auth assets special case for current structure (they go to admin)
            if ($moduleName === 'Auth') {
                $target = FCPATH . 'assets/admin';
            } else {
                $target = FCPATH . 'assets/modules/' . strtolower($moduleName);
            }

            $this->copyRecursive($source, $target);
            CLI::write(sprintf('%s modülü assetleri yayınlandı.', $moduleName), 'cyan');
        }
    }

    protected function copyRecursive($source, $target)
    {
        if (!is_dir($target)) {
            mkdir($target, 0755, true);
        }

        $dir = opendir($source);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($source . '/' . $file)) {
                    $this->copyRecursive($source . '/' . $file, $target . '/' . $file);
                } else {
                    if ($file !== '.gitkeep') {
                        copy($source . '/' . $file, $target . '/' . $file);
                    }
                }
            }
        }
        closedir($dir);
    }
}
