<?php

namespace App\Controllers;

use App\Models\LabModel;
use App\Models\FacultyModel;
use App\Models\DepartmentModel;
use App\Models\UserModel;
use App\Models\ComponentModel;
use CodeIgniter\Files\File;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;


class LabController extends BaseController
{
    public function create()
    {
        $facultyModel = new FacultyModel();
        $departmentModel = new DepartmentModel();
        $userModel = new UserModel();

        $faculties = $facultyModel->findAll();
        $departments = $departmentModel->findAll();
        $managers = $userModel->where('role_id', 4)->findAll();

        return view('labs/create', [
            'faculties' => $faculties,
            'departments' => $departments,
            'managers' => $managers,
        ]);
    }

    public function store()
    {
        $labModel = new LabModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'faculty_id' => $this->request->getPost('faculty_id'),
            'department_id' => $this->request->getPost('department_id'),
            'manager_id' => $this->request->getPost('manager_id'),
        ];

        if (!$labModel->insert($data)) {
            return redirect()->back()->with('error', 'Failed to create lab.');
        }

        return redirect()->to('/homepage')->with('success', 'Lab created successfully.');
    }

    public function edit($id)
    {
        $labModel = new LabModel();
        $facultyModel = new FacultyModel();
        $departmentModel = new DepartmentModel();
        $userModel = new UserModel();

        $lab = $labModel->find($id);
        $faculties = $facultyModel->findAll();
        $departments = $departmentModel->findAll();
        $managers = $userModel->where('role_id', 4)->findAll(); // Assuming role_id 2 is for managers

        return view('labs/edit', [
            'lab' => $lab,
            'faculties' => $faculties,
            'departments' => $departments,
            'managers' => $managers,
        ]);
    }

    public function update($id)
    {
        $labModel = new LabModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'faculty_id' => $this->request->getPost('faculty_id'),
            'department_id' => $this->request->getPost('department_id'),
            'manager_id' => $this->request->getPost('manager_id'),
        ];

        if (!$labModel->update($id, $data)) {
            return redirect()->back()->with('error', 'Failed to update lab.');
        }

        return redirect()->to('/homepage')->with('success', 'Lab updated successfully.');
    }

    public function delete($id)
    {
        $labModel = new LabModel();
        $labModel->delete($id);
        return redirect()->to('/labs')->with('success', 'Lab deleted successfully');
    }

    public function view($id)
    {
        $labModel = new LabModel();
        $componentModel = new ComponentModel();


        $lab = $labModel->find($id);

        if (!$lab) {
            // Handle case where lab does not exist
            return redirect()->to('/homepage')->with('error', 'Lab not found.');
        }

        $components = $componentModel->where('lab_id', $id)->findAll();

        return view('labs/view', [
            'lab' => $lab,
            'components' => $components, // Pass components separately
            'activeTab' => 'inventory'
        ]);
    }

    public function inventory($id)
    {
        $labModel = new LabModel();
        $lab = $labModel->find($id);

        return view('labs/view', [
            'lab' => $lab,
            'activeTab' => 'inventory'
        ]);
    }

    public function borrowingLog($id)
    {
        $labModel = new LabModel();
        $lab = $labModel->find($id);

        return view('labs/view', [
            'lab' => $lab,
            'activeTab' => 'borrowing-log'
        ]);
    }

    public function analytics($id)
    {
        $labModel = new LabModel();
        $lab = $labModel->find($id);

        return view('labs/view', [
            'lab' => $lab,
            'activeTab' => 'analytics'
        ]);
    }

    public function addComponent($labId)
    {

        $componentModel = new ComponentModel();


        $validationRules = [
            'name' => 'required',
            'date_bought' => 'required|valid_date',
            'value' => 'required|decimal',
            'state' => 'required|in_list[new,used,damaged]',
            'photo' => 'uploaded[photo]|is_image[photo]|max_size[photo,2048]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        // Handle file upload
        // Handle file upload for the photo
        $photo = $this->request->getFile('photo');
        $photoExtension = $photo->getExtension(); // Get file extension (e.g., jpg, png)
        $uniquePhotoName = uniqid() . '.' . $photoExtension; // Generate a unique file name
        $photo->move(FCPATH . 'uploads/components', $uniquePhotoName); // Move file with the unique name
        $photoPath = 'uploads/components/' . $uniquePhotoName;

        // Generate QR code with a unique file name
        $qrCode = new QrCode($this->request->getPost('name'));
        $writer = new PngWriter();
        $uniqueQrCodeName = uniqid() . '.png'; // Unique name for QR code
        $qrCodePath = FCPATH . 'uploads/qrcodes/' . $uniqueQrCodeName;
        $writer->write($qrCode)->saveToFile($qrCodePath);


        // Store relative path in the database
        //$relativeQrCodePath = 'uploads/qrcodes/' . '.png';
        $qrCodePath = 'uploads/qrcodes/' . $uniqueQrCodeName;



        // Save component to the database
        $componentModel->insert([
            'name' => $this->request->getPost('name'),
            'date_bought' => $this->request->getPost('date_bought'),
            'value' => $this->request->getPost('value'),
            'lab_id' => $labId,
            'state' => $this->request->getPost('state'),
            'photo' => $photoPath, // Save the full path with the unique file name
            'qr_code' => $qrCodePath, // Save the full path with the unique file name
        ]);

        return redirect()->back()->with('success', 'Component added successfully!');
    }

    public function deleteComponent($id)
    {
        $componentModel = new ComponentModel();


        // Find and delete the component
        $component = $componentModel->find($id);
        if ($component) {
            // Delete files from the file system
            @unlink(FCPATH . $component['photo']);
            @unlink(FCPATH . $component['qr_code']);
            $componentModel->delete($id);
        }

        return redirect()->back()->with('success', 'Component deleted successfully!');
    }
}
