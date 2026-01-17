<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\ContentTypeRepositoryInterface;
use App\Repositories\Interfaces\ContentTypeFieldRepositoryInterface;
use App\Validation\ContentTypeValidation;

class ContentTypeController extends BaseController
{
    protected ContentTypeRepositoryInterface $contentTypeRepository;
    protected ContentTypeFieldRepositoryInterface $contentTypeFieldRepository;

    public function __construct()
    {
        $this->contentTypeRepository = service('contentTypeRepository');
        $this->contentTypeFieldRepository = service('contentTypeFieldRepository');
    }

    public function index()
    {
        $this->requireAuth();

        $contentTypes = $this->contentTypeRepository->getAll();

        return view('admin/content-types/index', ['contentTypes' => $contentTypes]);
    }

    public function create()
    {
        $this->requireAuth();

        return view('admin/content-types/create');
    }

    public function store()
    {
        if (!$this->validate(ContentTypeValidation::rules(), ContentTypeValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'has_seo_fields' => $this->request->getPost('has_seo_fields') ? 1 : 0,
            'has_categories' => $this->request->getPost('has_categories') ? 1 : 0
        ];

        $contentType = $this->contentTypeRepository->create($data);

        if (!$contentType) {
            return redirect()->back()->with('error', 'İçerik türü oluşturulamadı.');
        }

        if ($data['has_seo_fields']) {
            $this->createSeoFields($contentType->id);
        }

        return redirect()->to('/admin/content-types')->with('success', 'İçerik türü başarıyla oluşturuldu.');
    }

    public function edit(int $id)
    {
        $this->requireAuth();

        $contentType = $this->contentTypeRepository->findById($id);

        if (!$contentType) {
            return redirect()->to('/admin/content-types')->with('error', 'İçerik türü bulunamadı.');
        }

        $fields = $this->contentTypeFieldRepository->getByContentTypeId($id);

        return view('admin/content-types/edit', [
            'contentType' => $contentType,
            'fields' => $fields
        ]);
    }

    public function update(int $id)
    {
        $contentType = $this->contentTypeRepository->findById($id);

        if (!$contentType) {
            return redirect()->to('/admin/content-types')->with('error', 'İçerik türü bulunamadı.');
        }

        if (!$this->validate(ContentTypeValidation::rules(true, $id), ContentTypeValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'has_seo_fields' => $this->request->getPost('has_seo_fields') ? 1 : 0,
            'has_categories' => $this->request->getPost('has_categories') ? 1 : 0
        ];

        $result = $this->contentTypeRepository->update($id, $data);

        if (!$result) {
            return redirect()->back()->with('error', 'İçerik türü güncellenemedi.');
        }

        return redirect()->to('/admin/content-types')->with('success', 'İçerik türü başarıyla güncellendi.');
    }

    public function delete(int $id)
    {
        $this->requireAuth();

        $result = $this->contentTypeRepository->delete($id);

        if (!$result) {
            return redirect()->back()->with('error', 'İçerik türü silinemedi.');
        }

        return redirect()->to('/admin/content-types')->with('success', 'İçerik türü başarıyla silindi.');
    }

    protected function createSeoFields(int $contentTypeId): void
    {
        $seoFields = [
            [
                'content_type_id' => $contentTypeId,
                'field_key' => 'seo_title',
                'field_type' => 'text',
                'field_label' => 'SEO Başlık',
                'is_required' => false,
                'field_options' => null,
                'sort_order' => 9998
            ],
            [
                'content_type_id' => $contentTypeId,
                'field_key' => 'seo_description',
                'field_type' => 'textarea',
                'field_label' => 'SEO Açıklama',
                'is_required' => false,
                'field_options' => null,
                'sort_order' => 9999
            ],
            [
                'content_type_id' => $contentTypeId,
                'field_key' => 'seo_image',
                'field_type' => 'image',
                'field_label' => 'SEO Görsel',
                'is_required' => false,
                'field_options' => null,
                'sort_order' => 10000
            ]
        ];

        foreach ($seoFields as $field) {
            $this->contentTypeFieldRepository->create($field);
        }
    }
}