<?php

namespace App\Core\Modules\Content\Controllers;

use App\Core\Shared\Controllers\BaseController;

use App\Core\Modules\Content\Repositories\Interfaces\ContentRepositoryInterface;
use App\Core\Modules\Content\Repositories\Interfaces\ContentTypeRepositoryInterface;
use App\Core\Modules\Content\Repositories\Interfaces\ContentTypeFieldRepositoryInterface;
use App\Core\Modules\Content\Repositories\Interfaces\ContentMetaRepositoryInterface;
use App\Core\Modules\Category\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Core\Modules\Content\Validation\ContentValidation;
use App\Core\Modules\Content\Enums\FieldType;
use App\Core\Modules\Content\Enums\ContentStatus;
use CodeIgniter\Events\Events;

class ContentController extends BaseController
{
    protected ContentRepositoryInterface $contentRepository;
    protected ContentTypeRepositoryInterface $contentTypeRepository;
    protected ContentTypeFieldRepositoryInterface $fieldRepository;
    protected ContentMetaRepositoryInterface $metaRepository;
    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct()
    {
        $this->contentRepository = service('contentRepository');
        $this->contentTypeRepository = service('contentTypeRepository');
        $this->fieldRepository = service('contentTypeFieldRepository');
        $this->metaRepository = service('contentMetaRepository');
        $this->categoryRepository = service('categoryRepository');
    }

    public function index(int $contentTypeId)
    {
        $contentType = $this->contentTypeRepository->findById($contentTypeId);

        if (!$contentType) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $cacheKey = "content_list_{$contentTypeId}";
        if (!$contents = cache($cacheKey)) {
            $contents = $this->contentRepository->getAll([
                'content_type_id' => $contentTypeId
            ]);
            cache()->save($cacheKey, $contents, 3600);
        }

        return view('App\Core\Modules\Content\Views/contents/index', [
            'contentType' => $contentType,
            'contents' => $contents
        ]);
    }

    public function create(int $contentTypeId)
    {
        $contentType = $this->contentTypeRepository->findById($contentTypeId);

        if (!$contentType) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $fields = $this->fieldRepository->getByContentType($contentTypeId);
        $categories = [];

        if ($contentType->has_categories) {
            $categories = $this->categoryRepository->getAll([
                'content_type_id' => $contentTypeId
            ]);
        }

        return view('App\Core\Modules\Content\Views/contents/create', [
            'contentType' => $contentType,
            'fields' => $fields,
            'categories' => $categories
        ]);
    }

