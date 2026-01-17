<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\FormFieldRepositoryInterface;
use App\Validation\FormFieldValidation;
use App\Enums\FieldType;

class FormFieldController extends BaseController
{
    protected FormFieldRepositoryInterface $formFieldRepository;

    public function __construct()
    {
        $this->formFieldRepository = service('formFieldRepository');
    }

    public function store(int $formId)
    {
        if (!$this->validate(FormFieldValidation::rules(), FormFieldValidation::messages())) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        $data = [
            'form_id' => $formId,
            'field_key' => $this->request->getPost('field_key'),
            'field_type' => FieldType::from($this->request->getPost('field_type')),
            'field_label' => $this->request->getPost('field_label'),
            'field_options' => $this->request->getPost('field_options'),
            'is_required' => $this->request->getPost('is_required') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?? 0
        ];

        $field = $this->formFieldRepository->create($data);

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

    public function update(int $formId, int $id)
    {
        if (!$this->validate(FormFieldValidation::rules(), FormFieldValidation::messages())) {
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

        $result = $this->formFieldRepository->update($id, $data);

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

    public function delete(int $formId, int $id)
    {
        $result = $this->formFieldRepository->delete($id);

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