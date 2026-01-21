<?php

namespace App\Core\Modules\System\Controllers;

use App\Core\Modules\System\Repositories\Interfaces\ContentRepositoryInterface;
use App\Core\Modules\System\Repositories\Interfaces\ContentTypeRepositoryInterface;

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
        $sitemapPath = FCPATH . 'sitemap.xml';
        $sitemapExists = file_exists($sitemapPath);
        $lastGenerated = $sitemapExists ? date('d.m.Y H:i:s', filemtime($sitemapPath)) : null;

        return view('App\Core\Modules\System\Views/index', [
            'sitemapExists' => $sitemapExists,
            'lastGenerated' => $lastGenerated
        ]);
    }

    public function generate()
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        $this->addHomepage($xml);
        $this->addContents($xml);

        $sitemapPath = FCPATH . 'sitemap.xml';
        
        if (file_put_contents($sitemapPath, $xml->asXML())) {
            return redirect()->to('/admin/sitemap')->with('success', 'Sitemap başarıyla oluşturuldu.');
        }

        return redirect()->back()->with('error', 'Sitemap oluşturulamadı.');
    }

    public function view()
    {
        $sitemapPath = FCPATH . 'sitemap.xml';
        
        if (!file_exists($sitemapPath)) {
            return redirect()->to('/admin/sitemap')->with('error', 'Sitemap bulunamadı.');
        }

        $content = file_get_contents($sitemapPath);

        return view('App\Core\Modules\System\Views/view', ['content' => $content]);
    }

    public function delete()
    {
        $sitemapPath = FCPATH . 'sitemap.xml';
        
        if (!file_exists($sitemapPath)) {
            return redirect()->to('/admin/sitemap')->with('error', 'Sitemap bulunamadı.');
        }

        if (unlink($sitemapPath)) {
            return redirect()->to('/admin/sitemap')->with('success', 'Sitemap başarıyla silindi.');
        }

        return redirect()->back()->with('error', 'Sitemap silinemedi.');
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
}
