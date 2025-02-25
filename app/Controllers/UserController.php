<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\FacultyModel;
use App\Models\DepartmentModel;
use App\Models\BorrowingModel;
use App\Models\ComponentModel;

class UserController extends BaseController
{
    public function profile($userId)
    {
        $userModel = new UserModel();
        $facultyModel = new FacultyModel();
        $departmentModel = new DepartmentModel();
        $borrowingModel = new BorrowingModel();
        $componentModel = new ComponentModel();

        // 1. Fetch the user record
        $user = $userModel->find($userId);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // 2. Fetch Faculty and Department names (if available)
        $faculty = $facultyModel->find($user['faculty_id']);
        $department = $departmentModel->find($user['department_id']);

        // 3. Fetch the list of items borrowed by the user
        // We assume "approved" status means currently borrowed.
        $borrowings = $borrowingModel
            ->select('borrowings.*, components.component_code, components.name as component_name')
            ->join('components', 'components.id = borrowings.component_id', 'left')
            ->where('borrowings.user_id', $userId)
            ->where('borrowings.status', 'approved')
            ->findAll();

        // 4. Prepare data for the view.
        $data = [
            'user'           => $user,
            'facultyName'    => (!empty($faculty) && isset($faculty['name'])) ? $faculty['name'] : 'Unavailable',
            'departmentName' => (!empty($department) && isset($department['name'])) ? $department['name'] : 'Unavailable',
            'borrowedItems'  => $borrowings,
        ];
        return view('users/profile', $data);
    }
}
