<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\ContentRepositoryInterface;
use App\Repositories\Interfaces\ContentTypeRepositoryInterface;
use App\Repositories\Interfaces\ContentMetaRepositoryInterface;
use App\Repositories\Interfaces\ContentTypeFieldRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Models\ContentCategoryModel;
use App\Events\ContentEvents;
use App\Validation\ContentValidation;
use App\Enums\ContentStatus;

class ContentController extends BaseController
{
    protected ContentRepositoryInterface $contentRepository;
    protected ContentTypeRepositoryInterface $contentTypeRepository;
    protected ContentMetaRepositoryInterface $contentMetaRepository;
    protected ContentTypeFieldRepositoryInterface $contentTypeFieldRepository;
    protected CategoryRepositoryInterface $categoryRepository;
    protected ContentCategoryModel $contentCategoryModel;

    public function __construct()
    {
        $this->contentRepository = service('contentRepository');
        $this->contentTypeRepository = service('contentTypeRepository');
        $this->contentMetaRepository = service('contentMetaRepository');
        $this->contentTypeFieldRepository = service('contentTypeFieldRepository');
        $this->categoryRepository = service('categoryRepository');
        $this->contentCategoryModel = service('contentCategoryModel');
    }

    public function index(int $contentTypeId)
    {
        $this->requireAuth();

        $contentType = $this->contentTypeRepository->findById($contentTypeId);

        if (!$contentType) {
            return redirect()->to('/admin/content-types')->with('error', 'İçerik türü bulunamadı.');
        }

        $contents = $this->contentRepository->getByContentType($contentTypeId);

        return view('admin/contents/index', [
            'contentType' => $contentType,
            'contents' => $contents
        ]);
    }

