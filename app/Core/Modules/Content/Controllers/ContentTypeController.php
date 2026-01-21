<?php

namespace App\Core\Modules\Content\Controllers;

use App\Core\Shared\Controllers\BaseController;

use App\Core\Modules\Content\Repositories\Interfaces\ContentTypeRepositoryInterface;
use App\Core\Modules\Content\Validation\ContentTypeValidation;

class ContentTypeController extends BaseController
{
    protected ContentTypeRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = service('contentTypeRepository');
    }

    public function index()
    {
        $contentTypes = $this->repository->getAll();

        return view('App\Core\Modules\Content\Views/content_types/index', [
            'contentTypes' => $contentTypes
        ]);
    }

    public function create()
    {
        return view('App\Core\Modules\Content\Views/content_types/create');
    }

    public function store()
    {
        if (!$this->validate(ContentTypeValidation::rules(), ContentTypeValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'has_seo_fields' => $this->request->getPost('has_seo_fields') ? true : false,
            'has_categories' => $this->request->getPost('has_categories') ? true : false,
            'visible' => $this->request->getPost('visible') ? true : false,
        ];

        $contentType = $this->repository->create($data);

        if (!$contentType) {
            return redirect()->back()->with('error', 'İçerik türü oluşturulamadı');
        }

        return redirect()->to('/admin/content-types')->with('success', 'İçerik türü başarıyla oluşturuldu');
    }

    public function edit(int $id)
    {
        $contentType = $this->repository->findById($id);

        if (!$contentType) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('App\Core\Modules\Content\Views/content_types/edit', [
            'contentType' => $contentType
        ]);
    }

    public function update(int $id)
    {
        if (!$this->validate(ContentTypeValidation::rules(true, $id), ContentTypeValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'has_seo_fields' => $this->request->getPost('has_seo_fields') ? true : false,
            'has_categories' => $this->request->getPost('has_categories') ? true : false,
            'visible' => $this->request->getPost('visible') ? true : false,
        ];

        if (!$this->repository->update($id, $data)) {
            return redirect()->back()->with('error', 'İçerik türü güncellenemedi');
        }

        return redirect()->to('/admin/content-types')->with('success', 'İçerik türü başarıyla güncellendi');
    }

    public function delete(int $id)
    {
        if (!$this->repository->delete($id)) {
            return redirect()->back()->with('error', 'İçerik türü silinemedi');
        }

        return redirect()->to('/admin/content-types')->with('success', 'İçerik türü başarıyla silindi');
    }
}


