<?php

namespace App\Controllers;

use App\Repositories\Interfaces\FormRepositoryInterface;
use App\Repositories\Interfaces\FormFieldRepositoryInterface;
use App\Repositories\Interfaces\FormSubmissionRepositoryInterface;
use App\Repositories\Interfaces\FormSubmissionDataRepositoryInterface;

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

    public function submit(string $slug)
    {
        $form = $this->formRepository->findBySlug($slug);

        if (!$form) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Form bulunamadı.'
            ])->setStatusCode(404);
        }

        $fields = $this->formFieldRepository->getByFormId($form->id);
        $validationRules = $this->buildValidationRules($fields);

        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        $submission = $this->formSubmissionRepository->create([
            'form_id' => $form->id,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'status' => 'new'
        ]);

        if (!$submission) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Form gönderimi kaydedilemedi.'
            ])->setStatusCode(500);
        }

        foreach ($fields as $field) {
            $value = $this->request->getPost($field->field_key);
            
            $this->formSubmissionDataRepository->create([
                'submission_id' => $submission->id,
                'field_key' => $field->field_key,
                'field_value' => is_array($value) ? json_encode($value) : $value
            ]);
        }

        $this->sendEmail($form, $fields, $submission);

        return $this->response->setJSON([
            'success' => true,
            'message' => $form->success_message ?? 'Form başarıyla gönderildi.'
        ]);
    }

    protected function buildValidationRules(array $fields): array
    {
        $rules = [];

        foreach ($fields as $field) {
            $fieldRules = [];

            if ($field->is_required) {
                $fieldRules[] = 'required';
            }

            switch ($field->field_type) {
                case 'email':
                    $fieldRules[] = 'valid_email';
                    break;
                case 'number':
                    $fieldRules[] = 'numeric';
                    break;
                case 'text':
                case 'textarea':
                    $fieldRules[] = 'max_length[1000]';
                    break;
            }

            if (!empty($fieldRules)) {
                $rules[$field->field_key] = implode('|', $fieldRules);
            }
        }

        return $rules;
    }

    protected function sendEmail(object $form, array $fields, object $submission): void
    {
        $email = \Config\Services::email();

        $submissionData = $this->formSubmissionDataRepository->getBySubmissionId($submission->id);
        
        $message = "Yeni Form Gönderimi\n\n";
        $message .= "Form: {$form->name}\n";
        $message .= "Tarih: " . date('d.m.Y H:i:s') . "\n";
        $message .= "IP: {$submission->ip_address}\n\n";
        $message .= "---\n\n";

        foreach ($submissionData as $data) {
            $field = null;
            foreach ($fields as $f) {
                if ($f->field_key === $data->field_key) {
                    $field = $f;
                    break;
                }
            }

            $label = $field ? $field->field_label : $data->field_key;
            $message .= "{$label}: {$data->field_value}\n";
        }

        $email->setTo($form->email_to);
        $email->setSubject("Yeni Form Gönderimi: {$form->name}");
        $email->setMessage($message);
        $email->send();
    }
}