<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\FacultyModel;
use App\Models\DepartmentModel;
use CodeIgniter\Controller;

class AuthController extends BaseController //new change
{
    public function registerForm()
    {

        // Load the Faculty and Department models
        $facultyModel = new FacultyModel();
        $departmentModel = new DepartmentModel();

        // Fetch all faculties and departments
        $faculties = $facultyModel->findAll();
        log_message('debug', 'Faculties Data: ' . json_encode($faculties));
        $departments = $departmentModel->findAll();

        // Load the registration form view with faculties and departments
        return view('register', [
            'showNavbar' => false,
            'faculties' => $faculties,
            'departments' => $departments
        ]);
    }

    public function register()
    {
        $validationRules = [
            'username'      => 'required|min_length[3]|max_length[50]',
            'email'         => 'required|valid_email|max_length[100]',
            'password'      => 'required|min_length[6]',
            'mobile_number' => 'required|min_length[7]|max_length[15]',  // NEW: Validate mobile number
            // Allow faculty and department to be empty (not required)
            'faculty_id'    => 'permit_empty|integer',
            'department_id' => 'permit_empty|integer',
        ];

        if (!$this->validate($validationRules)) {
            log_message('error', 'Validation failed: ' . json_encode($this->validator->getErrors()));
            log_message('debug', 'POST Data: ' . json_encode($this->request->getPost())); // Log all data
            return redirect()->back()->with('error', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $data = [
            'username'      => $this->request->getPost('username'),
            'email'         => $this->request->getPost('email'),
            'mobile_number' => $this->request->getPost('mobile_number'), // NEW: Collect mobile number
            'faculty_id'    => $this->request->getPost('faculty_id'),     // May be empty
            'department_id' => $this->request->getPost('department_id'),  // May be empty
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role_id'       => 3, // Default role: Student
        ];

        log_message('debug', 'Registering user with data: ' . json_encode($data));

        if ($userModel->insert($data)) {
            return redirect()->to('/login')->with('success', 'Registration successful');
        }

        log_message('error', 'Registration failed: ' . json_encode($userModel->errors()));
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

        $roleModel = new \App\Models\RoleModel();
        $roleData = $roleModel->find($user['role_id']);

        if ($user && password_verify($password, $user['password_hash'])) {
            session()->set([
                'id' => $user['id'],
                'username' => $user['username'],
                'role_id' => $user['role_id'],
                'role'          => $roleData['name'],
                'faculty_id' => $user['faculty_id'],
                'department_id' => $user['department_id'],
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
