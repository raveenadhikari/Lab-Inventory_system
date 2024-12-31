<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use CodeIgniter\Controller;

class AuthController extends BaseController //new change
{
    public function registerForm()
    {
        // Load the registration form view
        return view('register', ['showNavbar' => false]);
    }

    public function register()
    {
        // Validation rules for registration
        $validationRules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email|max_length[100]',
            'password' => 'required|min_length[6]',
            'faculty' => 'required|max_length[100]',
        ];

        // Validate input
        if (!$this->validate($validationRules)) {
            return redirect()->back()->with('error', $this->validator->getErrors());
        }

        // Initialize the UserModel
        $userModel = new \App\Models\UserModel();

        // Data to insert into the database
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'faculty' => $this->request->getPost('faculty'),
            'department' => $this->request->getPost('department'),
        ];

        $data['role_id'] = 3; // Default role: Student

        // Insert the user into the database
        if ($userModel->insert($data)) {
            return redirect()->to('/login')->with('success', 'Registration successful');
        }

        return redirect()->back()->with('error', 'Registration failed');
    }

    public function loginForm()
    {
        // Load the login form view
        return view('login', ['showNavbar' => false]);
    }

    public function login()
    {
        // Initialize the UserModel
        $userModel = new \App\Models\UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password_hash'])) {
            session()->set([
                'id' => $user['id'],
                'username' => $user['username'],
                'role_id' => $user['role_id'],
                'isLoggedIn' => true,
            ]);

            if ($user['role_id'] == 1) { // Super Admin
                return redirect()->to('/dashboard');
            } elseif ($user['role_id'] == 2) { // Admin
                return redirect()->to('/admin-dashboard');
            }
            log_message('debug', 'Session Data: ' . json_encode(session()->get()));

            return redirect()->to('/homepage');
        }

        return redirect()->back()->with('error', 'Invalid login credentials');
    }

    public function logout()
    {
        // Destroy the session
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully');
    }
    public function homepage()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return view('homepage', [
            'username' => session()->get('username'),
            'role' => session()->get('role_id'), // Ensure this is being passed correctly
        ]);
    }
}
