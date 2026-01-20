<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if ($session->has('admin_logged_in') && $session->get('admin_logged_in') === true) {
            return;
        }

        // Check for Remember Me Cookie
        helper('cookie');
        $rememberCookie = get_cookie('admin_remember_token');

        if ($rememberCookie) {
            $parts = explode(':', $rememberCookie);
            if (count($parts) === 2) {
                $userId = $parts[0];
                $token = $parts[1];

                // Check DB
                $userRepository = service('userRepository');
                $user = $userRepository->findById($userId);

                if (
                    $user &&
                    $user->remember_token === $token &&
                    $user->remember_expires_at > date('Y-m-d H:i:s')
                ) {
                    // Valid Token - Login User
                    $session->set([
                        'admin_logged_in' => true,
                        'admin_user_id' => $user->id,
                        'admin_username' => $user->username
                    ]);
                    return;
                }
            }
        }

        return redirect()->to('/admin/login')->with('error', 'Giriş yapmanız gerekiyor.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // After işlemi yok
    }
}