    public function create(int $contentTypeId)
    {
        $this->requireAuth();

        $contentType = $this->contentTypeRepository->findById($contentTypeId);

        if (!$contentType) {
            return redirect()->to('/admin/content-types')->with('error', 'İçerik türü bulunamadı.');
        }

        $fields = $this->contentTypeFieldRepository->getByContentTypeId($contentTypeId);
        $categories = [];

        if ($contentType->has_categories) {
            $categories = $this->categoryRepository->getByContentTypeId($contentTypeId);
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
            return redirect()->to('/admin/content-types')->with('error', 'İçerik türü bulunamadı.');
        }

        if (!$this->validate(ContentValidation::rules(), ContentValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $contentData = [
            'content_type_id' => $contentTypeId,
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug'),
            'status' => ContentStatus::from($this->request->getPost('status'))
        ];

        $content = $this->contentRepository->create($contentData);

        if (!$content) {
            return redirect()->back()->with('error', 'İçerik oluşturulamadı.');
        }

        $this->saveMeta($content->id, $contentTypeId);

        if ($contentType->has_categories) {
            $this->saveCategories($content->id);
        }

        \CodeIgniter\Events\Events::trigger('content_created', $content->id);

        return redirect()->to("/admin/contents/{$contentTypeId}")->with('success', 'İçerik başarıyla oluşturuldu.');
    }

    public function edit(int $contentTypeId, int $id)
    {
        $this->requireAuth();

        $contentType = $this->contentTypeRepository->findById($contentTypeId);
        $content = $this->contentRepository->findById($id);

        if (!$contentType || !$content) {
            return redirect()->to('/admin/content-types')->with('error', 'İçerik bulunamadı.');
        }

        $fields = $this->contentTypeFieldRepository->getByContentTypeId($contentTypeId);
        $meta = $this->contentMetaRepository->getByContentId($id);
        $categories = [];
        $selectedCategories = [];

        if ($contentType->has_categories) {
            $categories = $this->categoryRepository->getByContentTypeId($contentTypeId);
            $relations = $this->contentCategoryModel->where('content_id', $id)->findAll();
            $selectedCategories = array_column($relations, 'category_id');
        }

        return view('admin/contents/edit', [
            'contentType' => $contentType,
            'content' => $content,
            'fields' => $fields,
            'meta' => $meta,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories
        ]);
    }

    public function update(int $contentTypeId, int $id)
    {
        $content = $this->contentRepository->findById($id);

        if (!$content) {
            return redirect()->to("/admin/contents/{$contentTypeId}")->with('error', 'İçerik bulunamadı.');
        }

        $contentType = $this->contentTypeRepository->findById($contentTypeId);

        if (!$this->validate(ContentValidation::rules(), ContentValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $contentData = [
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug'),
            'status' => ContentStatus::from($this->request->getPost('status'))
        ];

        $result = $this->contentRepository->update($id, $contentData);

        if (!$result) {
            return redirect()->back()->with('error', 'İçerik güncellenemedi.');
        }

        $this->saveMeta($id, $contentTypeId);

        if ($contentType->has_categories) {
            $this->saveCategories($id);
        }

        \CodeIgniter\Events\Events::trigger('content_updated', $id);

        return redirect()->to("/admin/contents/{$contentTypeId}")->with('success', 'İçerik başarıyla güncellendi.');
    }

    public function delete(int $contentTypeId, int $id)
    {
        $this->requireAuth();

        $result = $this->contentRepository->delete($id);

        if (!$result) {
            return redirect()->back()->with('error', 'İçerik silinemedi.');
        }

        \CodeIgniter\Events\Events::trigger('content_deleted', $id);        

        return redirect()->to("/admin/contents/{$contentTypeId}")->with('success', 'İçerik başarıyla silindi.');
    }

    protected function saveMeta(int $contentId, int $contentTypeId): void
    {
        $fields = $this->contentTypeFieldRepository->getByContentTypeId($contentTypeId);

        foreach ($fields as $field) {
            $value = $this->request->getPost($field->field_key);

            if ($value !== null) {
                $this->contentMetaRepository->upsert($contentId, $field->field_key, $value);
            }
        }
    }

    protected function saveCategories(int $contentId): void
    {
        $this->contentCategoryModel->where('content_id', $contentId)->delete();

        $categories = $this->request->getPost('categories');

        if (is_array($categories)) {
            foreach ($categories as $categoryId) {
                $this->contentCategoryModel->insert([
                    'content_id' => $contentId,
                    'category_id' => $categoryId
                ]);
            }
        }
    }

    public function bulkAction(int $contentTypeId)
    {
        $action = $this->request->getPost('bulk_action');
        $selectedIds = $this->request->getPost('selected_ids');

        if (!$action || !$selectedIds || !is_array($selectedIds)) {
            return redirect()->back()->with('error', 'Geçersiz işlem.');
        }

        $count = 0;

        switch ($action) {
            case 'publish':
                foreach ($selectedIds as $id) {
                    if ($this->contentRepository->update($id, ['status' => ContentStatus::PUBLISHED])) {
                        $count++;
                    }
                }
                $message = $count . ' içerik yayınlandı.';
                break;

            case 'draft':
                foreach ($selectedIds as $id) {
                    if ($this->contentRepository->update($id, ['status' => ContentStatus::DRAFT])) {
                        $count++;
                    }
                }
                $message = $count . ' içerik taslağa alındı.';
                break;

            case 'archive':
                foreach ($selectedIds as $id) {
                    if ($this->contentRepository->update($id, ['status' => ContentStatus::ARCHIVED])) {
                        $count++;
                    }
                }
                $message = $count . ' içerik arşivlendi.';
                break;

            case 'delete':
                foreach ($selectedIds as $id) {
                    if ($this->contentRepository->delete($id)) {
                        $count++;
                    }
                }
                $message = $count . ' içerik silindi.';
                break;

            default:
                return redirect()->back()->with('error', 'Geçersiz işlem.');
        }

        return redirect()->to("/admin/contents/{$contentTypeId}")->with('success', $message);
    }
    
    public function duplicate(int $contentTypeId, int $id)
    {
        $originalContent = $this->contentRepository->findById($id);

        if (!$originalContent) {
            return redirect()->back()->with('error', 'İçerik bulunamadı.');
        }

        $newTitle = $originalContent->title . ' (Kopya)';
        $newSlug = generate_unique_slug($newTitle, 'contents');

        $newContent = $this->contentRepository->create([
            'content_type_id' => $originalContent->content_type_id,
            'title' => $newTitle,
            'slug' => $newSlug,
            'status' => ContentStatus::DRAFT
        ]);

        if (!$newContent) {
            return redirect()->back()->with('error', 'İçerik kopyalanamadı.');
        }

        $originalMeta = $this->contentMetaRepository->getByContentId($id);
        foreach ($originalMeta as $meta) {
            $this->contentMetaRepository->create([
                'content_id' => $newContent->id,
                'meta_key' => $meta->meta_key,
                'meta_value' => $meta->meta_value
            ]);
        }

        $contentType = $this->contentTypeRepository->findById($contentTypeId);
        if ($contentType->has_categories) {
            $relations = $this->contentCategoryModel->where('content_id', $id)->findAll();
            foreach ($relations as $relation) {
                $this->contentCategoryModel->insert([
                    'content_id' => $newContent->id,
                    'category_id' => $relation['category_id']
                ]);
            }
        }

        return redirect()->to("/admin/contents/{$contentTypeId}/edit/{$newContent->id}")->with('success', 'İçerik başarıyla kopyalandı.');
    }    
}