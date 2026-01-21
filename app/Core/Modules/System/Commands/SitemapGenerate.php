<?php

namespace App\Core\Modules\System\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SitemapGenerate extends BaseCommand
{
    protected $group = 'BSCMS';
    protected $name = 'sitemap:generate';
    protected $description = 'Sitemap.xml dosyasını oluşturur';

    public function run(array $params)
    {
        $contentRepository = service('contentRepository');
        $contentTypeRepository = service('contentTypeRepository');

        CLI::write('Sitemap oluşturuluyor...', 'yellow');

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        $url = $xml->addChild('url');
        $url->addChild('loc', base_url());
        $url->addChild('lastmod', date('Y-m-d'));
        $url->addChild('changefreq', 'daily');
        $url->addChild('priority', '1.0');

        $contents = $contentRepository->getAll(['status' => 'published']);
        $count = 0;

        foreach ($contents as $content) {
            $contentType = $contentTypeRepository->findById($content->content_type_id);
            
            if (!$contentType) {
                continue;
            }

            $url = $xml->addChild('url');
            
            if ($contentType->slug === 'page') {
                $loc = base_url('page/' . $content->slug);
            } else {
                $loc = base_url($contentType->slug . '/' . $content->slug);
            }
            
            $url->addChild('loc', htmlspecialchars($loc));
            $url->addChild('lastmod', date('Y-m-d', strtotime($content->updated_at ?? $content->created_at)));
            $url->addChild('changefreq', 'weekly');
            $url->addChild('priority', '0.8');
            
            $count++;
        }

        $sitemapPath = FCPATH . 'sitemap.xml';
        
        if (file_put_contents($sitemapPath, $xml->asXML())) {
            CLI::write('Sitemap başarıyla oluşturuldu!', 'green');
            CLI::write('Toplam URL: ' . ($count + 1), 'green');
            CLI::write('Konum: ' . $sitemapPath, 'green');
        } else {
            CLI::write('Sitemap oluşturulamadı!', 'red');
        }
    }
}
