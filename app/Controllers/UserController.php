<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function profile()
    {
        $userId = session()->get('id'); // Get the logged-in user's ID from the session
        $userModel = new UserModel();
        $user = $userModel->find($userId); // Fetch user details from the database

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User not found.');
        }

        return view('profile', [
            'user' => $user,
        ]);
    }
}
