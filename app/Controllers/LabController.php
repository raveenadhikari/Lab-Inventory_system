<?php

namespace App\Controllers;

use App\Models\LabModel;
use App\Models\FacultyModel;
use App\Models\DepartmentModel;
use App\Models\UserModel;
use App\Models\ComponentModel;
use App\Models\ComponentCategoryModel;
use App\Models\ComponentModelModel;
use App\Models\ComponentSubCategoryModel;
use App\Models\BorrowingModel;
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
    /*
    public function view($id)
    {
        $labModel = new LabModel();
        $componentModel = new ComponentModel();
        $categoryModel = new ComponentCategoryModel();
        $modelModel = new ComponentModelModel();
        $borrowingModel = new BorrowingModel();
        $subCategoryModel = new ComponentSubCategoryModel();

        $lab = $labModel->find($id);
        $components = $componentModel->where('lab_id', $id)->findAll();
        $categories = $categoryModel->findAll();
        $models = $modelModel->findAll();

        $isLabManager = session()->get('id') == $lab['manager_id'];

        // NEW: Get current user's pending requests
        $userId = session()->get('id');
        $userRequests = [];
        foreach ($components as $component) {
            $hasRequest = $borrowingModel->where('component_id', $component['id'])
                ->where('user_id', $userId)
                ->where('status', 'requested')
                ->countAllResults() > 0;
            $userRequests[$component['id']] = $hasRequest;
            $subcategory = $subCategoryModel->find($component['subcategory_id']);
            $model       = $modelModel->find($component['model_id']);

            $component['subcategory_name'] = $subcategory ? $subcategory['name'] : null;
            $component['model_name']       = $model ? $model['name'] : null;
        }

        return view('labs/view', [
            'lab' => $lab,
            'components' => $components,
            'isLabManager' => $isLabManager,
            'categories' => $categories,
            'models' => $models,
            'activeTab' => 'inventory',
            'userRequests' => $userRequests,
            'borrowingModel' => $borrowingModel,
        ]);
    } */

    /*

    public function view($id)
    {
        $labModel          = new LabModel();
        $componentModel    = new ComponentModel();
        $categoryModel     = new ComponentCategoryModel();
        $modelModel        = new ComponentModelModel();
        $subCategoryModel  = new ComponentSubCategoryModel();
        $borrowingModel    = new BorrowingModel();

        $allSubcategories = $subCategoryModel->findAll();
        $groupedSubcategories = [];

        foreach ($allSubcategories as $sub) {
            $groupedSubcategories[$sub['category_id']][] = $sub;
        }

        // 1) Get the lab and its components
        $lab = $labModel->find($id);
        $components = $componentModel->where('lab_id', $id)->findAll();

        // 2) Get all categories and models (for dropdowns, etc.)
        $categories = $categoryModel->findAll();
        $models     = $modelModel->findAll();

        // 3) Check if current user is the lab manager
        $isLabManager = (session()->get('id') == $lab['manager_id']);

        // 4) Track current user's pending requests
        $userId = session()->get('id');
        $userRequests = [];

        // IMPORTANT: loop by reference (&$component) so we can modify each array item
        foreach ($components as &$component) {
            if ($component['borrow_status'] === 'requested') {
                $borrowing = $borrowingModel
                    ->where('component_id', $component['id'])
                    ->where('status', 'requested')
                    ->orderBy('borrow_date', 'desc')
                    ->first();
                if ($borrowing) {
                    $userModel = new UserModel();
                    $user = $userModel->find($borrowing['user_id']);
                    $component['requested_by'] = $user ? $user['username'] : 'Unknown';
                }
            }
            // (Attach other details like subcategory_name and model_name as before)
            $subcategory = $subCategoryModel->find($component['subcategory_id']);
            $model       = $modelModel->find($component['model_id']);
            $component['subcategory_name'] = $subcategory ? $subcategory['name'] : null;
            $component['model_name']       = $model ? $model['name'] : null;
        }
        // After modifying by reference, unset the reference to avoid pitfalls
        unset($component);

        // 5) Pass everything to the same labs/view.php
        return view('labs/view', [
            'lab'            => $lab,
            'components'     => $components,
            'categories'     => $categories,
            'models'         => $models,
            'isLabManager'   => $isLabManager,
            'userRequests'   => $userRequests,
            'borrowingModel' => $borrowingModel,
            'activeTab'      => 'inventory', // or whichever tab you want active
            'groupedSubcategories' => $groupedSubcategories,
        ]);
    }
    */

    public function view($id)
    {
        $labModel          = new LabModel();
        $componentModel    = new ComponentModel();
        $categoryModel     = new ComponentCategoryModel();
        $modelModel        = new ComponentModelModel();
        $subCategoryModel  = new ComponentSubCategoryModel();
        $borrowingModel    = new BorrowingModel();

        // Group subcategories (for filtering later)
        $allSubcategories = $subCategoryModel->findAll();
        $groupedSubcategories = [];
        foreach ($allSubcategories as $sub) {
            $groupedSubcategories[$sub['category_id']][] = $sub;
        }

        // 1) Get the lab and its components
        $lab = $labModel->find($id);
        $components = $componentModel->where('lab_id', $id)->findAll();

        // 2) Get all categories and models (for dropdowns, etc.)
        $categories = $categoryModel->findAll();
        $models     = $modelModel->findAll();

        // 3) Check if current user is the lab manager
        $isLabManager = (session()->get('id') == $lab['manager_id']);

        // 4) Process each component
        $userId = session()->get('id');
        foreach ($components as &$component) {
            // If the component is requested for borrowing, attach the requester info.
            if ($component['borrow_status'] === 'requested') {
                $borrowing = $borrowingModel
                    ->where('component_id', $component['id'])
                    ->where('status', 'requested')
                    ->orderBy('borrow_date', 'desc')
                    ->first();
                if ($borrowing) {
                    $userModel = new UserModel();
                    $user = $userModel->find($borrowing['user_id']);
                    $component['requested_by'] = $user ? $user['username'] : 'Unknown';
                }
            }

            // Attach subcategory and model names
            $subcategory = $subCategoryModel->find($component['subcategory_id']);
            $model       = $modelModel->find($component['model_id']);
            $component['subcategory_name'] = $subcategory ? $subcategory['name'] : null;
            $component['model_name']       = $model ? $model['name'] : null;

            // Initialize a priority flag (only for lab manager)
            $component['priority'] = 0;
            if ($isLabManager) {
                if ($component['borrow_status'] === 'requested') {
                    // Highest priority for borrow requests
                    $component['priority'] = 2;
                } else {
                    // Check for pending return request
                    $pendingReturn = $borrowingModel->where('component_id', $component['id'])
                        ->where('return_status', 'pending')
                        ->first();
                    if ($pendingReturn) {
                        $component['priority'] = 1;
                    }
                }
            }
        }
        unset($component);

        // 5) For lab managers, sort components so that high-priority ones appear at the top.
        if ($isLabManager) {
            usort($components, function ($a, $b) {
                return $b['priority'] - $a['priority'];
            });
        }

        // 6) Return the view with all data
        return view('labs/view', [
            'lab'                   => $lab,
            'components'            => $components,
            'categories'            => $categories,
            'models'                => $models,
            'isLabManager'          => $isLabManager,
            'userRequests'          => [], // if needed
            'borrowingModel'        => $borrowingModel,
            'activeTab'             => 'inventory',
            'groupedSubcategories'  => $groupedSubcategories,
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
        $borrowModel = new BorrowingModel();

        $borrowings = $borrowModel
            ->select('borrowings.*, components.name as component_name, users.name as user_name')
            ->join('components', 'components.id = borrowings.component_id')
            ->join('users', 'users.id = borrowings.user_id')
            ->where('components.lab_id', $id)
            ->findAll();

        return view('labs/borrowing-log', [
            'borrowings' => $borrowings,
        ]);
    }





    //working add component before the qr change.
    /*
    public function addComponent($labId)
    {

        log_message('debug', 'Form data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'File data: ' . json_encode($this->request->getFiles()));

        $componentModel = new ComponentModel();
        $categoryModel = new ComponentCategoryModel();
        $modelModel = new ComponentModelModel();

        $validationRules = [
            'name' => 'required',
            'date_bought' => 'required|valid_date',
            'value' => 'required|decimal',
            'state' => 'required|in_list[new,used,damaged]',
            'photo' => 'uploaded[photo]|is_image[photo]|max_size[photo,2048]',
            'component_code' => 'required|is_unique[components.component_code]',
            'warranty_end_date' => 'valid_date',
            'funds_from' => 'required',
            'category_id' => 'required|integer',
            'subcategory_id' => 'required|integer',
            'model_id' => 'integer',
        ];

        if (!$this->validate($validationRules)) {
            log_message('error', 'Validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        // Handle file upload
        $photo = $this->request->getFile('photo');
        $uniquePhotoName = uniqid() . '.' . $photo->getExtension();
        $photo->move(FCPATH . 'uploads/components', $uniquePhotoName);
        $photoPath = 'uploads/components/' . $uniquePhotoName;



        // Generate QR code
        $qrCode = new QrCode(base_url("components/view/{$labId}"));
        $writer = new PngWriter();
        $uniqueQrCodeName = uniqid() . '.png';
        $qrCodePath = FCPATH . 'uploads/qrcodes/' . $uniqueQrCodeName;
        $writer->write($qrCode)->saveToFile($qrCodePath);



        // Save component to the database
        $data = [
            'name' => $this->request->getPost('name'),
            'date_bought' => $this->request->getPost('date_bought'),
            'value' => $this->request->getPost('value'),
            'lab_id' => $labId,
            'state' => $this->request->getPost('state'),
            'photo' => $photoPath,
            'qr_code' => 'uploads/qrcodes/' . $uniqueQrCodeName,
            'component_code' => $this->request->getPost('component_code'),
            'warranty_end_date' => $this->request->getPost('warranty_end_date'),
            'funds_from' => $this->request->getPost('funds_from'),
            'category_id' => $this->request->getPost('category_id'),
            'subcategory_id' => $this->request->getPost('subcategory_id'),
            'model_id' => $this->request->getPost('model_id'),
        ];
        log_message('debug', 'Data to insert: ' . json_encode($data));



        if (!$componentModel->insert($data)) {
            log_message('error', 'Database insert failed: ' . json_encode($componentModel->errors()));
            return redirect()->back()->with('error', 'Failed to save the component.');
        }




        return redirect()->back()->with('success', 'Component added successfully!');
    }

    */
    public function addComponent($labId)
    {
        log_message('debug', 'Form data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'File data: ' . json_encode($this->request->getFiles()));

        $componentModel = new ComponentModel();
        $categoryModel = new ComponentCategoryModel();
        $modelModel = new ComponentModelModel();

        $validationRules = [
            'name'             => 'required',
            'date_bought'      => 'required|valid_date',
            'value'            => 'required|decimal',
            'state'            => 'required|in_list[new,used,damaged]',
            'photo'            => 'uploaded[photo]|is_image[photo]|max_size[photo,2048]',
            'component_code'   => 'required|is_unique[components.component_code]',
            'warranty_end_date' => 'valid_date',
            'funds_from'       => 'required',
            'category_id'      => 'required|integer',
            'subcategory_id'   => 'required|integer',
            'model_id'         => 'integer',
        ];

        if (!$this->validate($validationRules)) {
            log_message('error', 'Validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        // Handle file upload for the photo
        $photo = $this->request->getFile('photo');
        $uniquePhotoName = uniqid() . '.' . $photo->getExtension();
        $photo->move(FCPATH . 'uploads/components', $uniquePhotoName);
        $photoPath = 'uploads/components/' . $uniquePhotoName;

        // Insert the component first (without the QR code)
        $data = [
            'name'             => $this->request->getPost('name'),
            'date_bought'      => $this->request->getPost('date_bought'),
            'value'            => $this->request->getPost('value'),
            'lab_id'           => $labId,
            'state'            => $this->request->getPost('state'),
            'photo'            => $photoPath,
            'component_code'   => $this->request->getPost('component_code'),
            'warranty_end_date' => $this->request->getPost('warranty_end_date'),
            'funds_from'       => $this->request->getPost('funds_from'),
            'category_id'      => $this->request->getPost('category_id'),
            'subcategory_id'   => $this->request->getPost('subcategory_id'),
            'model_id'         => $this->request->getPost('model_id'),
        ];

        log_message('debug', 'Data to insert: ' . json_encode($data));

        if (!$componentModel->insert($data)) {
            log_message('error', 'Database insert failed: ' . json_encode($componentModel->errors()));
            return redirect()->back()->with('error', 'Failed to save the component.');
        }

        // Retrieve the new component's ID
        $componentId = $componentModel->getInsertID();

        // Generate the full URL for the component profile page.
        // Make sure base_url() returns your full domain (e.g. https://example.com)
        $componentUrl = base_url("components/{$componentId}");

        // Generate QR code with the URL using Endroid QrCode
        $qrCode = new QrCode($componentUrl);
        $writer = new PngWriter();
        $uniqueQrCodeName = uniqid() . '.png';
        $qrCodePathFull = FCPATH . 'uploads/qrcodes/' . $uniqueQrCodeName;
        $writer->write($qrCode)->saveToFile($qrCodePathFull);

        // Update the component record with the QR code relative path
        $componentModel->update($componentId, [
            'qr_code' => 'uploads/qrcodes/' . $uniqueQrCodeName,
        ]);

        return redirect()->back()->with('success', 'Component added successfully!');
    }


    public function getSubcategoriesByCategory($categoryId)
    {
        $subcategoryModel = new ComponentSubCategoryModel();
        $subcategories = $subcategoryModel->where('category_id', $categoryId)->findAll();

        return $this->response->setJSON($subcategories);
    }


    public function getModelsBySubCategory($subcategoryId)
    {
        $modelModel = new ComponentModelModel();
        $models = $modelModel->getBySubCategory($subcategoryId);

        return $this->response->setJSON($models);
    }

    public function addModel()
    {
        $modelModel = new ComponentModelModel();

        // Validate the request
        $validationRules = [
            'name' => 'required|is_unique[component_models.name]',
            'subcategory_id' => 'required|integer',
        ];

        if (!$this->validate($validationRules)) {
            return $this->response->setJSON(['error' => $this->validator->getErrors()]);
        }

        // Save the new model
        $modelData = [
            'name' => $this->request->getJSON()->name,
            'subcategory_id' => $this->request->getJSON()->subcategory_id,
        ];

        $modelModel->insert($modelData);

        return $this->response->setJSON([
            'success' => true,
            'model_id' => $modelModel->getInsertID(), // Return the ID of the new model
        ]);
    }

    public function editComponent($id)
    {
        $componentModel   = new \App\Models\ComponentModel();
        $categoryModel    = new \App\Models\ComponentCategoryModel();
        $subcategoryModel = new \App\Models\ComponentSubCategoryModel();
        $modelModel       = new \App\Models\ComponentModelModel();

        $component = $componentModel->find($id);
        if (!$component) {
            return redirect()->back()->with('error', 'Component not found.');
        }

        // Get data for dropdowns
        $categories    = $categoryModel->findAll();
        $subcategories = $subcategoryModel->findAll();
        $models        = $modelModel->findAll();

        return view('components/edit', [
            'component'    => $component,
            'categories'   => $categories,
            'subcategories' => $subcategories,
            'models'       => $models,
        ]);
    }

    public function updateComponent($id)
    {
        $componentModel = new \App\Models\ComponentModel();
        $component      = $componentModel->find($id);
        if (!$component) {
            return redirect()->back()->with('error', 'Component not found.');
        }

        // Retrieve posted data
        $data = [
            'name'             => $this->request->getPost('name'),
            'date_bought'      => $this->request->getPost('date_bought'),
            'value'            => $this->request->getPost('value'),
            'state'            => $this->request->getPost('state'),
            'component_code'   => $this->request->getPost('component_code'),
            'warranty_end_date' => $this->request->getPost('warranty_end_date'),
            'funds_from'       => $this->request->getPost('funds_from'),
            'category_id'      => $this->request->getPost('category_id'),
            'subcategory_id'   => $this->request->getPost('subcategory_id'),
            'model_id'         => $this->request->getPost('model_id'),
        ];

        // Check if a new image file is uploaded
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            // Generate a unique file name
            $uniquePhotoName = uniqid() . '.' . $photo->getExtension();
            $photo->move(FCPATH . 'uploads/components', $uniquePhotoName);
            $data['photo'] = 'uploads/components/' . $uniquePhotoName;

            // Optionally, remove the old photo from the file system if it exists
            if (!empty($component['photo']) && file_exists(FCPATH . $component['photo'])) {
                @unlink(FCPATH . $component['photo']);
            }
        }

        if (!$componentModel->update($id, $data)) {
            return redirect()->back()->with('error', 'Failed to update component.');
        }

        return redirect()->to("/components/{$id}")
            ->with('success', 'Component updated successfully.');
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

    public function borrow($componentId)
    {
        $componentModel = new \App\Models\ComponentModel();
        $borrowingModel = new \App\Models\BorrowingModel();

        // Check if the component exists and is available
        $component = $componentModel->find($componentId);

        if (!$component) {
            log_message('error', "Component with ID {$componentId} not found.");
            return $this->response->setJSON(['error' => 'Component not found.']);
        }

        if ($component['borrow_status'] !== 'available') {
            log_message('error', "Component with ID {$componentId} is not available for borrowing.");
            return $this->response->setJSON(['error' => 'Component is not available for borrowing.']);
        }

        // Update borrow_status to "requested"
        $updateData = ['borrow_status' => 'requested'];

        if (!$componentModel->update($componentId, $updateData)) {
            log_message('error', 'Failed to update borrow_status for component ID: ' . $componentId);
            return $this->response->setJSON(['error' => 'Failed to update component status.']);
        }

        // Create a borrowing record
        $borrowingData = [
            'component_id' => $componentId,
            'user_id' => session()->get('id'), // Assuming user ID is stored in the session
            'status' => 'requested',
            'borrow_date' => date('Y-m-d H:i:s')
        ];

        if (!$borrowingModel->insert($borrowingData)) {
            log_message('error', 'Failed to create borrowing record: ' . json_encode($borrowingData));
            return $this->response->setJSON(['error' => 'Failed to record borrowing request.']);
        }

        return $this->response->setJSON(['success' => 'Borrow request submitted successfully.']);
    }

    public function approveRequest($componentId)
    {
        $componentModel = new \App\Models\ComponentModel();
        $borrowingModel = new \App\Models\BorrowingModel();

        // Find the component
        $component = $componentModel->find($componentId);
        if (!$component) {
            return $this->response->setJSON(['error' => 'Component not found.']);
        }

        // Update component status
        $componentModel->update($componentId, ['borrow_status' => 'borrowed']);

        // Update the borrowing record
        $borrowing = $borrowingModel->where('component_id', $componentId)
            ->where('status', 'requested')
            ->orderBy('borrow_date', 'desc')
            ->first();

        if ($borrowing) {
            $borrowingModel->update($borrowing['id'], [
                'status' => 'approved',
                'approved_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON([
            'success' => 'Request approved successfully.'
        ]);
    }

    public function cancelRequest($componentId)
    {
        $componentModel = new \App\Models\ComponentModel();
        $borrowingModel = new \App\Models\BorrowingModel();
        $userId = session()->get('id');

        // Find the component
        $component = $componentModel->find($componentId);

        if (!$component) {
            return $this->response->setJSON(['error' => 'Component not found.']);
        }

        if ($component['borrow_status'] !== 'requested') {
            return $this->response->setJSON(['error' => 'No request to cancel for this component.']);
        }

        // Find the user's specific borrowing request
        $borrowing = $borrowingModel
            ->where('component_id', $componentId)
            ->where('user_id', $userId)
            ->where('status', 'requested')
            ->first();

        if (!$borrowing) {
            return $this->response->setJSON(['error' => 'No request found to cancel.']);
        }

        // Update component status
        $componentModel->update($componentId, ['borrow_status' => 'available']);

        // Update borrowing record
        $borrowingModel->update($borrowing['id'], ['status' => 'canceled']);

        return $this->response->setJSON(['success' => 'Request canceled successfully.']);
    }

    public function declineRequest($componentId)
    {
        $componentModel = new \App\Models\ComponentModel();
        $borrowingModel = new \App\Models\BorrowingModel();

        // Find the component
        $component = $componentModel->find($componentId);

        if (!$component) {
            return $this->response->setJSON(['error' => 'Component not found.']);
        }

        if ($component['borrow_status'] !== 'requested') {
            return $this->response->setJSON(['error' => 'No request to decline for this component.']);
        }

        // Update component borrow_status to "available"
        $componentModel->update($componentId, ['borrow_status' => 'available']);

        // Update borrowing record to mark it as declined
        $borrowing = $borrowingModel->where('component_id', $componentId)
            ->where('status', 'requested')
            ->orderBy('borrow_date', 'desc')
            ->first();

        if ($borrowing) {
            $borrowingModel->update($borrowing['id'], ['status' => 'declined']);
        }

        return $this->response->setJSON(['success' => 'Request declined successfully.']);
    }

    public function requestReturn($componentId)
    {
        $componentModel = new \App\Models\ComponentModel();
        $borrowingModel = new \App\Models\BorrowingModel();

        // 1) Check if component is borrowed
        $component = $componentModel->find($componentId);
        if (!$component || $component['borrow_status'] !== 'borrowed') {
            return $this->response->setJSON(['error' => 'Component not borrowed.']);
        }

        // 2) Check if current user is the borrower
        $userId = session()->get('id');
        $borrowing = $borrowingModel
            ->where('component_id', $componentId)
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->first();

        if (!$borrowing) {
            return $this->response->setJSON(['error' => 'You are not the borrower of this component.']);
        }

        // 3) Update borrowing record to mark return as pending
        //    Make sure 'return_status' is in allowedFields and in the DB table
        $borrowingModel->update($borrowing['id'], [
            'return_status' => 'pending'
        ]);

        return $this->response->setJSON(['success' => 'Return request submitted successfully.']);
    }

    public function acceptReturn($componentId)
    {
        $componentModel = new \App\Models\ComponentModel();
        $borrowingModel = new \App\Models\BorrowingModel();

        // Find the component
        $component = $componentModel->find($componentId);
        if (!$component || $component['borrow_status'] !== 'borrowed') {
            return $this->response->setJSON(['error' => 'Component not borrowed.']);
        }

        // Find the pending return request
        $borrowing = $borrowingModel->where('component_id', $componentId)
            ->where('return_status', 'pending')
            ->first();

        if (!$borrowing) {
            return $this->response->setJSON(['error' => 'No pending return request for this component.']);
        }

        // Update component status to available
        $componentModel->update($componentId, ['borrow_status' => 'available']);

        // Update borrowing record to mark return as accepted
        $borrowingModel->update($borrowing['id'], [
            'return_status' => 'accepted',
            'return_date' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['success' => 'Return accepted successfully.']);
    }

    public function declineReturn($componentId)
    {
        $borrowingModel = new \App\Models\BorrowingModel();

        // Find the pending return request
        $borrowing = $borrowingModel->where('component_id', $componentId)
            ->where('return_status', 'pending')
            ->first();

        if (!$borrowing) {
            return $this->response->setJSON(['error' => 'No pending return request for this component.']);
        }

        // Update borrowing record to mark return as declined
        $borrowingModel->update($borrowing['id'], [
            'return_status' => 'declined'
        ]);

        return $this->response->setJSON(['success' => 'Return declined.']);
    }
    public function filterComponents($labId)
    {
        $componentModel = new ComponentModel();
        $request = $this->request->getJSON();

        $categoryId = $request->category_id;
        $subcategoryId = $request->subcategory_id;

        $query = $componentModel
            ->where('lab_id', $labId)
            ->orderBy('name', 'ASC');

        if ($categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        if ($subcategoryId !== 'all') {
            $query->where('subcategory_id', $subcategoryId);
        }

        $components = $query->findAll();

        // Instantiate needed models
        $subCategoryModel = new ComponentSubCategoryModel();
        $modelModel = new ComponentModelModel();
        $borrowingModel = new BorrowingModel();
        $labModel = new LabModel();

        // Get lab info and determine if the current user is a lab manager
        $lab = $labModel->find($labId);
        $isLabManager = (session()->get('id') == $lab['manager_id']);
        $userId = session()->get('id');

        // Prepare a userRequests array and attach extra flags to each component
        $userRequests = [];

        foreach ($components as &$component) {
            // Attach subcategory name and model name
            $subcategory = $subCategoryModel->find($component['subcategory_id']);
            $model = $modelModel->find($component['model_id']);
            $component['subcategory_name'] = $subcategory ? $subcategory['name'] : null;
            $component['model_name'] = $model ? $model['name'] : null;

            // Check if the current user has an active borrow request for this component
            $hasRequest = $borrowingModel
                ->where('component_id', $component['id'])
                ->where('user_id', $userId)
                ->where('status', 'requested')
                ->countAllResults() > 0;
            $userRequests[$component['id']] = $hasRequest;

            // Check if the current user is the approved borrower
            $component['isBorrower'] = $borrowingModel
                ->where('component_id', $component['id'])
                ->where('user_id', $userId)
                ->where('status', 'approved')
                ->countAllResults() > 0;

            // Check if there is a pending return request for this component
            $hasPendingReturn = $borrowingModel
                ->where('component_id', $component['id'])
                ->where('return_status', 'pending')
                ->countAllResults() > 0;
            $component['hasPendingReturn'] = $hasPendingReturn;

            // Pass along the lab manager flag (for convenience)
            $component['isLabManager'] = $isLabManager;
        }
        unset($component); // Remove reference

        // Render the partial view for the table body
        $html = view('labs/partials/components_table', [
            'components'   => $components,
            'isLabManager' => $isLabManager,
            'userRequests' => $userRequests,
        ]);

        // Return the HTML as JSON
        return $this->response->setJSON(['html' => $html]);
    }

    public function bulkUploadForm($labId)
    {
        $labModel = new LabModel();
        $lab = $labModel->find($labId);
        return view('labs/bulk_upload', ['lab' => $lab]);
    }

    public function bulkUploadProcess($labId)
    {
        // Get the uploaded file
        $file = $this->request->getFile('csv_file');
        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'Invalid file upload.');
        }
        if (strtolower($file->getExtension()) !== 'csv') {
            return redirect()->back()->with('error', 'Please upload a valid CSV file.');
        }

        // Open the CSV file
        if (($handle = fopen($file->getTempName(), 'r')) === false) {
            return redirect()->back()->with('error', 'Could not open CSV file.');
        }

        // Read the header row to get column names
        $header = fgetcsv($handle, 1000, ",");
        // Expected columns (order does not matter as long as headers match)
        $expectedColumns = [
            'name',
            'date_bought',
            'value',
            'state',
            'component_code',
            'warranty_end_date',
            'funds_from',
            'category_id',
            'subcategory_id',
            'model_id'
        ];

        // (Optional) Check if the CSV header contains the expected columns.
        // You can uncomment the following block to enforce this.
        /*
    foreach ($expectedColumns as $column) {
        if (!in_array($column, $header)) {
            fclose($handle);
            return redirect()->back()->with('error', "CSV is missing required column: $column");
        }
    }
    */

        $componentModel = new ComponentModel();
        $writer = new PngWriter();
        $insertedCount = 0;
        $errors = [];

        // Process each row in the CSV file
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            // Combine header and row to form an associative array
            $row = array_combine($header, $data);
            if (!$row) {
                $errors[] = "Error reading a row.";
                continue;
            }

            // Prepare the data array for insertion.
            // Note: 'photo' is left empty; you can update it later.
            // 'borrow_status' is set to 'available' by default.
            $componentData = [
                'name'             => $row['name'],
                'date_bought'      => $row['date_bought'],
                'value'            => $row['value'],
                'state'            => $row['state'],
                'component_code'   => $row['component_code'],
                'warranty_end_date' => $row['warranty_end_date'],
                'funds_from'       => $row['funds_from'],
                'lab_id'           => $labId,
                'category_id'      => $row['category_id'],
                'subcategory_id'   => $row['subcategory_id'],
                'model_id'         => $row['model_id'],
                'borrow_status'    => 'available',
                'photo'            => '',   // Photo will be added later
                'qr_code'          => ''    // Will be updated after insertion
            ];

            // Insert the component into the database
            if (!$componentModel->insert($componentData)) {
                $errors[] = "Failed to insert component with code: " . $row['component_code'];
                continue;
            }
            $componentId = $componentModel->getInsertID();

            // Generate the component profile URL (adjust base_url as needed)
            $componentUrl = base_url("components/{$componentId}");

            // Generate QR code using Endroid QrCode
            $qrCode = new QrCode($componentUrl);
            // (Optional) Customize your QR code here (size, margin, colors, etc.)
            $uniqueQrCodeName = uniqid() . '.png';
            $qrCodePathFull = FCPATH . 'uploads/qrcodes/' . $uniqueQrCodeName;
            $writer->write($qrCode)->saveToFile($qrCodePathFull);

            // Update the component record with the generated QR code path
            $componentModel->update($componentId, [
                'qr_code' => 'uploads/qrcodes/' . $uniqueQrCodeName,
            ]);

            $insertedCount++;
        }
        fclose($handle);

        if (!empty($errors)) {
            // If there were errors, you can log them or show a summary.
            return redirect()->to("/labs/view/{$labId}/inventory")
                ->with('error', "Bulk upload completed with errors: " . implode(", ", $errors));
        }
        return redirect()->to("/labs/view/{$labId}/inventory")
            ->with('success', "Successfully uploaded {$insertedCount} components.");
    }

    public function addToCart($componentId)
    {
        $session = session();
        $cart = $session->get('borrow_cart') ?? [];
        if (!in_array($componentId, $cart)) {
            $cart[] = $componentId;
            $session->set('borrow_cart', $cart);
        }
        return $this->response->setJSON(['success' => true, 'message' => 'Item added to cart.']);
    }

    public function viewCart()
    {
        $session = session();
        $cart = $session->get('borrow_cart') ?? [];
        $componentModel = new ComponentModel();
        $components = [];
        if (!empty($cart)) {
            $components = $componentModel->whereIn('id', $cart)->findAll();
        }
        return view('cart/view', ['components' => $components]);
    }

    public function requestCart()
    {
        $session = session();
        $cart = $session->get('borrow_cart') ?? [];
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }
        $componentModel = new ComponentModel();
        $borrowingModel = new BorrowingModel();
        $userId = $session->get('id'); // Assuming the logged-in user ID is stored in session

        foreach ($cart as $componentId) {
            $component = $componentModel->find($componentId);
            if ($component && $component['borrow_status'] === 'available') {
                $componentModel->update($componentId, ['borrow_status' => 'requested']);
                $borrowingData = [
                    'component_id' => $componentId,
                    'user_id'      => $userId,
                    'status'       => 'requested',
                    'borrow_date'  => date('Y-m-d H:i:s')
                ];
                $borrowingModel->insert($borrowingData);
            }
        }
        $session->remove('borrow_cart');
        return redirect()->back()->with('success', 'Borrow request submitted for all items in your cart.');
    }

    public function clearCart()
    {
        $session = session();
        $session->remove('borrow_cart');
        return $this->response->setJSON(['success' => true]);
    }

    public function analytics($id)
    {
        $labModel = new \App\Models\LabModel();
        $componentModel = new \App\Models\ComponentModel();
        $userModel = new \App\Models\UserModel();

        $lab = $labModel->find($id);
        if (!$lab) {
            return redirect()->to('/labs')->with('error', 'Lab not found.');
        }

        // Get lab manager details using the manager_id from the lab record.
        $manager = $userModel->find($lab['manager_id']);

        // Get all components for this lab.
        $components = $componentModel->where('lab_id', $id)->findAll();

        // Initialize counts for component states.
        $stateCounts = [
            'new'     => 0,
            'used'    => 0,
            'damaged' => 0,
        ];

        // Initialize counts for borrow status.
        $borrowStatusCounts = [
            'available' => 0,
            'requested' => 0,
            'borrowed'  => 0,
        ];

        // Count items with expired warranties.
        $expiredWarranty = 0;
        $today = date('Y-m-d');

        foreach ($components as $component) {
            if (isset($stateCounts[$component['state']])) {
                $stateCounts[$component['state']]++;
            }
            if (isset($borrowStatusCounts[$component['borrow_status']])) {
                $borrowStatusCounts[$component['borrow_status']]++;
            }
            if (!empty($component['warranty_end_date']) && $component['warranty_end_date'] < $today) {
                $expiredWarranty++;
            }
        }

        return view('labs/analytics', [
            'lab'                => $lab,
            'manager'            => $manager,
            'stateCounts'        => $stateCounts,
            'borrowStatusCounts' => $borrowStatusCounts,
            'expiredWarranty'    => $expiredWarranty
        ]);
    }
}
