<?php

namespace App\Core\Modules\Content\Controllers;

use App\Core\Shared\Controllers\BaseController;
use App\Core\Modules\Content\Repositories\Interfaces\ContentTypeRepositoryInterface;
use App\Core\Modules\Content\Repositories\Interfaces\ContentRepositoryInterface;
use App\Core\Modules\Category\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Core\Modules\Content\Enums\ContentStatus;

class FrontendController extends BaseController
{
    protected ContentTypeRepositoryInterface $contentTypeRepository;
    protected ContentRepositoryInterface $contentRepository;
    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct()
    {
        $this->contentTypeRepository = service('contentTypeRepository');
        $this->contentRepository = service('contentRepository');
        $this->categoryRepository = service('categoryRepository');
    }

    public function index()
    {
        $template = service('template');
        return $template->render('page');
    }

    public function page(string $slug)
    {
        $content = $this->contentRepository->findBySlug($slug);

        if (!$content || !$content->isPublished()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $loop = service('loop');
        $loop->setContents([$content]);

        $template = service('template');
        $template->setCurrentContent($content);

        return $template->render('page', ['content' => $content]);
    }

    public function single(string $contentTypeSlug, string $slug)
    {
        $contentType = $this->contentTypeRepository->findBySlug($contentTypeSlug);

        if (!$contentType || !$contentType->visible) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $content = $this->contentRepository->findBySlug($slug);

        if (!$content || $content->content_type_id !== $contentType->id || !$content->isPublished()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $loop = service('loop');
        $loop->setContents([$content]);

        $template = service('template');
        $template->setCurrentContent($content);

        return $template->render('single', ['content' => $content, 'contentType' => $contentType]);
    }

    public function list(string $contentTypeSlug)
    {
        $contentType = $this->contentTypeRepository->findBySlug($contentTypeSlug);

        if (!$contentType || !$contentType->visible) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $contents = $this->contentRepository->getAll([
            'content_type_id' => $contentType->id,
            'status' => ContentStatus::PUBLISHED
        ]);

        $loop = service('loop');
        $loop->setContents($contents);

        $template = service('template');
        return $template->render('list', ['contents' => $contents, 'contentType' => $contentType]);
    }

    public function category(string $contentTypeSlug, string $categorySlug)
    {
        $contentType = $this->contentTypeRepository->findBySlug($contentTypeSlug);

        if (!$contentType || !$contentType->visible) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $category = $this->categoryRepository->findBySlug($categorySlug, $contentType->id);

        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $contents = $this->contentRepository->getAll([
            'content_type_id' => $contentType->id,
            'status' => ContentStatus::PUBLISHED,
            'category_id' => $category->id
        ]);

        $loop = service('loop');
        $loop->setContents($contents);

        $template = service('template');
        return $template->render('list', [
            'contents' => $contents,
            'contentType' => $contentType,
            'category' => $category
        ]);
    }
}


