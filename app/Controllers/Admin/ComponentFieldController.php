<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\ComponentFieldRepositoryInterface;
use App\Validation\ComponentFieldValidation;
use App\Enums\FieldType;

class ComponentFieldController extends BaseController
{
    protected ComponentFieldRepositoryInterface $componentFieldRepository;

    public function __construct()
    {
        $this->componentFieldRepository = service('componentFieldRepository');
    }

    public function store(int $componentId)
    {
        if (!$this->validate(ComponentFieldValidation::rules(), ComponentFieldValidation::messages())) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        $fieldOptions = $this->request->getPost('field_options');

        $data = [
            'component_id' => $componentId,
            'field_key' => $this->request->getPost('field_key'),
            'field_type' => FieldType::from($this->request->getPost('field_type')),
            'field_label' => $this->request->getPost('field_label'),
            'field_options' => $fieldOptions ? json_encode($fieldOptions) : null,
            'is_required' => $this->request->getPost('is_required') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?? 0
        ];

        $field = $this->componentFieldRepository->create($data);

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

    public function update(int $componentId, int $id)
    {
        if (!$this->validate(ComponentFieldValidation::rules(), ComponentFieldValidation::messages())) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        $fieldOptions = $this->request->getPost('field_options');

        $data = [
            'field_key' => $this->request->getPost('field_key'),
            'field_type' => FieldType::from($this->request->getPost('field_type')),
            'field_label' => $this->request->getPost('field_label'),
            'field_options' => $fieldOptions ? json_encode($fieldOptions) : null,
            'is_required' => $this->request->getPost('is_required') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?? 0
        ];

        $result = $this->componentFieldRepository->update($id, $data);

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

    public function delete(int $componentId, int $id)
    {
        $result = $this->componentFieldRepository->delete($id);

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