    public function store(int $contentTypeId)
    {
        $contentType = $this->contentTypeRepository->findById($contentTypeId);

        if (!$contentType) {
            return redirect()->back()->with('error', 'İçerik türü bulunamadı');
        }

        if (!$this->validate(ContentValidation::rules(), ContentValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $data = [
            'content_type_id' => $contentTypeId,
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug'),
            'status' => ContentStatus::tryFrom($this->request->getPost('status'))?->value ?? ContentStatus::DRAFT->value
        ];

        $content = $this->contentRepository->create($data);

        if (!$content) {
            // Transaction will be handled by transComplete status check if we return/exit, 
            // but strict mode might need explicit failure. 
            // Since we are inside transStart, let's behave safely.
            // But we can't rollback easily inside 'transStart' usage pattern without manual control?
            // Actually CI4 docs say: "If you want to manually manage the transaction... use transBegin".
            // With transStart, we rely on automatic rollback if an exception is thrown or if the logic flow indicates error?
            // "If strict mode is enabled (default), if any of the groups of commands fails, the entire transaction will be rolled back."
            // But repository->create() likely just returns false, not a "command failure" in the DB sense unless it throws.
            // So we should manually rollback if logic fails.
            // But wait, user standard said: NO TRY CATCH.
            // User example:
            // $db->transStart();
            // $content = repo->create();
            // if (!$content) { $db->transRollback(); return error; }
            // So I WILL use that pattern.
            $db->transRollback();
            return redirect()->back()->with('error', 'İçerik oluşturulamadı');
        }

        $fields = $this->fieldRepository->getByContentType($contentTypeId);
        $properties = []; // Collect meta for JSON storage

        foreach ($fields as $field) {
            $fieldKey = $field->field_key;
            $value = $this->request->getPost($fieldKey);

            if ($field->field_type === FieldType::RELATION) {
                $fieldOptions = $field->getFieldOptions();

                if ($fieldOptions['multiple'] ?? false) {
                    $value = is_array($value) ? json_encode(array_map('intval', $value)) : null;
                } else {
                    $value = $value ? (string) (int) $value : null;
                }
            } elseif ($field->field_type === FieldType::REPEATER) {
                // XSS Prevention: Sanitize before encoding
                $value = is_array($value) ? json_encode($this->sanitizeArray($value)) : null;
            } elseif ($field->field_type === FieldType::GALLERY) {
                $value = is_array($value) ? json_encode(array_map('intval', $value)) : null;
            }

            if ($value !== null) {
                $this->metaRepository->upsert($content->id, $fieldKey, $value);
                $properties[$fieldKey] = $value; // Add to properties
            }
        }

        // Dual-Write: Save collected meta as JSON properties
        if (!empty($properties)) {
            $this->contentRepository->update($content->id, ['properties' => $properties]);
        }

        if ($contentType->has_categories) {
            $categoryIds = $this->request->getPost('categories') ?? [];
            $this->saveCategoryRelations($content->id, $categoryIds);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Kayıt başarısız (İşlem tamamlanamadı)');
        }

        // ✅ CACHE INVALIDATION
        $this->clearCacheForCreate($content);

        Events::trigger('content_created', $content->id, $contentTypeId);

        return redirect()->to("/admin/content-type/{$contentTypeId}/contents")->with('success', 'İçerik başarıyla oluşturuldu');
    }

    public function edit(int $contentTypeId, int $id)
    {
        $contentType = $this->contentTypeRepository->findById($contentTypeId);

        if (!$contentType) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $content = $this->contentRepository->findById($id);

        if (!$content || $content->content_type_id !== $contentTypeId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $fields = $this->fieldRepository->getByContentType($contentTypeId);
        $metas = $this->metaRepository->getAllByContentId($id);

        $contentMeta = [];
        foreach ($metas as $meta) {
            $contentMeta[$meta->meta_key] = $meta->meta_value;
        }

        $categories = [];
        $selectedCategories = [];

        if ($contentType->has_categories) {
            $categories = $this->categoryRepository->getAll([
                'content_type_id' => $contentTypeId
            ]);
            $selectedCategories = $this->getContentCategories($id);
        }

        return view('App\Core\Modules\Content\Views/contents/edit', [
            'contentType' => $contentType,
            'content' => $content,
            'fields' => $fields,
            'contentMeta' => $contentMeta,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories
        ]);
    }

    public function update(int $contentTypeId, int $id)
    {
        $contentType = $this->contentTypeRepository->findById($contentTypeId);

        if (!$contentType) {
            return redirect()->back()->with('error', 'İçerik türü bulunamadı');
        }

        $content = $this->contentRepository->findById($id);

        if (!$content || $content->content_type_id !== $contentTypeId) {
            return redirect()->back()->with('error', 'İçerik bulunamadı');
        }

        if (!$this->validate(ContentValidation::rules(), ContentValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $data = [
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug'),
            'status' => ContentStatus::tryFrom($this->request->getPost('status'))?->value ?? ContentStatus::DRAFT->value
        ];

        if (!$this->contentRepository->update($id, $data)) {
            $db->transRollback();
            return redirect()->back()->with('error', 'İçerik güncellenemedi');
        }

        $fields = $this->fieldRepository->getByContentType($contentTypeId);
        $properties = []; // Collect meta for JSON storage

        foreach ($fields as $field) {
            $fieldKey = $field->field_key;
            $value = $this->request->getPost($fieldKey);

            if ($field->field_type === FieldType::RELATION) {
                $fieldOptions = $field->getFieldOptions();

                if ($fieldOptions['multiple'] ?? false) {
                    $value = is_array($value) ? json_encode(array_map('intval', $value)) : null;
                } else {
                    $value = $value ? (string) (int) $value : null;
                }
            } elseif ($field->field_type === FieldType::REPEATER) {
                // XSS Prevention: Sanitize before encoding
                $value = is_array($value) ? json_encode($this->sanitizeArray($value)) : null;
            } elseif ($field->field_type === FieldType::GALLERY) {
                $value = is_array($value) ? json_encode(array_map('intval', $value)) : null;
            }

            if ($value !== null) {
                $this->metaRepository->upsert($id, $fieldKey, $value);
                $properties[$fieldKey] = $value; // Add to properties
            }
        }

        // Dual-Write: Update properties JSON (Merging with existing is safer? No, form submission is usually full update for standard fields)
        // But to be safe and efficient, we just overwrite properties with what was submitted.
        // If we want partial updates, we should read existing first.
        // Assuming Edit Form submits ALL fields.
        if (!empty($properties)) {
            $this->contentRepository->update($id, ['properties' => $properties]);
        }

        if ($contentType->has_categories) {
            $categoryIds = $this->request->getPost('categories') ?? [];
            $this->saveCategoryRelations($id, $categoryIds);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Güncelleme başarısız');
        }

        // ✅ CACHE INVALIDATION
        $this->clearCacheForUpdate($id, $content);

        Events::trigger('content_updated', $id, $contentTypeId);

        return redirect()->to("/admin/content-type/{$contentTypeId}/contents")->with('success', 'İçerik başarıyla güncellendi');
    }

    public function delete(int $contentTypeId, int $id)
    {
        $content = $this->contentRepository->findById($id);

        if (!$content || $content->content_type_id !== $contentTypeId) {
            return redirect()->back()->with('error', 'İçerik bulunamadı');
        }

        $db = \Config\Database::connect();
        // Get content for cache clearing before deletion
        $content = $this->contentRepository->findById($id);

        if (!$this->contentRepository->delete($id)) {
            $db->transRollback();
            return redirect()->back()->with('error', 'İçerik silinemedi');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Silme işlemi başarısız');
        }

        // ✅ CACHE INVALIDATION
        $this->clearCacheForDelete($id, $content);

        Events::trigger('content_deleted', $id);

        return redirect()->to("/admin/content-type/{$contentTypeId}/contents")->with('success', 'İçerik başarıyla silindi');
    }

    public function getRelationOptions(int $contentTypeId)
    {
        $contentType = $this->contentTypeRepository->findById($contentTypeId);

        if (!$contentType) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'İçerik türü bulunamadı'
            ])->setStatusCode(404);
        }

        $visibleOnly = $this->request->getGet('visible_only') === '1';

        if ($visibleOnly && $contentType->visible) {
            $contents = $this->contentRepository->getAll([
                'content_type_id' => $contentTypeId,
                'status' => 'published'
            ]);
        } else {
            $contents = $this->contentRepository->getAll([
                'content_type_id' => $contentTypeId
            ]);
        }

        $options = array_map(function ($content) {
            return [
                'id' => $content->id,
                'title' => $content->title
            ];
        }, $contents);

        return $this->response->setJSON([
            'success' => true,
            'options' => $options
        ]);
    }

    protected function saveCategoryRelations(int $contentId, array $categoryIds): void
    {
        $db = \Config\Database::connect();
        $builder = $db->table('content_categories');

        // Get content definition to find content type id
        // Note: contentId is already created so we can query it, or pass contentTypeId as argument
        // But the original code passed contentId. We need to fetch it to be safe 
        // or rely on caller having correct logic. Let's fetch it for strictness.
        $content = $this->contentRepository->findById($contentId);
        if (!$content)
            return; // Should not happen in this flow

        // Get valid categories for this content type
        $validCategories = $this->categoryRepository->getAll([
            'content_type_id' => $content->content_type_id
        ]);
        $validCategoryIds = array_column($validCategories, 'id');

        // We are already in a transaction from the caller
        $builder->where('content_id', $contentId)->delete();

        foreach ($categoryIds as $categoryId) {
            // Validate category belongs to content type
            if (!in_array((int) $categoryId, $validCategoryIds)) {
                log_message('warning', "Invalid category {$categoryId} assignment attempted for content {$contentId}");
                continue;
            }

            $builder->insert([
                'content_id' => $contentId,
                'category_id' => $categoryId
            ]);
        }
    }

    protected function getContentCategories(int $contentId): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('content_categories');

        $results = $builder->select('category_id')
            ->where('content_id', $contentId)
            ->get()
            ->getResultArray();

        return array_column($results, 'category_id');
    }

    protected function clearCacheForCreate($content): void
    {
        $this->deleteCacheMatching("content_list_*");
        cache()->delete("content_list_{$content->content_type_id}");
    }

    protected function clearCacheForUpdate(int $id, $oldContent): void
    {
        cache()->delete("content_{$id}");
        cache()->delete("content_list_{$oldContent->content_type_id}");
        $this->deleteCacheMatching("content_list_*");
    }

    protected function clearCacheForDelete(int $id, $content): void
    {
        cache()->delete("content_{$id}");
        if ($content) {
            cache()->delete("content_list_{$content->content_type_id}");
        }
        $this->deleteCacheMatching("content_list_*");
    }

    protected function deleteCacheMatching(string $pattern): void
    {
        $cache = cache();

        if (method_exists($cache, 'deleteMatching')) {
            $cache->deleteMatching($pattern);
        }
    }

    protected function sanitizeValue($value, $fieldType)
    {
        if ($fieldType === FieldType::REPEATER) {
            return is_array($value) ? json_encode($this->sanitizeArray($value)) : null;
        }

        return $value;
    }

    protected function sanitizeArray(array $data): array
    {
        return array_map(function ($item) {
            if (is_array($item)) {
                return $this->sanitizeArray($item);
            }
            // HTML encode to prevent XSS in JSON fields
            return htmlspecialchars((string) $item, ENT_QUOTES, 'UTF-8');
        }, $data);
    }
}



