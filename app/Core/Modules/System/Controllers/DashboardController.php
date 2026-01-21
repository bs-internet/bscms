<?php

namespace App\Core\Modules\System\Controllers;

use App\Core\Shared\Controllers\BaseController;
use App\Core\Modules\System\Repositories\Interfaces\ContentRepositoryInterface;
use App\Core\Modules\System\Repositories\Interfaces\FormSubmissionRepositoryInterface;

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

        return view('App\Core\Modules\System\Views/index', $data);
    }
}
