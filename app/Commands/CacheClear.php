<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CacheClear extends BaseCommand
{
    protected $group = 'BSCMS';
    protected $name = 'cache:clear';
    protected $description = 'Tüm cache\'i temizler';

    public function run(array $params)
    {
        CLI::write('Cache temizleniyor...', 'yellow');

        $cache = \Config\Services::cache();
        
        if ($cache->clean()) {
            CLI::write('Cache başarıyla temizlendi!', 'green');
        } else {
            CLI::write('Cache temizlenemedi!', 'red');
        }
    }
}