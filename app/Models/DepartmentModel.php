<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table = 'departments';   // Table name
    protected $primaryKey = 'id';      // Primary key column

    // Fields that can be inserted or updated
    protected $allowedFields = [
        'name',
        'faculty_id',
        'created_at',
        'updated_at'
    ];

    // Enable automatic timestamps
    protected $useTimestamps = true;

    // Validation rules (optional)
    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'faculty_id' => 'required|integer'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Department name is required.',
            'max_length' => 'Department name cannot exceed 255 characters.'
        ],
        'faculty_id' => [
            'required' => 'Faculty ID is required.',
            'integer' => 'Faculty ID must be a valid integer.'
        ]
    ];

    protected $skipValidation = false;
}
