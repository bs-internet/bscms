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
            // Session Hijacking Protection: Validate User-Agent and IP Subnet
            $currentUserAgent = $request->getServer('HTTP_USER_AGENT') ?? '';
            $sessionUserAgent = $session->get('admin_user_agent');

            $currentIp = $request->getIPAddress();
            $ipParts = explode('.', $currentIp);
            $currentSubnet = count($ipParts) >= 3 ? $ipParts[0] . '.' . $ipParts[1] . '.' . $ipParts[2] : $currentIp;
            $sessionSubnet = $session->get('admin_ip_subnet');

            if (
                ($sessionUserAgent && $currentUserAgent !== $sessionUserAgent) ||
                ($sessionSubnet && $currentSubnet !== $sessionSubnet)
            ) {
                log_message('warning', 'Session hijacking attempt detected. User-Agent or IP subnet mismatch for user: ' . $session->get('admin_username'));

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

                // Hash token for comparison
                $hashedToken = hash('sha256', $token);

                // Check DB
                $userRepository = service('userRepository');
                $user = $userRepository->findById($userId);

                if (
                    $user &&
                    hash_equals($user->remember_token, $hashedToken) &&
                    $user->remember_expires_at > date('Y-m-d H:i:s')
                ) {
                    // Valid Token - Rotate it for security
                    $newToken = bin2hex(random_bytes(32));
                    $newHashedToken = hash('sha256', $newToken);
                    $newExpiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

                    $userRepository->updateTokenFields($user->id, [
                        'remember_token' => $newHashedToken,
                        'remember_expires_at' => $newExpiresAt
                    ]);

                    // Update cookie with new token
                    set_cookie('admin_remember_token', $user->id . ':' . $newToken, 30 * 24 * 60 * 60);

                    // Login User
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
