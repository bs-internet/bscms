<?php

namespace App\Core\Modules\Auth\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('auth'); // Or however we construct the User
        // Actually, in this system, the user is likely in the session.
        // Let's get the user from Repository via ID in session

        $session = session();
        if (!$session->has('admin_user_id')) {
            return redirect()->to('/admin/login');
        }

        $userId = $session->get('admin_user_id');
        $userRepository = service('userRepository');
        $user = $userRepository->findById($userId);

        if (!$user) {
            $session->destroy();
            return redirect()->to('/admin/login');
        }

        // Arguments check
        if (empty($arguments)) {
            return;
        }

        $permission = $arguments[0];

        if (!$user->can($permission)) {
            return redirect()->back()->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
            // Or show 403 view
            // throw new \CodeIgniter\Exceptions\PageNotFoundException("Yetkisiz Erişim");
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
