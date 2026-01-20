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

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/admin/login');
    }
}