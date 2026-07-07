<?php

namespace App\Controllers;

use App\Models\AdminModel;

class AuthController extends BaseController
{
    public function login()
    {
        // Jika sudah login, redirect ke dashboard admin
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('admin'));
        }

        $data = [
            'title' => 'Login Admin - Warmindo Connect'
        ];

        return view('auth/login', $data);
    }

    public function processLogin()
    {
        $session = session();
        $adminModel = new AdminModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $adminModel->where('username', $username)->first();

        if ($admin) {
            if (password_verify($password, $admin['password'])) {
                $ses_data = [
                    'admin_id'   => $admin['id'],
                    'admin_name' => $admin['name'],
                    'username'   => $admin['username'],
                    'isLoggedIn' => true,
                ];
                $session->set($ses_data);
                return redirect()->to(base_url('admin'));
            } else {
                $session->setFlashdata('error', 'Password salah.');
                return redirect()->to(base_url('admin/login'));
            }
        } else {
            $session->setFlashdata('error', 'Username tidak ditemukan.');
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('admin/login'));
    }
}
