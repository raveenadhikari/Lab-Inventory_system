<?php

namespace App\Controllers;

use App\Models\LabModel;
use App\Models\RoleModel;
use App\Models\FacultyModel;
use App\Models\DepartmentModel;

class HomeController extends BaseController
{
    public function index()
    {

        // Redirect unauthenticated users
        if (!session()->has('role_id')) {
            return redirect()->to('/login');
        }
        $labModel = new LabModel();
        $roleModel = new RoleModel();

        // Get the user's role_id from the session
        $roleId = session()->get('role_id');
        $departmentId = session()->get('department_id');
        $facultyId = session()->get('faculty_id');

        // Log debug information
        log_message('debug', 'Role ID: ' . $roleId);
        log_message('debug', 'Department ID: ' . $departmentId);

        // Fetch the role details from the database
        $role = $roleModel->find($roleId);


        // Default to an empty labs array
        $labs = [];
        $labsTitle = 'Labs: All';

        if ($role) {
            // Check if the user is an admin or superadmin by role name (role name is not working so added the role id)
            if (in_array($role['id'], ['1', '2'])) {
                // Fetch all labs for admin or superadmin
                $labs = $labModel
                    ->select('labs.*, faculties.name AS faculty_name, departments.name AS department_name')
                    ->join('faculties', 'faculties.id = labs.faculty_id', 'left')
                    ->join('departments', 'departments.id = labs.department_id', 'left')
                    ->findAll();
                $labsTitle = 'Labs: All';
            } else {
                // For students or other users, fetch labs related to their department
                $departmentId = session()->get('department_id');

                if ($departmentId) {
                    $labs = $labModel
                        ->select('labs.*, faculties.name AS faculty_name, departments.name AS department_name')
                        ->join('faculties', 'faculties.id = labs.faculty_id', 'left')
                        ->join('departments', 'departments.id = labs.department_id', 'left')
                        ->where('labs.department_id', $departmentId)
                        ->findAll();
                    $labsTitle = 'Labs: Department of ' . $this->getDepartmentName($departmentId);
                } elseif ($facultyId) {
                    $labs = $labModel
                        ->select('labs.*, faculties.name AS faculty_name, departments.name AS department_name')
                        ->join('faculties', 'faculties.id = labs.faculty_id', 'left')
                        ->join('departments', 'departments.id = labs.department_id', 'left')
                        ->where('labs.faculty_id', $facultyId)
                        ->findAll();
                    $labsTitle = 'Labs: Faculty of ' . $this->getFacultyName($facultyId);
                } else {
                    $labs = $labModel
                        ->select('labs.*, faculties.name AS faculty_name, departments.name AS department_name')
                        ->join('faculties', 'faculties.id = labs.faculty_id', 'left')
                        ->join('departments', 'departments.id = labs.department_id', 'left')
                        ->findAll();
                }
            }
        }

        // Fetch all faculties and departments for the dropdown
        $facultyModel = new FacultyModel();
        $departmentModel = new DepartmentModel();
        $faculties = $facultyModel->findAll();
        $departments = $departmentModel->findAll();

        // Pass data to the view
        return view('homepage', [
            'labs' => $labs,
            'role' => $role['name'] ?? 'Unknown',
            'labsTitle' => $labsTitle,
            'faculties' => $faculties,
            'departments' => $departments,
        ]);
    }

    public function filterLabs()
    {
        $labModel = new LabModel();
        $facultyId = $this->request->getGet('faculty_id');
        $departmentId = $this->request->getGet('department_id');

        $labs = $labModel
            ->select('labs.*, faculties.name AS faculty_name, departments.name AS department_name')
            ->join('faculties', 'faculties.id = labs.faculty_id', 'left')
            ->join('departments', 'departments.id = labs.department_id', 'left');

        if ($facultyId && $facultyId != 'all') {
            $labs->where('labs.faculty_id', $facultyId);
        }

        if ($departmentId && $departmentId != 'all') {
            $labs->where('labs.department_id', $departmentId);
        }

        $labs = $labs->findAll();

        $labsTitle = 'Labs: All';
        if ($facultyId && $facultyId != 'all') {
            $labsTitle = 'Labs: Faculty of ' . $this->getFacultyName($facultyId);
        }
        if ($departmentId && $departmentId != 'all') {
            $labsTitle = 'Labs: Department of ' . $this->getDepartmentName($departmentId);
        }

        // Fetch all faculties and departments for the dropdown
        $facultyModel = new FacultyModel();
        $departmentModel = new DepartmentModel();
        $faculties = $facultyModel->findAll();
        $departments = $departmentModel->findAll();

        return view('homepage', [
            'labs' => $labs,
            'role' => session()->get('role'),
            'labsTitle' => $labsTitle,
            'faculties' => $faculties,
            'departments' => $departments,
        ]);
    }


    private function getDepartmentName($departmentId)
    {
        $departmentModel = new \App\Models\DepartmentModel();
        $department = $departmentModel->find($departmentId);
        return $department['name'] ?? 'Unknown';
    }

    private function getFacultyName($facultyId)
    {
        $facultyModel = new \App\Models\FacultyModel();
        $faculty = $facultyModel->find($facultyId);
        return $faculty['name'] ?? 'Unknown';
    }
}
