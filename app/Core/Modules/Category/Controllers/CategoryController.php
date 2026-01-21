<?php

namespace App\Core\Modules\Category\Controllers;

use App\Core\Shared\Controllers\BaseController;

use App\Core\Modules\Category\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Core\Modules\Content\Repositories\Interfaces\ContentTypeRepositoryInterface;
use App\Core\Modules\Category\Validation\CategoryValidation;

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

        return view('App\Core\Modules\Category\Views/index', [
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

        return view('App\Core\Modules\Category\Views/create', [
            'contentType' => $contentType,
            'categories' => $categories
        ]);
    }

    public function store(int $contentTypeId)
    {
        if (!$this->validate(CategoryValidation::rules(), CategoryValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $data = [
            'content_type_id' => $contentTypeId,
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'parent_id' => $this->request->getPost('parent_id') ?: null,
            'sort_order' => $this->request->getPost('sort_order') ?? 0
        ];

        $category = $this->categoryRepository->create($data);

        if (!$category) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Kategori oluşturulamadı.');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Kayıt başarısız.');
        }

        // ✅ CACHE INVALIDATION
        $this->clearCacheForCreate($category);

        \CodeIgniter\Events\Events::trigger('category_created', $category->id);

        return redirect()->to("/admin/categories/{$contentTypeId}")->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    public function edit(int $contentTypeId, int $id)
    {
        $contentType = $this->contentTypeRepository->findById($contentTypeId);
        $category = $this->categoryRepository->findById($id);

        if (!$contentType || !$category) {
            return redirect()->to("/App\Core\Modules\Category\Views\{$contentTypeId}")->with('error', 'Kategori bulunamadı.');
        }

        $categories = $this->categoryRepository->getByContentTypeId($contentTypeId);

        return view('App\Core\Modules\Category\Views/edit', [
            'contentType' => $contentType,
            'category' => $category,
            'categories' => $categories
        ]);
    }

    public function update(int $contentTypeId, int $id)
    {
        $category = $this->categoryRepository->findById($id);

        if (!$category) {
            return redirect()->to("/App\Core\Modules\Category\Views\{$contentTypeId}")->with('error', 'Kategori bulunamadı.');
        }

        if (!$this->validate(CategoryValidation::rules(), CategoryValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'parent_id' => $this->request->getPost('parent_id') ?: null,
            'sort_order' => $this->request->getPost('sort_order') ?? 0
        ];

        $result = $this->categoryRepository->update($id, $data);

        if (!$result) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Kategori güncellenemedi.');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Güncelleme başarısız.');
        }

        // ✅ CACHE INVALIDATION
        $this->clearCacheForUpdate($id, $category);

        \CodeIgniter\Events\Events::trigger('category_updated', $id);

        return redirect()->to("/admin/categories/{$contentTypeId}")->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function delete(int $contentTypeId, int $id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Perform cleanup directly via Event trigger if needed, or rely on Event listener
        // We added CategoryEvents::cleanupComponentLocations on 'category_deleted'.

        // Get category for cache clearing before deletion
        $category = $this->categoryRepository->findById($id);

        $result = $this->categoryRepository->delete($id);

        if (!$result) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Kategori silinemedi.');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Silme işlemi başarısız.');
        }

        // ✅ CACHE INVALIDATION
        $this->clearCacheForDelete($id, $category);

        \CodeIgniter\Events\Events::trigger('category_deleted', $id);

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
            $db = \Config\Database::connect();
            $db->transStart();

            $count = 0;
            foreach ($selectedIds as $id) {
                if ($this->categoryRepository->delete($id)) {
                    \CodeIgniter\Events\Events::trigger('category_deleted', $id);
                    $count++;
                    $this->clearCaches($contentTypeId, $id);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Toplu silme işlemi başarısız.');
            }

            return redirect()->to("/App\Core\Modules\Category\Views\{$contentTypeId}")->with('success', $count . ' kategori silindi.');
        }

        return redirect()->back()->with('error', 'Geçersiz işlem.');
    }

    protected function clearCacheForCreate($category): void
    {
        cache()->deleteMatching("category_*");
        cache()->deleteMatching("content_list_*");
    }

    protected function clearCacheForUpdate(int $id, $oldCategory): void
    {
        cache()->delete("category_{$id}");
        cache()->deleteMatching("category_*");
        cache()->deleteMatching("content_list_*");
    }

    protected function clearCacheForDelete(int $id, $category): void
    {
        cache()->delete("category_{$id}");
        cache()->deleteMatching("category_*");
        cache()->deleteMatching("content_list_*");
    }
}


