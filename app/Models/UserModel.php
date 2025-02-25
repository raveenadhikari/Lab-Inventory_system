<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; // Table associated with this model
    protected $primaryKey = 'id'; // Primary key of the table

    // Fields allowed for mass assignment
    protected $allowedFields = [
        'username',
        'email',
        'password_hash',
        'mobile_number',    // NEW: Mobile number field added
        'faculty_id',
        'department_id',
        'role_id',
    ];

    // Automatically manage timestamps
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Soft delete support
    protected $useSoftDeletes = false;

    // Validation rules for this model
    protected $validationRules = [
        'username'      => 'required|min_length[3]|max_length[50]',
        'email'         => 'required|valid_email|max_length[100]',
        'password_hash' => 'required|min_length[6]',
        'mobile_number' => 'required|min_length[7]|max_length[15]', // NEW: Mobile number is required (min 7, max 15 characters)
        // Faculty and department are now optional (allow empty) because not every user will have these values.
        'faculty_id'    => 'permit_empty|integer',
        'department_id' => 'permit_empty|integer',
    ];

    protected $validationMessages = []; // Custom validation error messages
}
