<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\ComponentRepositoryInterface;
use App\Repositories\Interfaces\ComponentFieldRepositoryInterface;
use App\Repositories\Interfaces\ComponentLocationRepositoryInterface;
use App\Repositories\Interfaces\ComponentInstanceRepositoryInterface;
use App\Repositories\Interfaces\ComponentInstanceDataRepositoryInterface;
use App\Repositories\Interfaces\ContentTypeRepositoryInterface;
use App\Validation\ComponentValidation;
use App\Enums\ComponentType;

class ComponentController extends BaseController
{
    protected ComponentRepositoryInterface $componentRepository;
    protected ComponentFieldRepositoryInterface $componentFieldRepository;
    protected ComponentLocationRepositoryInterface $componentLocationRepository;
    protected ComponentInstanceRepositoryInterface $componentInstanceRepository;
    protected ComponentInstanceDataRepositoryInterface $componentInstanceDataRepository;
    protected ContentTypeRepositoryInterface $contentTypeRepository;

    public function __construct()
    {
        $this->componentRepository = service('componentRepository');
        $this->componentFieldRepository = service('componentFieldRepository');
        $this->componentLocationRepository = service('componentLocationRepository');
        $this->componentInstanceRepository = service('componentInstanceRepository');
        $this->componentInstanceDataRepository = service('componentInstanceDataRepository');
        $this->contentTypeRepository = service('contentTypeRepository');
    }

    public function index()
    {
        $components = $this->componentRepository->getAll();
        return view('admin/components/index', ['components' => $components]);
    }

    public function create()
    {
        $contentTypes = $this->contentTypeRepository->getAll();
        return view('admin/components/create', [
            'contentTypes' => $contentTypes
        ]);
    }

    public function store()
    {
        if (!$this->validate(ComponentValidation::rules(), ComponentValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'description' => $this->request->getPost('description'),
            'type' => ComponentType::from($this->request->getPost('type'))
        ];

        $component = $this->componentRepository->create($data);

        if (!$component) {
            return redirect()->back()->with('error', 'Bileşen oluşturulamadı.');
        }

        if ($component->type === ComponentType::SPECIFIC) {
            $this->saveLocations($component->id);
        }

        if ($component->type === ComponentType::GLOBAL) {
            $this->componentInstanceRepository->create([
                'component_id' => $component->id,
                'title' => $component->name,
                'is_global' => 1
            ]);
        }

        return redirect()->to("/admin/components/edit/{$component->id}")->with('success', 'Bileşen başarıyla oluşturuldu. Şimdi alanları ekleyebilirsiniz.');
    }

    public function edit(int $id)
    {
        $component = $this->componentRepository->findById($id);
        
        if (!$component) {
            return redirect()->to('/admin/components')->with('error', 'Bileşen bulunamadı.');
        }

        $fields = $this->componentFieldRepository->getByComponentId($id);
        $locations = [];
        $contentTypes = [];
        $globalInstance = null;

        if ($component->type === ComponentType::SPECIFIC) {
            $locations = $this->componentLocationRepository->getByComponentId($id);
            $contentTypes = $this->contentTypeRepository->getAll();
        }

        if ($component->type === ComponentType::GLOBAL) {
            $instances = $this->componentInstanceRepository->getByComponentId($id);
            $globalInstance = !empty($instances) ? $instances[0] : null;
            
            if ($globalInstance) {
                $instanceData = $this->componentInstanceDataRepository->getByInstanceId($globalInstance->id);
            }
        }

        return view('admin/components/edit', [
            'component' => $component,
            'fields' => $fields,
            'locations' => $locations,
            'contentTypes' => $contentTypes,
            'globalInstance' => $globalInstance ?? null,
            'instanceData' => $instanceData ?? []
        ]);
    }

    public function update(int $id)
    {
        $component = $this->componentRepository->findById($id);

        if (!$component) {
            return redirect()->to('/admin/components')->with('error', 'Bileşen bulunamadı.');
        }

        if (!$this->validate(ComponentValidation::rules(), ComponentValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'description' => $this->request->getPost('description'),
            'type' => ComponentType::from($this->request->getPost('type'))
        ];

        $result = $this->componentRepository->update($id, $data);

        if (!$result) {
            return redirect()->back()->with('error', 'Bileşen güncellenemedi.');
        }

        if ($component->type === ComponentType::SPECIFIC) {
            $this->componentLocationRepository->deleteByComponent($id);
            $this->saveLocations($id);
        }

        return redirect()->to('/admin/components')->with('success', 'Bileşen başarıyla güncellendi.');
    }

    public function delete(int $id)
    {
        $result = $this->componentRepository->delete($id);

        if (!$result) {
            return redirect()->back()->with('error', 'Bileşen silinemedi.');
        }

        return redirect()->to('/admin/components')->with('success', 'Bileşen başarıyla silindi.');
    }

    public function saveGlobalData(int $id)
    {
        $component = $this->componentRepository->findById($id);

        if (!$component || $component->type !== ComponentType::GLOBAL) {
            return redirect()->back()->with('error', 'Geçersiz bileşen.');
        }

        $instances = $this->componentInstanceRepository->getByComponentId($id);
        $instance = !empty($instances) ? $instances[0] : null;

        if (!$instance) {
            return redirect()->back()->with('error', 'Global bileşen instance bulunamadı.');
        }

        $fields = $this->componentFieldRepository->getByComponentId($id);

        foreach ($fields as $field) {
            $value = $this->request->getPost($field->field_key);

            if ($value !== null) {
                $this->componentInstanceDataRepository->upsert($instance->id, $field->field_key, $value);
            }
        }

        return redirect()->back()->with('success', 'Bileşen verileri başarıyla kaydedildi.');
    }

    protected function saveLocations(int $componentId): void
    {
        $locations = $this->request->getPost('locations');

        if (is_array($locations)) {
            foreach ($locations as $location) {
                if (isset($location['type']) && isset($location['id'])) {
                    $this->componentLocationRepository->create([
                        'component_id' => $componentId,
                        'location_type' => $location['type'],
                        'location_id' => $location['id']
                    ]);
                }
            }
        }
    }
}