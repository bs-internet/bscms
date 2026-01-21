<?php

namespace App\Core\Modules\Content\Events;

use CodeIgniter\Events\Events;

class ContentEvents
{
    public static function register(): void
    {
        // Content deleted → clean up everything related
        Events::on('content_deleted', function (int $contentId) {
            $db = \Config\Database::connect();

            // 1. Component locations cleanup
            $db->table('component_locations')
                ->where('location_type', 'content')
                ->where('location_id', $contentId)
                ->delete();

            // 2. Content components cleanup
            $contentComponents = $db->table('content_components')
                ->where('content_id', $contentId)
                ->get()
                ->getResult();

            foreach ($contentComponents as $cc) {
                // Delete non-global component instances
                $instance = $db->table('component_instances')
                    ->where('id', $cc->component_instance_id)
                    ->get()
                    ->getRow();

                if ($instance && !$instance->is_global) {
                    // Delete instance data first (FK constraint)
                    $db->table('component_instance_data')
                        ->where('component_instance_id', $instance->id)
                        ->delete();

                    // Then delete instance
                    $db->table('component_instances')
                        ->where('id', $instance->id)
                        ->delete();
                }
            }

            // 3. Category relations cleanup (cascade should handle, but explicit is better)
            $db->table('content_categories')
                ->where('content_id', $contentId)
                ->delete();

            // 4. Meta cleanup (cascade should handle, but be explicit)
            $db->table('content_meta')
                ->where('content_id', $contentId)
                ->delete();

            // 5. Cache invalidation
            cache()->delete("content_{$contentId}");
            self::generateSitemap();
        });

        // Content updated → cache invalidation
        Events::on('content_updated', function (int $contentId, int $contentTypeId) {
            cache()->delete("content_{$contentId}");
            cache()->delete("content_list_{$contentTypeId}");
            self::generateSitemap();
        });

        // Content created → cache invalidation
        Events::on('content_created', function (int $contentId, int $contentTypeId) {
            cache()->delete("content_list_{$contentTypeId}");
            self::generateSitemap();
        });
    }

    protected static function generateSitemap(): void
    {
        try {
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
        } catch (\Exception $e) {
            log_message('error', '[Sitemap] ' . $e->getMessage());
        }
    }
}
