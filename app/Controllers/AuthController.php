<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function registerForm()
    {
        // Load the registration form view
        return view('register');
    }

    public function register()
    {
        // Validation rules for registration
        $validationRules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email|max_length[100]',
            'password' => 'required|min_length[6]',
        ];

        // Validate input
        if (!$this->validate($validationRules)) {
            // Return to the form with validation errors
            return redirect()->back()->with('error', $this->validator->getErrors());
        }

        // Initialize the UserModel
        $userModel = new \App\Models\UserModel();

        // Data to insert into the database
        $dataToInsert = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ];

        // Insert the user into the database
        if ($userModel->insert($dataToInsert)) {
            // Redirect to the login page on success
            return redirect()->to('/login')->with('success', 'Registration successful');
        }

        // Redirect back with an error message on failure
        return redirect()->back()->with('error', 'Registration failed');
    }

    public function loginForm()
    {
        // Load the login form view
        return view('login');
    }

    public function login()
    {
        // Initialize the UserModel
        $userModel = new UserModel();

        // Retrieve email and password from POST data
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Fetch the user by email
        $user = $userModel->where('email', $email)->first();

        // Verify the user's credentials
        if ($user && password_verify($password, $user['password_hash'])) {
            // Set session data for the logged-in user
            session()->set([
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'isLoggedIn' => true,
            ]);

            // Redirect to the homepage
            return redirect()->to('/homepage');
        }

        // Redirect back with an error message on failure
        return redirect()->back()->with('error', 'Invalid login credentials');
    }

    public function logout()
    {
        // Destroy the session
        session()->destroy();

        // Redirect to the login page
        return redirect()->to('/login')->with('success', 'Logged out successfully');
    }

    public function homepage()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Load the homepage view with user details
        return view('homepage', [
            'username' => session()->get('username'),
            'email' => session()->get('email'),
        ]);
    }
}
