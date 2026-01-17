<?php

namespace App\Controllers;

use App\Libraries\Template;
use App\Libraries\Loop;
use App\Repositories\Interfaces\ContentRepositoryInterface;
use App\Repositories\Interfaces\ContentTypeRepositoryInterface;
use App\Repositories\Interfaces\ContentMetaRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class FrontendController extends BaseController
{
    protected Template $template;
    protected Loop $loop;
    protected ContentRepositoryInterface $contentRepository;
    protected ContentTypeRepositoryInterface $contentTypeRepository;
    protected ContentMetaRepositoryInterface $contentMetaRepository;
    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct()
    {
        $this->template = service('template');
        $this->loop = service('loop');
        $this->contentRepository = service('contentRepository');
        $this->contentTypeRepository = service('contentTypeRepository');
        $this->contentMetaRepository = service('contentMetaRepository');
        $this->categoryRepository = service('categoryRepository');
    }

    public function index()
    {
        $contents = $this->contentRepository->getAll([
            'status' => 'published',
            'limit' => 10,
            'order_by' => 'created_at',
            'order' => 'DESC'
        ]);

        $this->loop->setContents($contents);

        $output = $this->template->render('index');
        return $this->response->setBody($output);
    }

    public function page(string $slug)
    {
        $content = $this->contentRepository->findBySlug($slug);

        if (!$content || $content->status !== 'published') {
            return $this->show404();
        }

        $this->template->setCurrentContent($content);
        $this->loop->setContents([$content]);

        $output = $this->template->render('page', ['content' => $content]);
        return $this->response->setBody($output);
    }

    public function single(string $contentTypeSlug, string $slug)
    {
        $contentType = $this->contentTypeRepository->findBySlug($contentTypeSlug);

        if (!$contentType) {
            return $this->show404();
        }

        $content = $this->contentRepository->findBySlug($slug, $contentType->id);

        if (!$content || $content->status !== 'published') {
            return $this->show404();
        }

        $this->template->setCurrentContent($content);
        $this->loop->setContents([$content]);

        $output = $this->template->render('single', ['content' => $content]);
        return $this->response->setBody($output);
    }

    public function list(string $contentTypeSlug)
    {
        $contentType = $this->contentTypeRepository->findBySlug($contentTypeSlug);

        if (!$contentType) {
            return $this->show404();
        }

        $categoryId = $this->request->getGet('category');

        if ($categoryId) {
            $contents = $this->contentRepository->getByCategory($categoryId, [
                'status' => 'published',
                'order_by' => 'created_at',
                'order' => 'DESC'
            ]);
        } else {
            $contents = $this->contentRepository->getByContentType($contentType->id, [
                'status' => 'published',
                'order_by' => 'created_at',
                'order' => 'DESC'
            ]);
        }

        $this->template->setCurrentContent(null);
        $this->loop->setContents($contents);

        $output = $this->template->render('list', [
            'contentType' => $contentType,
            'contents' => $contents
        ]);
        
        return $this->response->setBody($output);
    }

    public function category(string $contentTypeSlug, string $categorySlug)
    {
        $contentType = $this->contentTypeRepository->findBySlug($contentTypeSlug);

        if (!$contentType) {
            return $this->show404();
        }

        $category = $this->categoryRepository->findBySlug($categorySlug, $contentType->id);

        if (!$category) {
            return $this->show404();
        }

        $contents = $this->contentRepository->getByCategory($category->id, [
            'status' => 'published',
            'order_by' => 'created_at',
            'order' => 'DESC'
        ]);

        $this->template->setCurrentContent(null);
        $this->loop->setContents($contents);

        $output = $this->template->render('list', [
            'contentType' => $contentType,
            'category' => $category,
            'contents' => $contents
        ]);
        
        return $this->response->setBody($output);
    }

    protected function show404()
    {
        $this->response->setStatusCode(404);
        $output = $this->template->render('404');
        return $this->response->setBody($output);
    }
}