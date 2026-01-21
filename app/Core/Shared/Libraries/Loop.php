<?php

namespace App\Core\Shared\Libraries;

class Loop
{
    protected array $contents = [];
    protected int $currentIndex = -1;
    protected ?object $currentContent = null;
    protected array $currentMeta = [];
    protected array $currentCategories = [];

    public function __construct(
        protected $contentRepository = null,
        protected $contentMetaRepository = null,
        protected $categoryRepository = null,
        protected $mediaRepository = null,
        protected $contentCategoryModel = null
    ) {
        $this->contentRepository = $contentRepository ?? service('contentRepository');
        $this->contentMetaRepository = $contentMetaRepository ?? service('contentMetaRepository');
        $this->categoryRepository = $categoryRepository ?? service('categoryRepository');
        $this->mediaRepository = $mediaRepository ?? service('mediaRepository');
        $this->contentCategoryModel = $contentCategoryModel ?? service('contentCategoryModel');
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
        return ($this->currentIndex + 1) < count($this->contents);
    }

    public function thePost(): void
    {
        if (!$this->havePosts()) {
            return;
        }

        $this->currentIndex++;
        $this->currentContent = $this->contents[$this->currentIndex];

        $metas = $this->contentMetaRepository->getAllByContentId($this->currentContent->id);

        $this->currentMeta = [];
        foreach ($metas as $meta) {
            $this->currentMeta[$meta->meta_key] = $meta->meta_value;
        }

        $this->currentCategories = $this->contentCategoryModel
            ->select('categories.*')
            ->join('categories', 'categories.id = content_categories.category_id')
            ->where('content_categories.content_id', $this->currentContent->id)
            ->findAll();
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

        $contentTypeRepo = service('contentTypeRepository');
        $contentType = $contentTypeRepo->findById($this->currentContent->content_type_id);

        if (!$contentType) {
            return null;
        }

        return site_url($contentType->slug . '/' . $this->currentContent->slug);
    }

    public function getExcerpt(int $length = 150): ?string
    {
        $content = $this->currentMeta['content'] ?? '';

        if (empty($content)) {
            return null;
        }

        $content = strip_tags($content);

        if (mb_strlen($content) <= $length) {
            return $content;
        }

        return mb_substr($content, 0, $length) . '...';
    }

    public function getMeta(string $key)
    {
        $value = $this->currentMeta[$key] ?? null;

        if (is_null($value)) {
            return null;
        }

        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return $value;
    }

    public function getRepeater(string $key): ?array
    {
        $value = $this->getMeta($key);

        if (!is_array($value)) {
            return null;
        }

        return $value;
    }

    public function getImage(int $mediaId, string $size = 'full'): ?string
    {
        $mediaRepo = service('mediaRepository');
        $media = $mediaRepo->findById($mediaId);

        if (!$media) {
            return null;
        }

        $imageProcessor = service('imageProcessor');
        return $imageProcessor->getImageUrl($media->filepath, $size);
    }

    public function getGallery(array $mediaIds): array
    {
        $images = [];

        foreach ($mediaIds as $mediaId) {
            $url = $this->getImage($mediaId);
            if ($url) {
                $images[] = $url;
            }
        }

        return $images;
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

    public function getRelation(string $key)
    {
        if (!$this->currentContent) {
            return null;
        }

        $metaValue = $this->currentMeta[$key] ?? null;

        if (!$metaValue) {
            return null;
        }

        $decoded = json_decode($metaValue, true);

        if (is_array($decoded)) {
            return !empty($decoded) ? $this->contentRepository->findById($decoded[0]) : null;
        }

        return $this->contentRepository->findById((int) $metaValue);
    }

    public function getRelations(string $key): array
    {
        if (!$this->currentContent) {
            return [];
        }

        $metaValue = $this->currentMeta[$key] ?? null;

        if (!$metaValue) {
            return [];
        }

        $decoded = json_decode($metaValue, true);

        if (!is_array($decoded)) {
            $decoded = [(int) $metaValue];
        }

        $results = [];

        foreach ($decoded as $relatedId) {
            $content = $this->contentRepository->findById($relatedId);
            if ($content) {
                $results[] = $content;
            }
        }

        return $results;
    }
}
