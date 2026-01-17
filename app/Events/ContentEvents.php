<?php

namespace App\Events;

use CodeIgniter\Events\Events;

class ContentEvents
{
    public static function register(): void
    {
        Events::on('content_created', function($contentId) {
            self::generateSitemap();
        });

        Events::on('content_updated', function($contentId) {
            self::generateSitemap();
        });

        Events::on('content_deleted', function($contentId) {
            self::generateSitemap();
        });
    }

    protected static function generateSitemap(): void
    {
        $contentRepository = service('contentRepository');
        $contentTypeRepository = service('contentTypeRepository');

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        $url = $xml->addChild('url');
        $url->addChild('loc', base_url());
        $url->addChild('lastmod', date('Y-m-d'));
        $url->addChild('changefreq', 'daily');
        $url->addChild('priority', '1.0');

        $contents = $contentRepository->getAll(['status' => 'published']);

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
        }

        $sitemapPath = FCPATH . 'sitemap.xml';
        file_put_contents($sitemapPath, $xml->asXML());
    }
}