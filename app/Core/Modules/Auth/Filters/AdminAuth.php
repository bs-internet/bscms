<?php

namespace App\Core\Modules\Auth\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if ($session->has('admin_logged_in') && $session->get('admin_logged_in') === true) {
            // Session Hijacking Protection: Validate User-Agent
            $currentUserAgent = $request->getServer('HTTP_USER_AGENT') ?? '';
            $sessionUserAgent = $session->get('admin_user_agent');

            if ($sessionUserAgent && $currentUserAgent !== $sessionUserAgent) {
                log_message('warning', 'Session hijacking attempt detected. User-Agent mismatch for user: ' . $session->get('admin_username'));

                $session->destroy();
                helper('cookie');
                delete_cookie('admin_remember_token');

                return redirect()->to('/admin/login')->with('error', 'Güvenlik nedeniyle oturumunuz sonlandırıldı. Lütfen tekrar giriş yapın.');
            }

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
                    $session->regenerate();
                    $session->set([
                        'admin_logged_in' => true,
                        'admin_user_id' => $user->id,
                        'admin_username' => $user->username,
                        'admin_ip' => $request->getIPAddress(),
                        'admin_user_agent' => $request->getServer('HTTP_USER_AGENT') ?? ''
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
