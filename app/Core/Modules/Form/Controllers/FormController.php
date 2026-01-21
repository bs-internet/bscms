<?php

namespace App\Core\Modules\Form\Controllers;

use App\Core\Modules\Form\Repositories\Interfaces\FormRepositoryInterface;
use App\Core\Modules\Form\Repositories\Interfaces\FormFieldRepositoryInterface;
use App\Core\Modules\Form\Repositories\Interfaces\FormSubmissionRepositoryInterface;
use App\Core\Modules\Form\Repositories\Interfaces\FormSubmissionDataRepositoryInterface;
use App\Core\Modules\Form\Validation\FormValidation;
use App\Core\Modules\Form\Enums\SubmissionStatus;

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

        return view('App\Core\Modules\Form\Views/index', ['forms' => $forms]);
    }

    public function create()
    {
        return view('App\Core\Modules\Form\Views/create');
    }

    public function store()
    {
        if (!$this->validate(FormValidation::rules(), FormValidation::messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'email_to' => $this->request->getPost('email_to'),
            'success_message' => $this->request->getPost('success_message')
        ];

        $form = $this->formRepository->create($data);

        if (!$form) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Form oluşturulamadı.');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Form oluşturma başarısız.');
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

        return view('App\Core\Modules\Form\Views/edit', [
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

        $db = \Config\Database::connect();
        $db->transStart();

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'email_to' => $this->request->getPost('email_to'),
            'success_message' => $this->request->getPost('success_message')
        ];

        $result = $this->formRepository->update($id, $data);

        if (!$result) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Form güncellenemedi.');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Güncelleme başarısız.');
        }

        return redirect()->to('/admin/forms')->with('success', 'Form başarıyla güncellendi.');
    }

    public function delete(int $id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $result = $this->formRepository->delete($id);

        if (!$result) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Form silinemedi.');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Silme işlemi başarısız.');
        }

        \CodeIgniter\Events\Events::trigger('form_deleted', $id);

        return redirect()->to('/admin/forms')->with('success', 'Form başarıyla silindi.');
    }

    public function submissions(int $formId)
    {
        $form = $this->formRepository->findById($formId);

        if (!$form) {
            return redirect()->to('/admin/forms')->with('error', 'Form bulunamadı.');
        }

        $submissions = $this->formSubmissionRepository->getByFormId($formId);

        return view('App\Core\Modules\Form\Views/submissions', [
            'form' => $form,
            'submissions' => $submissions
        ]);
    }

    public function submissionDetail(int $formId, int $id)
    {
        $form = $this->formRepository->findById($formId);
        $submission = $this->formSubmissionRepository->findById($id);

        if (!$form || !$submission) {
            return redirect()->to("/App\Core\Modules\Form\Views\forms\{$formId}/submissions")->with('error', 'Gönderim bulunamadı.');
        }

        $submissionData = $this->formSubmissionDataRepository->getBySubmissionId($id);
        $fields = $this->formFieldRepository->getByFormId($formId);

        if ($submission->status === SubmissionStatus::NEW ->value) {
            // Using Enum ->value for comparison if status is string in DB
            // But verify if $submission->status is cast or raw string.
            // Assuming string from previous analysis.
            $this->formSubmissionRepository->update($id, ['status' => SubmissionStatus::READ->value]);
        }
        // Note: Logic above changed slightly to use ->value just in case.
        // Original: $submission->status === SubmissionStatus::NEW
        // If SubmissionStatus is Enum, direct comparison works if property is cast to Enum,
        // but often in CI4 entities it's string. Better use ->value or check Model casting.
        // Checking SubmissionStatus.php (Enum) ... it is backed by string.

        return view('App\Core\Modules\Form\Views/submission-detail', [
            'form' => $form,
            'submission' => $submission,
            'submissionData' => $submissionData,
            'fields' => $fields
        ]);
    }
}



