<?php

namespace App\Libraries;

use App\Repositories\Interfaces\ContentRepositoryInterface;
use App\Repositories\Interfaces\ContentMetaRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use App\Models\ContentCategoryModel;
use App\Enums\ContentStatus;

class ContentQuery
{
    protected ContentRepositoryInterface $contentRepository;
    protected ContentMetaRepositoryInterface $contentMetaRepository;
    protected CategoryRepositoryInterface $categoryRepository;
    protected MediaRepositoryInterface $mediaRepository;
    protected ContentCategoryModel $contentCategoryModel;
    protected Loop $loop;

    protected array $args = [];
    protected array $results = [];

    public function __construct(array $args = [])
    {
        $this->contentRepository = service('contentRepository');
        $this->contentMetaRepository = service('contentMetaRepository');
        $this->categoryRepository = service('categoryRepository');
        $this->mediaRepository = service('mediaRepository');
        $this->contentCategoryModel = service('contentCategoryModel');
        
        $this->loop = new Loop(
            $this->contentRepository,
            $this->contentMetaRepository,
            $this->categoryRepository,
            $this->mediaRepository,
            $this->contentCategoryModel
        );

        $this->args = $args;
        $this->query();
    }

    protected function query(): void
    {
        $filters = [];

        if (isset($this->args['content_type'])) {
            $contentTypeRepository = service('contentTypeRepository');
            $contentType = $contentTypeRepository->findBySlug($this->args['content_type']);
            
            if ($contentType) {
                $filters['content_type_id'] = $contentType->id;
            }
        }

        if (isset($this->args['status'])) {
            if (is_string($this->args['status'])) {
                $filters['status'] = ContentStatus::from($this->args['status']);
            } else {
                $filters['status'] = $this->args['status'];
            }
        }

        if (isset($this->args['limit'])) {
            $filters['limit'] = $this->args['limit'];
        }

        if (isset($this->args['offset'])) {
            $filters['offset'] = $this->args['offset'];
        }

        if (isset($this->args['order_by'])) {
            $filters['order_by'] = $this->args['order_by'];
        }

        if (isset($this->args['order'])) {
            $filters['order'] = $this->args['order'];
        }

        if (isset($this->args['category'])) {
            $this->results = $this->contentRepository->getByCategory($this->args['category'], $filters);
        } else {
            $this->results = $this->contentRepository->getAll($filters);
        }

        $this->loop->setContents($this->results);
    }

    public function havePosts(): bool
    {
        return $this->loop->havePosts();
    }

    public function thePost(): void
    {
        $this->loop->thePost();
    }

    public function rewindPosts(): void
    {
        $this->loop->rewindPosts();
    }

    public function getResults(): array
    {
        return $this->results;
    }
}