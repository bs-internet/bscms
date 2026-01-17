<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\ContentTypeFieldRepositoryInterface;
use App\Validation\ContentTypeFieldValidation;
use App\Enums\FieldType;

class ContentTypeFieldController extends BaseController
{
    protected ContentTypeFieldRepositoryInterface $contentTypeFieldRepository;

    public function __construct()
    {
        $this->contentTypeFieldRepository = service('contentTypeFieldRepository');
    }

    public function store(int $contentTypeId)
    {
        if (!$this->validate(ContentTypeFieldValidation::rules(), ContentTypeFieldValidation::messages())) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        $data = [
            'content_type_id' => $contentTypeId,
            'field_key' => $this->request->getPost('field_key'),
            'field_type' => FieldType::from($this->request->getPost('field_type')),
            'field_label' => $this->request->getPost('field_label'),
            'field_options' => $this->request->getPost('field_options'),
            'is_required' => $this->request->getPost('is_required') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?? 0
        ];

        $field = $this->contentTypeFieldRepository->create($data);

        if (!$field) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Alan oluşturulamadı.'
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Alan başarıyla oluşturuldu.',
            'data' => $field
        ]);
    }

    public function update(int $contentTypeId, int $id)
    {
        if (!$this->validate(ContentTypeFieldValidation::rules(), ContentTypeFieldValidation::messages())) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        $data = [
            'field_key' => $this->request->getPost('field_key'),
            'field_type' => FieldType::from($this->request->getPost('field_type')),
            'field_label' => $this->request->getPost('field_label'),
            'field_options' => $this->request->getPost('field_options'),
            'is_required' => $this->request->getPost('is_required') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?? 0
        ];

        $result = $this->contentTypeFieldRepository->update($id, $data);

        if (!$result) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Alan güncellenemedi.'
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Alan başarıyla güncellendi.'
        ]);
    }

    public function delete(int $contentTypeId, int $id)
    {
        $result = $this->contentTypeFieldRepository->delete($id);

        if (!$result) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Alan silinemedi.'
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Alan başarıyla silindi.'
        ]);
    }
}