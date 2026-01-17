<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ContentTypeRepositoryInterface;
use App\Validation\CategoryValidation;

class CategoryController extends BaseController
{
    protected CategoryRepositoryInterface $categoryRepository;
    protected ContentTypeRepositoryInterface $contentTypeRepository;

    public function __construct()
    {
        $this->categoryRepository = service('categoryRepository');
        $this->contentTypeRepository = service('contentTypeRepository');
    }

    public function index(int $contentTypeId)
    {
        $contentType = $this->contentTypeRepository->findById($contentTypeId);

        if (!$contentType) {
            return redirect()->to('/admin/content-types')->with('error', 'İçerik türü bulunamadı.');
        }

        $categories = $this->categoryRepository->getByContentTypeId($contentTypeId);

        return view('admin/categories/index', [
            'contentType' => $contentType,
            'categories' => $categories
        ]);
    }

    public function create(int $contentTypeId)
    {
        $contentType = $this->contentTypeRepository->findById($contentTypeId);

        if (!$contentType) {
            return redirect()->to('/admin/content-types')->with('error', 'İçerik türü bulunamadı.');
        }

        $categories = $this->categoryRepository->getByContentTypeId($contentTypeId);

        return view('admin/categories/create', [
            'contentType' => $contentType,
            'categories' => $categories
        ]);
    }

    public function store(int $contentTypeId)
    {
        if (!$this->validate(CategoryValidation::rules(), CategoryValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'content_type_id' => $contentTypeId,
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'parent_id' => $this->request->getPost('parent_id') ?: null,
            'sort_order' => $this->request->getPost('sort_order') ?? 0
        ];

        $category = $this->categoryRepository->create($data);

        if (!$category) {
            return redirect()->back()->with('error', 'Kategori oluşturulamadı.');
        }

        return redirect()->to("/admin/categories/{$contentTypeId}")->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    public function edit(int $contentTypeId, int $id)
    {
        $contentType = $this->contentTypeRepository->findById($contentTypeId);
        $category = $this->categoryRepository->findById($id);

        if (!$contentType || !$category) {
            return redirect()->to("/admin/categories/{$contentTypeId}")->with('error', 'Kategori bulunamadı.');
        }

        $categories = $this->categoryRepository->getByContentTypeId($contentTypeId);

        return view('admin/categories/edit', [
            'contentType' => $contentType,
            'category' => $category,
            'categories' => $categories
        ]);
    }

    public function update(int $contentTypeId, int $id)
    {
        $category = $this->categoryRepository->findById($id);

        if (!$category) {
            return redirect()->to("/admin/categories/{$contentTypeId}")->with('error', 'Kategori bulunamadı.');
        }

        if (!$this->validate(CategoryValidation::rules(), CategoryValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'parent_id' => $this->request->getPost('parent_id') ?: null,
            'sort_order' => $this->request->getPost('sort_order') ?? 0
        ];

        $result = $this->categoryRepository->update($id, $data);

        if (!$result) {
            return redirect()->back()->with('error', 'Kategori güncellenemedi.');
        }

        return redirect()->to("/admin/categories/{$contentTypeId}")->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function delete(int $contentTypeId, int $id)
    {
        $result = $this->categoryRepository->delete($id);

        if (!$result) {
            return redirect()->back()->with('error', 'Kategori silinemedi.');
        }

        return redirect()->to("/admin/categories/{$contentTypeId}")->with('success', 'Kategori başarıyla silindi.');
    }

    public function bulkAction(int $contentTypeId)
    {
        $action = $this->request->getPost('bulk_action');
        $selectedIds = $this->request->getPost('selected_ids');

        if (!$action || !$selectedIds || !is_array($selectedIds)) {
            return redirect()->back()->with('error', 'Geçersiz işlem.');
        }

        if ($action === 'delete') {
            $count = 0;
            foreach ($selectedIds as $id) {
                if ($this->categoryRepository->delete($id)) {
                    $count++;
                }
            }
            return redirect()->to("/admin/categories/{$contentTypeId}")->with('success', $count . ' kategori silindi.');
        }

        return redirect()->back()->with('error', 'Geçersiz işlem.');
    }
}