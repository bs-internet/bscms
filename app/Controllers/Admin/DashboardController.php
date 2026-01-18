<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\ContentRepositoryInterface;
use App\Repositories\Interfaces\FormSubmissionRepositoryInterface;

class DashboardController extends BaseController
{
    protected ContentRepositoryInterface $contentRepository;
    protected FormSubmissionRepositoryInterface $formSubmissionRepository;

    public function __construct()
    {
        $this->contentRepository = service('contentRepository');
        $this->formSubmissionRepository = service('formSubmissionRepository');
    }

    public function index()
    {
        $totalContents = count($this->contentRepository->getAll());
        $publishedContents = count($this->contentRepository->getAll(['status' => 'published']));
        $draftContents = count($this->contentRepository->getAll(['status' => 'draft']));

        $data = [
            'totalContents' => $totalContents,
            'publishedContents' => $publishedContents,
            'draftContents' => $draftContents
        ];

        return view('admin/dashboard', $data);
    }
}