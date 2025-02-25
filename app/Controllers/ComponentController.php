<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ComponentModel;
use App\Models\ComponentCategoryModel;
use App\Models\ComponentSubCategoryModel;
use App\Models\ComponentModelModel;
use App\Models\LabModel;
use App\Models\BorrowingModel;
use App\Models\UserModel;

class ComponentController extends BaseController
{
    public function show($componentId)
    {
        // 1. Load models
        $componentModel   = new ComponentModel();
        $categoryModel    = new ComponentCategoryModel();
        $subCategoryModel = new ComponentSubCategoryModel();
        $modelModel       = new ComponentModelModel();
        $borrowingModel   = new BorrowingModel();
        $labModel         = new LabModel();
        $userModel        = new UserModel();

        // 2. Fetch the component
        $component = $componentModel->find($componentId);
        if (!$component) {
            return redirect()->back()->with('error', 'Component not found.');
        }

        // 3. Fetch related details: category, subcategory, model, lab
        $category    = $categoryModel->find($component['category_id'] ?? 0);
        $subcategory = $subCategoryModel->find($component['subcategory_id'] ?? 0);
        $model       = $modelModel->find($component['model_id'] ?? 0);
        $lab         = $labModel->find($component['lab_id'] ?? 0);

        // 4. Calculate warranty period left (if applicable)
        $warrantyLeft = null;
        if (!empty($component['warranty_end_date'])) {
            $warrantyEnd = new \DateTime($component['warranty_end_date']);
            $now         = new \DateTime();
            $interval    = $now->diff($warrantyEnd);
            $warrantyLeft = ($warrantyEnd > $now) ? $interval->days . ' days left' : 'Expired';
        }

        // 5. Determine user roles and requests
        $currentUserId = session()->get('id');
        $isLabManager  = ($lab && $currentUserId == $lab['manager_id']);

        // Check if the current user has an approved borrow record (i.e. they are the borrower)
        $isBorrower = $borrowingModel->where('component_id', $componentId)
            ->where('user_id', $currentUserId)
            ->where('status', 'approved')
            ->countAllResults() > 0;

        // Check if the current user has a pending request (status 'requested')
        $hasPendingRequest = $borrowingModel->where('component_id', $componentId)
            ->where('user_id', $currentUserId)
            ->where('status', 'requested')
            ->countAllResults() > 0;

        // 6. Get additional borrowing details:
        //    If the component is borrowed, retrieve the approved record and fetch the borrower.
        //    If the component is requested, retrieve the request record and fetch the requester.
        $borrowedBy = null;
        $requestedBy = null;
        if ($component['borrow_status'] === 'borrowed') {
            $borrowing = $borrowingModel->where('component_id', $componentId)
                ->where('status', 'approved')
                ->orderBy('borrow_date', 'desc')
                ->first();
            if ($borrowing) {
                $borrowedBy = $userModel->find($borrowing['user_id']);
            }
        } elseif ($component['borrow_status'] === 'requested') {
            $borrowing = $borrowingModel->where('component_id', $componentId)
                ->where('status', 'requested')
                ->orderBy('borrow_date', 'desc')
                ->first();
            if ($borrowing) {
                $requestedBy = $userModel->find($borrowing['user_id']);
            }
        }

        // 7. Check if there is a pending return request for this component
        $pendingReturn = $borrowingModel->where('component_id', $componentId)
            ->where('return_status', 'pending')
            ->first();
        $hasPendingReturn = !empty($pendingReturn);

        // 8. Pass all data to the view
        $data = [
            'component'         => $component,
            'categoryName'      => $category['name'] ?? '',
            'subcategoryName'   => $subcategory['name'] ?? '',
            'modelName'         => $model['name'] ?? '',
            'lab'               => $lab,
            'warrantyLeft'      => $warrantyLeft,
            'isLabManager'      => $isLabManager,
            'isBorrower'        => $isBorrower,
            'hasPendingRequest' => $hasPendingRequest,
            'hasPendingReturn'  => $hasPendingReturn,
            'borrowedBy'        => $borrowedBy,
            'requestedBy'       => $requestedBy,
        ];

        return view('components/show', $data);
    }


    public function index()
    {
        // Connect to the database
        $db = \Config\Database::connect();

        // Retrieve the search query from the GET parameters
        $q = $this->request->getGet('q');

        // Determine the current page from the GET parameters (default page 1)
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 12; // Adjust this to control how many cards per page (e.g., 12 cards)

        // Build a query to search for components along with related fields:
        // Join labs (to get lab name), subcategories, and models.
        $builder = $db->table('components');
        $builder->select(
            'components.*, 
             component_sub_categories.name as subcategory_name, 
             component_models.name as model_name,
             labs.name as lab_name'
        );
        // Use the actual table names:
        $builder->join('component_sub_categories', 'component_sub_categories.id = components.subcategory_id', 'left');
        $builder->join('component_models', 'component_models.id = components.model_id', 'left');
        $builder->join('labs', 'labs.id = components.lab_id', 'left');

        // If there is a search query, add conditions to search across multiple columns.
        if (!empty($q)) {
            $builder->groupStart()
                ->like('components.name', $q)
                ->orLike('components.component_code', $q)
                ->orLike('component_sub_categories.name', $q)
                ->orLike('component_models.name', $q)
                ->orLike('labs.name', $q)
                ->groupEnd();
        }

        // Use pagination. This returns only the desired page of results.
        // Execute the query and get the results
        $components = $builder->get()->getResultArray();

        // Load the pagination library
        $pager = \Config\Services::pager();

        // Paginate the results manually
        $total = count($components);
        $components = array_slice($components, ($page - 1) * $perPage, $perPage);
        $pager->makeLinks($page, $perPage, $total);

        // Pass the search query and pager to the view
        return view('components/index', [
            'components' => $components,
            'q'          => $q,
            'pager'      => $pager,
        ]);
    }
}
