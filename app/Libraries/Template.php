<?php

namespace App\Libraries;

use App\Repositories\Interfaces\ContentRepositoryInterface;
use App\Repositories\Interfaces\ContentTypeRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use App\Repositories\Interfaces\MenuItemRepositoryInterface;
use App\Repositories\Interfaces\SettingRepositoryInterface;

class Template
{
    protected ContentRepositoryInterface $contentRepository;
    protected ContentTypeRepositoryInterface $contentTypeRepository;
    protected CategoryRepositoryInterface $categoryRepository;
    protected MenuRepositoryInterface $menuRepository;
    protected MenuItemRepositoryInterface $menuItemRepository;
    protected SettingRepositoryInterface $settingRepository;

    protected string $themePath;
    protected ?object $currentContent = null;
    protected ?object $currentContentType = null;
    protected array $templateData = [];

    public function __construct(
        ContentRepositoryInterface $contentRepository,
        ContentTypeRepositoryInterface $contentTypeRepository,
        CategoryRepositoryInterface $categoryRepository,
        MenuRepositoryInterface $menuRepository,
        MenuItemRepositoryInterface $menuItemRepository,
        SettingRepositoryInterface $settingRepository
    ) {
        $this->contentRepository = $contentRepository;
        $this->contentTypeRepository = $contentTypeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->menuRepository = $menuRepository;
        $this->menuItemRepository = $menuItemRepository;
        $this->settingRepository = $settingRepository;

        $this->themePath = APPPATH . '../themes/default/';
    }

    public function setCurrentContent(?object $content): void
    {
        $this->currentContent = $content;
        
        if ($content && isset($content->content_type_id)) {
            $this->currentContentType = $this->contentTypeRepository->findById($content->content_type_id);
        }
    }

    public function setTemplateData(array $data): void
    {
        $this->templateData = $data;
    }

    public function render(string $templateType, array $data = []): string
    {
        $data = array_merge($this->templateData, $data);
        
        $templateFile = $this->findTemplate($templateType);
        
        if (!$templateFile) {
            return $this->render404();
        }

        return $this->load($templateFile, $data);
    }

    public function loadHeader(array $data = []): string
    {
        $headerFile = $this->themePath . 'header.php';
        
        if (!file_exists($headerFile)) {
            return '';
        }

        return $this->load($headerFile, $data);
    }

    public function loadFooter(array $data = []): string
    {
        $footerFile = $this->themePath . 'footer.php';
        
        if (!file_exists($footerFile)) {
            return '';
        }

        return $this->load($footerFile, $data);
    }

    public function getSetting(string $key, $default = null)
    {
        $cacheManager = new \App\Libraries\CacheManager();
        $value = $cacheManager->getSetting($key);
        return $value ?? $default;
    }

    public function getMenu(string $location): array
    {
        $cacheManager = new \App\Libraries\CacheManager();
        return $cacheManager->getMenu($location);
    }

    protected function findTemplate(string $templateType): ?string
    {
        $templates = [];

        switch ($templateType) {
            case 'page':
                if ($this->currentContent) {
                    $templates[] = 'page-' . $this->currentContent->slug . '.php';
                }
                $templates[] = 'page.php';
                $templates[] = 'index.php';
                break;

            case 'single':
                if ($this->currentContentType) {
                    $templates[] = 'single-' . $this->currentContentType->slug . '.php';
                }
                $templates[] = 'single.php';
                $templates[] = 'index.php';
                break;

            case 'list':
                if ($this->currentContentType) {
                    $templates[] = 'list-' . $this->currentContentType->slug . '.php';
                }
                $templates[] = 'index.php';
                break;

            case '404':
                $templates[] = '404.php';
                $templates[] = 'index.php';
                break;

            default:
                $templates[] = 'index.php';
                break;
        }

        foreach ($templates as $template) {
            $file = $this->themePath . $template;
            if (file_exists($file)) {
                return $file;
            }
        }

        return null;
    }

    protected function load(string $file, array $data = []): string
    {
        if (!file_exists($file)) {
            return '';
        }

        extract($data);
        
        ob_start();
        include $file;
        return ob_get_clean();
    }

    protected function render404(): string
    {
        $templateFile = $this->findTemplate('404');
        
        if (!$templateFile) {
            return '<h1>404 - Sayfa Bulunamadı</h1>';
        }

        return $this->load($templateFile);
    }

    protected function buildMenuTree(array $items, ?int $parentId = null): array
    {
        $tree = [];
        
        foreach ($items as $item) {
            if ($item->parent_id == $parentId) {
                $node = [
                    'id' => $item->id,
                    'title' => $item->title,
                    'url' => $item->url,
                    'children' => $this->buildMenuTree($items, $item->id)
                ];
                $tree[] = $node;
            }
        }
        
        return $tree;
    }
}