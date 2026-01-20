<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Validation\AuthValidation;

class AuthController extends BaseController
{
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = service('userRepository');
    }

    private function checkAuth()
    {
        return session()->has('user_id');
    }

    public function login()
    {
        if ($this->checkAuth()) {
            return redirect()->to('/admin/dashboard');
        }

        return view('admin/login');
    }

    public function authenticate()
    {
        if (!$this->validate(AuthValidation::loginRules(), AuthValidation::loginMessages())) {
            log_message('error', 'Login validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userRepository->findByUsername($username);

        if (!$user) {
            log_message('error', 'Login failed: User not found for username: ' . $username);
            return redirect()->back()->with('error', 'Kullanıcı adı veya şifre hatalı.');
        }

        if (!password_verify($password, $user->password)) {
            log_message('error', 'Login failed: Password mismatch for username: ' . $username . '. Hash: ' . $user->password);
            return redirect()->back()->with('error', 'Kullanıcı adı veya şifre hatalı.');
        }

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

        $session = session();
        $session->set([
            'admin_logged_in' => true,
            'admin_user_id' => $user->id,
            'admin_username' => $user->username
        ]);

        return redirect()->to('/admin/dashboard');
    }

    public function forgotPassword()
    {
        return view('admin/forgot_password');
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
            return redirect()->to('/admin/login')->with('error', 'Geçersiz şifre sıfırlama bağlantısı.');
        }

        // Verify token (Raw query because UserRepository might not have findByToken yet, checking implementation...)
        // Ideally we add findByResetToken to repo, but standard findOne logic works if we query directly or add method.
        // Let's assume we need to find user by token. Since repo interface is strict, I'll use Model directly here for speed or add to repo.
        // Let's verify token via Repository if possible.
        // UserRepository Interface has no 'findByResetToken'. I'll fetch user logic differently or just add it.
        // For now, I'll iterate or use model. Let's use Model instance from repository if public (it's protected).
        // I will add a verifyResetToken method to AuthController for now that does a raw check via user class.

        $db = \Config\Database::connect();
        $user = $db->table('users')
            ->where('reset_token', $token)
            ->where('reset_expires_at >', date('Y-m-d H:i:s'))
            ->get()
            ->getRow();

        if (!$user) {
            return redirect()->to('/admin/login')->with('error', 'Geçersiz veya süresi dolmuş şifre sıfırlama bağlantısı.');
        }

        return view('admin/reset_password', ['token' => $token]);
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

        $db = \Config\Database::connect();
        $user = $db->table('users')
            ->where('reset_token', $token)
            ->where('reset_expires_at >', date('Y-m-d H:i:s'))
            ->get()
            ->getRow();

        if (!$user) {
            return redirect()->to('/admin/login')->with('error', 'Geçersiz işlem.');
        }

        // Update Password
        // Use Repository to ensure hashing logic in Model is triggered? 
        // Or Model update. Repository->update($id, data) uses Model->save/update which triggers callbacks.

        $this->userRepository->update($user->id, [
            'password' => $password,
            'reset_token' => null,
            'reset_expires_at' => null
        ]);

        return redirect()->to('/admin/login')->with('success', 'Şifreniz başarıyla güncellendi. Giriş yapabilirsiniz.'); // Flash success needs handling in login view
    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        helper('cookie');
        delete_cookie('admin_remember_token');

        return redirect()->to('/admin/login');
    }
}