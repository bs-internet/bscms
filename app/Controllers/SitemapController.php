<?php

namespace App\Controllers;

use App\Repositories\Interfaces\ContentRepositoryInterface;
use App\Repositories\Interfaces\ContentTypeRepositoryInterface;

class SitemapController extends BaseController
{
    protected ContentRepositoryInterface $contentRepository;
    protected ContentTypeRepositoryInterface $contentTypeRepository;

    public function __construct()
    {
        $this->contentRepository = service('contentRepository');
        $this->contentTypeRepository = service('contentTypeRepository');
    }

    public function index()
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        $this->addHomepage($xml);
        $this->addContents($xml);

        $this->response->setContentType('application/xml');
        return $this->response->setBody($xml->asXML());
    }

    protected function addHomepage(\SimpleXMLElement $xml): void
    {
        $url = $xml->addChild('url');
        $url->addChild('loc', base_url());
        $url->addChild('lastmod', date('Y-m-d'));
        $url->addChild('changefreq', 'daily');
        $url->addChild('priority', '1.0');
    }

    protected function addContents(\SimpleXMLElement $xml): void
    {
        $contents = $this->contentRepository->getAll(['status' => 'published']);

        foreach ($contents as $content) {
            $contentType = $this->contentTypeRepository->findById($content->content_type_id);
            
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
    }

    public function generate()
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        $this->addHomepage($xml);
        $this->addContents($xml);

        $sitemapPath = FCPATH . 'sitemap.xml';
        
        if (file_put_contents($sitemapPath, $xml->asXML())) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Sitemap başarıyla oluşturuldu.',
                'path' => base_url('sitemap.xml')
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Sitemap oluşturulamadı.'
        ])->setStatusCode(500);
    }
}