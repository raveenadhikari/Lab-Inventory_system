<?php

namespace App\Models;

use CodeIgniter\Model;

class ComponentSubCategoryModel extends Model
{
    protected $table = 'component_sub_categories'; // Subcategories table name
    protected $primaryKey = 'id';
    protected $allowedFields = ['category_id', 'name'];

    protected $useTimestamps = false;

    /**
     * Fetch subcategories by category ID.
     */
    public function getByCategory($categoryId)
    {
        return $this->where('category_id', $categoryId)->findAll();
    }
}
