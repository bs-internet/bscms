<?php

namespace App\Events;

use CodeIgniter\Events\Events;

class ContentEvents
{
    public static function register(): void
    {
        Events::on('content_created', function ($contentId) {
            self::generateSitemap();
        });

        Events::on('content_updated', function ($contentId) {
            self::generateSitemap();
        });

        Events::on('content_deleting', function ($contentId) {
            self::cleanupComponentInstances($contentId);
        });

        Events::on('content_deleted', function ($contentId) {
            self::cleanupComponentLocations($contentId);
            self::generateSitemap();
        });
    }

    protected static function cleanupComponentInstances($contentId): void
    {
        $db = \Config\Database::connect();

        // Find instances attached to this content
        // We must do this BEFORE content is deleted because content_components table 
        // has ON DELETE CASCADE on content_id.
        $contentComponents = $db->table('content_components')
            ->where('content_id', $contentId)
            ->get()
            ->getResult();

        foreach ($contentComponents as $cc) {
            // Check if instance is global
            $instance = $db->table('component_instances')
                ->where('id', $cc->component_instance_id)
                ->get()
                ->getRow();

            if ($instance && !$instance->is_global) {
                // Delete instance data
                $db->table('component_instance_data')
                    ->where('component_instance_id', $instance->id)
                    ->delete();

                // Delete instance
                $db->table('component_instances')
                    ->where('id', $instance->id)
                    ->delete();
            }
        }
    }

    protected static function cleanupComponentLocations($contentId): void
    {
        $db = \Config\Database::connect();

        // Delete from component_locations
        // This table does NOT have a foreign key to contents, so we must clean manually.
        $db->table('component_locations')
            ->where('location_type', 'content')
            ->where('location_id', $contentId)
            ->delete();
    }

    protected static function generateSitemap(): void
    {
        $contentRepository = service('contentRepository');
        $contentTypeRepository = service('contentTypeRepository');

        // ... Existing sitemap logic ...
        // For brevity in this replacement, assume we keep the sitemap logic. 
        // BUT wait, I should not truncate it if I am replacing the whole file content or a block.
        // I am replacing the WHOLE class? The StartLine/EndLine arguments target the whole class?
        // No, I should replace carefully.
        // Let's rewrite the FULL class to be safe and avoid truncation issues again.

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