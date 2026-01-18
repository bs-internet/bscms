<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\ContentRepositoryInterface;
use App\Repositories\Interfaces\ContentTypeRepositoryInterface;
use App\Repositories\Interfaces\ContentTypeFieldRepositoryInterface;
use App\Repositories\Interfaces\ContentMetaRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Validation\ContentValidation;
use App\Enums\FieldType;

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

        $contents = $this->contentRepository->getAll([
            'content_type_id' => $contentTypeId
        ]);

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

        $data = [
            'content_type_id' => $contentTypeId,
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug'),
            'status' => $this->request->getPost('status')
        ];

        $content = $this->contentRepository->create($data);

        if (!$content) {
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
                    $value = $value ? (string)(int)$value : null;
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

        \CodeIgniter\Events\Events::trigger('content_created', $content->id);

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

        $data = [
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug'),
            'status' => $this->request->getPost('status')
        ];

        if (!$this->contentRepository->update($id, $data)) {
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
                    $value = $value ? (string)(int)$value : null;
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

        \CodeIgniter\Events\Events::trigger('content_updated', $id);

        return redirect()->to("/admin/content-type/{$contentTypeId}/contents")->with('success', 'İçerik başarıyla güncellendi');
    }

    public function delete(int $contentTypeId, int $id)
    {
        $content = $this->contentRepository->findById($id);

        if (!$content || $content->content_type_id !== $contentTypeId) {
            return redirect()->back()->with('error', 'İçerik bulunamadı');
        }

        if (!$this->contentRepository->delete($id)) {
            return redirect()->back()->with('error', 'İçerik silinemedi');
        }

        \CodeIgniter\Events\Events::trigger('content_deleted', $id);

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

        $options = array_map(function($content) {
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
}
