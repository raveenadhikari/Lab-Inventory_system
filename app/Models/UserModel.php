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
        'faculty',
        'department',
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
        'username' => 'required|min_length[3]|max_length[50]',
        'email' => 'required|valid_email|max_length[100]',
        'password_hash' => 'required|min_length[6]',
        'faculty' => 'required|max_length[100]',
    ];

    protected $validationMessages = []; // Custom validation error messages
}
