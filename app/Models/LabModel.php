<?php

namespace App\Models;

use CodeIgniter\Model;

class LabModel extends Model
{
    protected $table = 'labs';          // The name of the database table
    protected $primaryKey = 'id';      // The primary key of the table

    // Fields that can be inserted or updated via the model
    protected $allowedFields = [
        'name',
        'faculty_id',
        'department_id',
        'manager_id',
        'created_at',
        'updated_at'
    ];

    // Use timestamps for created_at and updated_at fields
    protected $useTimestamps = true;

    // Validation rules (optional)
    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'faculty_id' => 'required|integer',
        'department_id' => 'required|integer'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Lab name is required.',
            'max_length' => 'Lab name cannot exceed 255 characters.'
        ],
        'faculty_id' => [
            'required' => 'Faculty is required.',
            'integer' => 'Invalid faculty ID.'
        ],
        'department_id' => [
            'required' => 'Department is required.',
            'integer' => 'Invalid department ID.'
        ]
    ];

    protected $skipValidation = false;
}
