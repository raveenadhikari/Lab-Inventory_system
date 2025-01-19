<?php

namespace App\Models;

use CodeIgniter\Model;

class FacultyModel extends Model
{
    protected $table = 'faculties';     // The name of the database table
    protected $primaryKey = 'id';      // The primary key of the table

    // Fields that can be inserted or updated via the model
    protected $allowedFields = [
        'name',
        'created_at',
        'updated_at'
    ];

    // Use timestamps for created_at and updated_at fields
    protected $useTimestamps = true;

    // Validation rules (optional)
    protected $validationRules = [
        'name' => 'required|max_length[255]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Faculty name is required.',
            'max_length' => 'Faculty name cannot exceed 255 characters.'
        ]
    ];

    protected $skipValidation = false;
}
