<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\FormRepositoryInterface;
use App\Repositories\Interfaces\FormFieldRepositoryInterface;
use App\Repositories\Interfaces\FormSubmissionRepositoryInterface;
use App\Repositories\Interfaces\FormSubmissionDataRepositoryInterface;
use App\Validation\FormValidation;
use App\Enums\SubmissionStatus;

class FormController extends BaseController
{
    protected FormRepositoryInterface $formRepository;
    protected FormFieldRepositoryInterface $formFieldRepository;
    protected FormSubmissionRepositoryInterface $formSubmissionRepository;
    protected FormSubmissionDataRepositoryInterface $formSubmissionDataRepository;

    public function __construct()
    {
        $this->formRepository = service('formRepository');
        $this->formFieldRepository = service('formFieldRepository');
        $this->formSubmissionRepository = service('formSubmissionRepository');
        $this->formSubmissionDataRepository = service('formSubmissionDataRepository');
    }

    public function index()
    {
        $forms = $this->formRepository->getAll();

        return view('admin/forms/index', ['forms' => $forms]);
    }

    public function create()
    {
        return view('admin/forms/create');
    }

    public function store()
    {
        if (!$this->validate(FormValidation::rules(), FormValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'email_to' => $this->request->getPost('email_to'),
            'success_message' => $this->request->getPost('success_message')
        ];

        $form = $this->formRepository->create($data);

        if (!$form) {
            return redirect()->back()->with('error', 'Form oluşturulamadı.');
        }

        return redirect()->to('/admin/forms')->with('success', 'Form başarıyla oluşturuldu.');
    }

    public function edit(int $id)
    {
        $form = $this->formRepository->findById($id);

        if (!$form) {
            return redirect()->to('/admin/forms')->with('error', 'Form bulunamadı.');
        }

        $fields = $this->formFieldRepository->getByFormId($id);

        return view('admin/forms/edit', [
            'form' => $form,
            'fields' => $fields
        ]);
    }

    public function update(int $id)
    {
        $form = $this->formRepository->findById($id);

        if (!$form) {
            return redirect()->to('/admin/forms')->with('error', 'Form bulunamadı.');
        }

        if (!$this->validate(FormValidation::rules(true, $id), FormValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'email_to' => $this->request->getPost('email_to'),
            'success_message' => $this->request->getPost('success_message')
        ];

        $result = $this->formRepository->update($id, $data);

        if (!$result) {
            return redirect()->back()->with('error', 'Form güncellenemedi.');
        }

        return redirect()->to('/admin/forms')->with('success', 'Form başarıyla güncellendi.');
    }

    public function delete(int $id)
    {
        $result = $this->formRepository->delete($id);

        if (!$result) {
            return redirect()->back()->with('error', 'Form silinemedi.');
        }

        return redirect()->to('/admin/forms')->with('success', 'Form başarıyla silindi.');
    }

    public function submissions(int $formId)
    {
        $form = $this->formRepository->findById($formId);

        if (!$form) {
            return redirect()->to('/admin/forms')->with('error', 'Form bulunamadı.');
        }

        $submissions = $this->formSubmissionRepository->getByFormId($formId);

        return view('admin/forms/submissions', [
            'form' => $form,
            'submissions' => $submissions
        ]);
    }

    public function submissionDetail(int $formId, int $id)
    {
        $form = $this->formRepository->findById($formId);
        $submission = $this->formSubmissionRepository->findById($id);

        if (!$form || !$submission) {
            return redirect()->to("/admin/forms/{$formId}/submissions")->with('error', 'Gönderim bulunamadı.');
        }

        $submissionData = $this->formSubmissionDataRepository->getBySubmissionId($id);
        $fields = $this->formFieldRepository->getByFormId($formId);

        if ($submission->status === SubmissionStatus::NEW) {
            $this->formSubmissionRepository->update($id, ['status' => SubmissionStatus::READ]);
        }

        return view('admin/forms/submission-detail', [
            'form' => $form,
            'submission' => $submission,
            'submissionData' => $submissionData,
            'fields' => $fields
        ]);
    }
}