<?php

namespace App\Controllers;

use App\Models\LabModel;
use App\Models\BorrowingModel;
use App\Models\ComponentModel;
use App\Models\ComponentCategoryModel;
use App\Models\ComponentModelModel;
use App\Models\ComponentSubCategoryModel;

class BorrowingController extends BaseController
{
    public function index($labId)
    {
        $labModel           = new LabModel();
        $componentModel     = new ComponentModel();
        $categoryModel      = new ComponentCategoryModel();
        $modelModel         = new ComponentModelModel();
        $subCategoryModel   = new ComponentSubCategoryModel(); // Used to group subcategories

        // 1) Fetch the lab
        $lab = $labModel->find($labId);

        // 2) Fetch categories, models, etc. for the view
        $categories   = $categoryModel->findAll();
        $models       = $modelModel->findAll();
        $isLabManager = (session()->get('id') == $lab['manager_id']);

        // 3) Fetch all subcategories & group them by category_id
        $allSubcategories = $subCategoryModel->findAll();
        $groupedSubcategories = [];
        foreach ($allSubcategories as $sub) {
            $groupedSubcategories[$sub['category_id']][] = $sub;
        }

        // 4) Fetch the borrowing log for this lab
        // Update the select clause to also retrieve the mobile number.
        $borrowModel = new BorrowingModel();
        $borrowings = $borrowModel
            ->select('borrowings.*, components.name as component_name, users.username as user_name, users.mobile_number as user_mobile')
            ->join('components', 'components.id = borrowings.component_id')
            ->join('users', 'users.id = borrowings.user_id')
            ->where('components.lab_id', $labId)
            ->findAll();

        // 5) Pass data to the labs/view.php with 'borrowing-log' active
        $data = [
            'lab'                  => $lab,
            'borrowings'           => $borrowings,
            'activeTab'            => 'borrowing-log',
            'isLabManager'         => $isLabManager,
            'categories'           => $categories,
            'models'               => $models,
            'groupedSubcategories' => $groupedSubcategories, // ensures it's defined
        ];

        return view('labs/view', $data);
    }
}
