<?php

namespace App\Core\Modules\Component\Controllers;

use App\Core\Modules\Component\Repositories\Interfaces\ComponentRepositoryInterface;
use App\Core\Modules\Component\Repositories\Interfaces\ComponentFieldRepositoryInterface;
use App\Core\Modules\Component\Repositories\Interfaces\ComponentInstanceRepositoryInterface;
use App\Core\Modules\Component\Repositories\Interfaces\ComponentInstanceDataRepositoryInterface;
use App\Core\Modules\Component\Repositories\Interfaces\ContentComponentRepositoryInterface;

class ComponentInstanceController extends BaseController
{
    protected ComponentRepositoryInterface $componentRepository;
    protected ComponentFieldRepositoryInterface $componentFieldRepository;
    protected ComponentInstanceRepositoryInterface $componentInstanceRepository;
    protected ComponentInstanceDataRepositoryInterface $componentInstanceDataRepository;
    protected ContentComponentRepositoryInterface $contentComponentRepository;

    public function __construct()
    {
        $this->componentRepository = service('componentRepository');
        $this->componentFieldRepository = service('componentFieldRepository');
        $this->componentInstanceRepository = service('componentInstanceRepository');
        $this->componentInstanceDataRepository = service('componentInstanceDataRepository');
        $this->contentComponentRepository = service('contentComponentRepository');
    }

    public function store(int $contentId, int $componentId)
    {
        $component = $this->componentRepository->findById($componentId);

        if (!$component) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Bileşen bulunamadı.'
            ])->setStatusCode(404);
        }

        $instance = $this->componentInstanceRepository->create([
            'component_id' => $componentId,
            'title' => $this->request->getPost('title'),
            'is_global' => 0
        ]);

        if (!$instance) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Bileşen instance oluşturulamadı.'
            ])->setStatusCode(500);
        }

        $maxSortOrder = 0;
        $existingComponents = $this->contentComponentRepository->getByContentId($contentId);
        
        foreach ($existingComponents as $cc) {
            if ($cc->sort_order > $maxSortOrder) {
                $maxSortOrder = $cc->sort_order;
            }
        }

        $contentComponent = $this->contentComponentRepository->create([
            'content_id' => $contentId,
            'component_instance_id' => $instance->id,
            'sort_order' => $maxSortOrder + 1
        ]);

        if (!$contentComponent) {
            $this->componentInstanceRepository->delete($instance->id);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Bileşen içeriğe eklenemedi.'
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Bileşen başarıyla eklendi.',
            'data' => [
                'instance_id' => $instance->id,
                'content_component_id' => $contentComponent->id
            ]
        ]);
    }

    public function updateData(int $instanceId)
    {
        $instance = $this->componentInstanceRepository->findById($instanceId);

        if (!$instance) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Instance bulunamadı.'
            ])->setStatusCode(404);
        }

        $title = $this->request->getPost('title');
        if ($title) {
            $this->componentInstanceRepository->update($instanceId, ['title' => $title]);
        }

        $fields = $this->componentFieldRepository->getByComponentId($instance->component_id);

        foreach ($fields as $field) {
            $value = $this->request->getPost($field->field_key);

            if ($value !== null) {
                $this->componentInstanceDataRepository->upsert($instanceId, $field->field_key, $value);
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Bileşen verileri başarıyla güncellendi.'
        ]);
    }

    public function delete(int $contentId, int $contentComponentId)
    {
        $result = $this->contentComponentRepository->delete($contentComponentId);

        if (!$result) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Bileşen silinemedi.'
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Bileşen başarıyla silindi.'
        ]);
    }

    public function reorder(int $contentId)
    {
        $order = $this->request->getPost('order');

        if (!is_array($order)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Geçersiz sıralama verisi.'
            ])->setStatusCode(422);
        }

        foreach ($order as $index => $contentComponentId) {
            $this->contentComponentRepository->update($contentComponentId, [
                'sort_order' => $index
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Sıralama başarıyla güncellendi.'
        ]);
    }
}
