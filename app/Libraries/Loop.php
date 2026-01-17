<?php

namespace App\Libraries;

use App\Repositories\Interfaces\ContentRepositoryInterface;
use App\Repositories\Interfaces\ContentMetaRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use App\Models\ContentCategoryModel;

class Loop
{
    protected ContentRepositoryInterface $contentRepository;
    protected ContentMetaRepositoryInterface $contentMetaRepository;
    protected CategoryRepositoryInterface $categoryRepository;
    protected MediaRepositoryInterface $mediaRepository;
    protected ContentCategoryModel $contentCategoryModel;

    protected array $contents = [];
    protected int $currentIndex = -1;
    protected ?object $currentContent = null;
    protected array $currentMeta = [];
    protected array $currentCategories = [];

    public function __construct(
        ContentRepositoryInterface $contentRepository,
        ContentMetaRepositoryInterface $contentMetaRepository,
        CategoryRepositoryInterface $categoryRepository,
        MediaRepositoryInterface $mediaRepository,
        ContentCategoryModel $contentCategoryModel
    ) {
        $this->contentRepository = $contentRepository;
        $this->contentMetaRepository = $contentMetaRepository;
        $this->categoryRepository = $categoryRepository;
        $this->mediaRepository = $mediaRepository;
        $this->contentCategoryModel = $contentCategoryModel;
    }

    public function setContents(array $contents): void
    {
        $this->contents = $contents;
        $this->currentIndex = -1;
        $this->currentContent = null;
        $this->currentMeta = [];
        $this->currentCategories = [];
    }

    public function havePosts(): bool
    {
        return $this->currentIndex < (count($this->contents) - 1);
    }

    public function thePost(): void
    {
        $this->currentIndex++;
        $this->currentContent = $this->contents[$this->currentIndex] ?? null;
        
        if ($this->currentContent) {
            $this->loadMeta();
            $this->loadCategories();
        }
    }

    public function rewindPosts(): void
    {
        $this->currentIndex = -1;
        $this->currentContent = null;
        $this->currentMeta = [];
        $this->currentCategories = [];
    }

    public function getCurrentContent(): ?object
    {
        return $this->currentContent;
    }

    public function getTitle(): ?string
    {
        return $this->currentContent->title ?? null;
    }

    public function getPermalink(): ?string
    {
        if (!$this->currentContent) {
            return null;
        }

        return site_url($this->currentContent->slug);
    }

    public function getExcerpt(int $length = 150): ?string
    {
        if (!$this->currentContent) {
            return null;
        }

        $content = $this->getMeta('content') ?? '';
        
        if (strlen($content) <= $length) {
            return $content;
        }

        return substr(strip_tags($content), 0, $length) . '...';
    }

    public function getMeta(string $key)
    {
        return $this->currentMeta[$key] ?? null;
    }

    public function getRepeater(string $key): ?array
    {
        $value = $this->getMeta($key);
        
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        }
        
        return is_array($value) ? $value : null;
    }

    public function getImage(int $mediaId, string $size = 'full'): ?string
    {
        $media = $this->mediaRepository->findById($mediaId);
        
        if (!$media) {
            return null;
        }

        return base_url($media->filepath);
    }

    public function getGallery(array $mediaIds): array
    {
        $gallery = [];
        
        foreach ($mediaIds as $mediaId) {
            $media = $this->mediaRepository->findById($mediaId);
            if ($media) {
                $gallery[] = [
                    'id' => $media->id,
                    'url' => base_url($media->filepath),
                    'filename' => $media->filename,
                    'mimetype' => $media->mimetype,
                ];
            }
        }
        
        return $gallery;
    }

    public function getCategories(): array
    {
        return $this->currentCategories;
    }

    public function hasCategory(int $categoryId): bool
    {
        foreach ($this->currentCategories as $category) {
            if ($category->id === $categoryId) {
                return true;
            }
        }
        
        return false;
    }

    protected function loadMeta(): void
    {
        if (!$this->currentContent) {
            return;
        }

        $metaItems = $this->contentMetaRepository->getByContentId($this->currentContent->id);
        
        $this->currentMeta = [];
        foreach ($metaItems as $meta) {
            $this->currentMeta[$meta->meta_key] = $meta->meta_value;
        }
    }

    protected function loadCategories(): void
    {
        if (!$this->currentContent) {
            return;
        }

        $categoryRelations = $this->contentCategoryModel
            ->where('content_id', $this->currentContent->id)
            ->findAll();

        $this->currentCategories = [];
        foreach ($categoryRelations as $relation) {
            $category = $this->categoryRepository->findById($relation['category_id']);
            if ($category) {
                $this->currentCategories[] = $category;
            }
        }
    }
}