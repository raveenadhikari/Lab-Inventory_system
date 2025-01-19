<?php

namespace App\Controllers;

use App\Models\DepartmentModel;

class DepartmentController extends BaseController
{
    public function getDepartmentsByFaculty($faculty_id)
    {
        $departmentModel = new DepartmentModel();

        // Fetch departments for the given faculty
        $departments = $departmentModel->where('faculty_id', $faculty_id)->findAll();

        return $this->response->setJSON($departments);
    }
}
