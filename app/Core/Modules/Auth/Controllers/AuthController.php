<?php

namespace App\Core\Modules\Auth\Controllers;

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
            return redirect()->to('/App\Core\Modules\System\Views\dashboard\index');
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
            $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

            $this->userRepository->update($user->id, [
                'remember_token' => $token,
                'remember_expires_at' => $expiresAt
            ]);

            helper('cookie');
            set_cookie('admin_remember_token', $user->id . ':' . $token, 30 * 24 * 60 * 60); // 30 days
        }

        // Session regeneration for security
        $session = session();
        $session->regenerate();

        $session->set([
            'admin_logged_in' => true,
            'admin_user_id' => $user->id,
            'admin_username' => $user->username,
            'admin_ip' => $ipAddress,
            'admin_user_agent' => $userAgent
        ]);

        return redirect()->to('/App\Core\Modules\System\Views\dashboard\index');
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
        return view('App\Core\Modules\Auth\Viewsorgot_password');
    }

    public function sendResetLink()
    {
        $email = $this->request->getPost('email');
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            // Security: Don't reveal if user exists
            return redirect()->back()->with('success', 'Eğer bu e-posta adresi kayıtlıysa, şifre sıfırlama bağlantısı gönderildi.');
        }

        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->userRepository->update($user->id, [
            'reset_token' => $token,
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
            return redirect()->to('/App\Core\Modules\Auth\Views\login')->with('error', 'Geçersiz şifre sıfırlama bağlantısı.');
        }

        $user = $this->userRepository->findByValidResetToken($token);

        if (!$user) {
            return redirect()->to('/App\Core\Modules\Auth\Views\login')->with('error', 'Geçersiz veya süresi dolmuş şifre sıfırlama bağlantısı.');
        }

        return view('App\Core\Modules\Auth\Views
eset_password', ['token' => $token]);
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

        $user = $this->userRepository->findByValidResetToken($token);

        if (!$user) {
            return redirect()->to('/App\Core\Modules\Auth\Views\login')->with('error', 'Geçersiz işlem.');
        }

        $this->userRepository->update($user->id, [
            'password' => $password,
            'reset_token' => null,
            'reset_expires_at' => null
        ]);

        return redirect()->to('/App\Core\Modules\Auth\Views\login')->with('success', 'Şifreniz başarıyla güncellendi. Giriş yapabilirsiniz.');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        helper('cookie');
        delete_cookie('admin_remember_token');

        return redirect()->to('/App\Core\Modules\Auth\Views\login');
    }
}


