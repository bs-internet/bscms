<?php

namespace App\Core\Modules\System\Controllers;

use App\Core\Shared\Controllers\BaseController;

use App\Core\Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;

class ProfileController extends BaseController
{
    protected UserRepositoryInterface $userRepository;

    public function __construct()
    {
        $this->userRepository = service('userRepository');
    }

    public function index()
    {
        $userId = session()->get('admin_user_id');
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            return redirect()->to('/admin/dashboard')->with('error', 'Kullanıcı bulunamadı.');
        }

        return view('App\Core\Modules\System\Views/profile/index', ['user' => $user]);
    }

    public function update()
    {
        $userId = session()->get('admin_user_id');

        $rules = [
            'email' => 'required|valid_email',
            'username' => 'required|min_length[3]',
        ];

        // Only validate password if it's being changed
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['password_confirm'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
        ];

        if ($this->request->getPost('password')) {
            // Let UserModel->beforeUpdate callback handle hashing
            $data['password'] = $this->request->getPost('password');
        }

        $result = $this->userRepository->update($userId, $data);

        if ($result) {
            // Update session if username changed
            session()->set('admin_username', $data['username']);
            return redirect()->back()->with('success', 'Profil başarıyla güncellendi.');
        }

        return redirect()->back()->with('error', 'Güncelleme başarısız.');
    }
}


