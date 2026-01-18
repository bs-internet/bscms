<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController as AppBaseController;

class BaseController extends AppBaseController
{
    protected function checkAuth(): bool
    {
        $session = session();
        return $session->has('admin_logged_in') && $session->get('admin_logged_in') === true;
    }

    protected function requireAuth()
    {
        if (!$this->checkAuth()) {
            return redirect()->to('/admin/login');
        }
    }
}
