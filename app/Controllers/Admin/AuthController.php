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
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userRepository->findByUsername($username);

        if (!$user || !password_verify($password, $user->password)) {
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

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/admin/login');
    }
}