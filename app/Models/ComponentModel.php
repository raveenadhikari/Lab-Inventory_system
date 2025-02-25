<?php

namespace App\Models;

use CodeIgniter\Model;

class ComponentModel extends Model
{
    protected $table = 'components'; // Components table name
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'date_bought',
        'value',
        'state',
        'photo',
        'qr_code',
        'component_code',
        'warranty_end_date',
        'funds_from',
        'lab_id',
        'category_id',
        'subcategory_id',
        'model_id',
        'borrow_status'
    ];

    protected $useTimestamps = true; // Disable automatic timestamps if not used

    /**
     * Fetch components with related category, subcategory, and model details.
     */
    public function getComponentsByLab($labId)
    {
        return $this
            ->select('components.*, component_categories.name AS category_name, component_sub_categories.name AS subcategory_name, component_models.name AS model_name')
            ->join('component_categories', 'component_categories.id = components.category_id', 'left')
            ->join('component_sub_categories', 'component_sub_categories.id = components.subcategory_id', 'left')
            ->join('component_models', 'component_models.id = components.model_id', 'left')
            ->where('components.lab_id', $labId)
            ->findAll();
    }
}
