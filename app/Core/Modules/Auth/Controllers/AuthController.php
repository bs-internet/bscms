<?php

namespace App\Core\Modules\Auth\Controllers;

use App\Core\Shared\Controllers\BaseController;

use App\Core\Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use App\Core\Modules\Auth\Validation\AuthValidation;
use App\Core\Modules\Auth\Libraries\RateLimiter;

/**
 * Authentication Controller
 * 
 * Handles user authentication, password reset, and session management
 * for the admin panel.
 */
class AuthController extends BaseController
{
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = service('userRepository');
    }

    /**
     * Check if user is authenticated
     * 
     * @return bool
     */
    private function checkAuth(): bool
    {
        return session()->has('admin_logged_in') && session()->get('admin_logged_in') === true;
    }

    /**
     * Display login page
     * 
     * Redirects to dashboard if already authenticated
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function login()
    {
        if ($this->checkAuth()) {
            return redirect()->to('/admin/dashboard');
        }

        return view('App\Core\Modules\Auth\Views\login');
    }

    public function authenticate()
    {
        if (!$this->validate(AuthValidation::loginRules(), AuthValidation::loginMessages())) {
            log_message('error', 'Login validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $ipAddress = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent()->getAgentString();

        // Rate Limiting Check
        $rateLimiter = new RateLimiter();
        $rateLimitKey = RateLimiter::loginKey($username, $ipAddress);

        if ($rateLimiter->tooManyAttempts($rateLimitKey)) {
            $seconds = $rateLimiter->availableIn($rateLimitKey);
            $minutes = ceil($seconds / 60);

            log_message('warning', "Rate limit exceeded for username: {$username} from IP: {$ipAddress}");

            return redirect()->back()->with(
                'error',
                "Çok fazla başarısız deneme. Lütfen {$minutes} dakika sonra tekrar deneyin."
            );
        }

        $user = $this->userRepository->findByUsername($username);

        if (!$user) {
            // Log failed attempt
            $this->logLoginAttempt(null, $username, $ipAddress, $userAgent, false);
            $rateLimiter->hit($rateLimitKey);

            log_message('error', 'Login failed: User not found for username: ' . $username);
            return redirect()->back()->with('error', 'Kullanıcı adı veya şifre hatalı.');
        }

        if (!password_verify($password, $user->password)) {
            // Log failed attempt
            $this->logLoginAttempt($user->id, $username, $ipAddress, $userAgent, false);
            $rateLimiter->hit($rateLimitKey);

            log_message('error', 'Login failed: Password mismatch for username: ' . $username);
            return redirect()->back()->with('error', 'Kullanıcı adı veya şifre hatalı.');
        }

        // Successful login - clear rate limiter
        $rateLimiter->clear($rateLimitKey);

        // Log successful attempt
        $this->logLoginAttempt($user->id, $username, $ipAddress, $userAgent, true);

        // Remember Me Logic
        if ($this->request->getPost('remember')) {
            $token = bin2hex(random_bytes(32));
            $hashedToken = hash('sha256', $token);
            $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

            $this->userRepository->update($user->id, [
                'remember_token' => $hashedToken,  // Store hash, not plain token
                'remember_expires_at' => $expiresAt
            ]);

            helper('cookie');
            set_cookie('admin_remember_token', $user->id . ':' . $token, 30 * 24 * 60 * 60); // 30 days
        }

        // Session regeneration for security
        $session = session();
        $session->regenerate();

        // Store IP subnet for hijacking detection
        $ipParts = explode('.', $ipAddress);
        $ipSubnet = count($ipParts) >= 3 ? $ipParts[0] . '.' . $ipParts[1] . '.' . $ipParts[2] : $ipAddress;

        $session->set([
            'admin_logged_in' => true,
            'admin_user_id' => $user->id,
            'admin_username' => $user->username,
            'admin_ip' => $ipAddress,
            'admin_ip_subnet' => $ipSubnet,
            'admin_user_agent' => $userAgent
        ]);

        return redirect()->to('/admin/dashboard');
    }

    /**
     * Log login attempt to database
     */
    private function logLoginAttempt(?int $userId, string $username, string $ip, string $userAgent, bool $success): void
    {
        $db = \Config\Database::connect();
        $db->table('login_attempts')->insert([
            'user_id' => $userId,
            'username' => $username,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'success' => $success ? 1 : 0,
            'attempted_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function forgotPassword()
    {
        return view('App\Core\Modules\Auth\Views\forgot_password');
    }

    public function sendResetLink()
    {
        $email = $this->request->getPost('email');
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            // Security: Don't reveal if user exists + Add fake delay to prevent timing attack
            usleep(random_int(400000, 600000)); // 400-600ms delay
            return redirect()->back()->with('success', 'Eğer bu e-posta adresi kayıtlıysa, şifre sıfırlama bağlantısı gönderildi.');
        }

        // Generate token and hash it
        $token = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->userRepository->update($user->id, [
            'reset_token' => $hashedToken,  // Store hash, not plain token
            'reset_expires_at' => $expiresAt
        ]);

        $resetLink = base_url("/admin/reset-password?token={$token}");

        $emailService = \Config\Services::email();
        $emailService->setTo($user->email);
        $emailService->setSubject('Şifre Sıfırlama İsteği | BSCMS');
        $emailService->setMessage("
            <h1>Şifre Sıfırlama</h1>
            <p>Merhaba {$user->username},</p>
            <p>Hesabınız için bir şifre sıfırlama talebi aldık. Aşağıdaki bağlantıya tıklayarak şifrenizi yenileyebilirsiniz:</p>
            <p><a href='{$resetLink}'>Şifremi Sıfırla</a></p>
            <p>Bu bağlantı 1 saat süreyle geçerlidir.</p>
            <p>Eğer bu talebi siz yapmadıysanız, bu e-postayı görmezden gelebilirsiniz.</p>
        ");

        if ($emailService->send()) {
            return redirect()->back()->with('success', 'Eğer bu e-posta adresi kayıtlıysa, şifre sıfırlama bağlantısı gönderildi.');
        } else {
            log_message('error', 'Email Sending Failed: ' . $emailService->printDebugger(['headers']));
            return redirect()->back()->with('error', 'E-posta gönderilemedi. Lütfen sistem yöneticisiyle iletişime geçin.');
        }
    }

    public function showResetForm()
    {
        $token = $this->request->getGet('token');

        if (!$token) {
            return redirect()->to('/admin/login')->with('error', 'Geçersiz şifre sıfırlama bağlantısı.');
        }


        // Hash the token to compare with database
        $hashedToken = hash('sha256', $token);
        $user = $this->userRepository->findByValidResetToken($hashedToken);

        if (!$user) {
            return redirect()->to('/admin/login')->with('error', 'Geçersiz veya süresi dolmuş şifre sıfırlama bağlantısı.');
        }

        return view('App\Core\Modules\Auth\Views\reset_password', ['token' => $token]);
    }

    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $passwordConfirm = $this->request->getPost('password_confirm');

        if ($password !== $passwordConfirm) {
            return redirect()->back()->withInput()->with('error', 'Şifreler eşleşmiyor.');
        }

        if (strlen($password) < 6) {
            return redirect()->back()->withInput()->with('error', 'Şifre en az 6 karakter olmalıdır.');
        }

        // Hash the token to find user
        $hashedToken = hash('sha256', $token);
        $user = $this->userRepository->findByValidResetToken($hashedToken);

        if (!$user) {
            return redirect()->to('/admin/login')->with('error', 'Geçersiz işlem.');
        }

        // Update password and clear reset tokens
        // Also clear remember tokens for security
        $this->userRepository->update($user->id, [
            'password' => $password,
            'reset_token' => null,
            'reset_expires_at' => null,
            'remember_token' => null,
            'remember_expires_at' => null
        ]);

        // Invalidate all active sessions for this user
        $db = \Config\Database::connect();
        $db->table('ci_sessions')
            ->where('data LIKE', '%"admin_user_id";i:' . $user->id . '%')
            ->delete();

        return redirect()->to('/admin/login')->with('success', 'Şifreniz başarıyla güncellendi. Giriş yapabilirsiniz.');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        helper('cookie');
        delete_cookie('admin_remember_token');

        return redirect()->to('/admin/login');
    }

    /**
     * Logout from all devices
     * Clears all sessions and remember tokens for the current user
     */
    public function logoutAllDevices()
    {
        $userId = session()->get('admin_user_id');

        if (!$userId) {
            return redirect()->to('/admin/login');
        }

        // Clear remember token
        $this->userRepository->update($userId, [
            'remember_token' => null,
            'remember_expires_at' => null
        ]);

        // Delete all sessions for this user
        $db = \Config\Database::connect();
        $db->table('ci_sessions')
            ->where('data LIKE', '%"admin_user_id";i:' . $userId . '%')
            ->delete();

        // Destroy current session
        session()->destroy();
        helper('cookie');
        delete_cookie('admin_remember_token');

        return redirect()->to('/admin/login')
            ->with('success', 'Tüm cihazlardan çıkış yapıldı. Güvenliğiniz için şifrenizi değiştirmenizi öneririz.');
    }
}


