<?php

namespace App\Models;

use CodeIgniter\Model;

class ComponentModelModel extends Model
{
    protected $table = 'component_models'; // Models table name
    protected $primaryKey = 'id';
    protected $allowedFields = ['subcategory_id', 'name'];

    protected $useTimestamps = false;

    /**
     * Fetch models by subcategory ID.
     */
    public function getBySubCategory($subcategoryId)
    {
        return $this->where('subcategory_id', $subcategoryId)->findAll();
    }
}
