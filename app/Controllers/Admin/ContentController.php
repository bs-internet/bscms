<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\ContentRepositoryInterface;
use App\Repositories\Interfaces\ContentTypeRepositoryInterface;
use App\Repositories\Interfaces\ContentTypeFieldRepositoryInterface;
use App\Repositories\Interfaces\ContentMetaRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Validation\ContentValidation;
use App\Enums\FieldType;
use App\Enums\ContentStatus;
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

        return view('admin/contents/index', [
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

        return view('admin/contents/create', [
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
                $value = is_array($value) ? json_encode($value) : null;
            } elseif ($field->field_type === FieldType::GALLERY) {
                $value = is_array($value) ? json_encode(array_map('intval', $value)) : null;
            }

            if ($value !== null) {
                $this->metaRepository->upsert($content->id, $fieldKey, $value);
            }
        }

        if ($contentType->has_categories) {
            $categoryIds = $this->request->getPost('categories') ?? [];
            $this->saveCategoryRelations($content->id, $categoryIds);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Kayıt başarısız (İşlem tamamlanamadı)');
        }

        $this->clearCaches($content->id, $contentTypeId);
        Events::trigger('content_created', $content->id);

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

        return view('admin/contents/edit', [
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
                $value = is_array($value) ? json_encode($value) : null;
            } elseif ($field->field_type === FieldType::GALLERY) {
                $value = is_array($value) ? json_encode(array_map('intval', $value)) : null;
            }

            if ($value !== null) {
                $this->metaRepository->upsert($id, $fieldKey, $value);
            }
        }

        if ($contentType->has_categories) {
            $categoryIds = $this->request->getPost('categories') ?? [];
            $this->saveCategoryRelations($id, $categoryIds);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Güncelleme başarısız');
        }

        $this->clearCaches($id, $contentTypeId);
        Events::trigger('content_updated', $id);

        return redirect()->to("/admin/content-type/{$contentTypeId}/contents")->with('success', 'İçerik başarıyla güncellendi');
    }

    public function delete(int $contentTypeId, int $id)
    {
        $content = $this->contentRepository->findById($id);

        if (!$content || $content->content_type_id !== $contentTypeId) {
            return redirect()->back()->with('error', 'İçerik bulunamadı');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Trigger 'deleting' event to handle cleanup of dependent resources (like component instances)
        // before the cascade delete removes the links.
        Events::trigger('content_deleting', $id);

        if (!$this->contentRepository->delete($id)) {
            $db->transRollback();
            return redirect()->back()->with('error', 'İçerik silinemedi');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Silme işlemi başarısız');
        }

        $this->clearCaches($id, $contentTypeId);
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

        // We are already in a transaction from the caller, so these will be part of it.
        $builder->where('content_id', $contentId)->delete();

        foreach ($categoryIds as $categoryId) {
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

    protected function clearCaches(int $contentId, int $contentTypeId): void
    {
        // Clear specific content cache
        cache()->delete("content_{$contentId}");

        // Clear content list cache
        cache()->delete("content_list_{$contentTypeId}");

        // Note: Category caches clearing would ideally be here or in CategoryRepository/Events,
        // but for now we follow the roadmap which prioritized content caches.
        // If we want to be thorough:
        // $categories = $this->getContentCategories($contentId);
        // foreach ($categories as $catId) { cache()->delete("category_contents_{$catId}"); }
    }
}
