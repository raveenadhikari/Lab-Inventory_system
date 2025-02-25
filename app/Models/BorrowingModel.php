<?php

namespace App\Models;

use CodeIgniter\Model;

class BorrowingModel extends Model
{
    protected $table = 'borrowings'; // Table name
    protected $primaryKey = 'id';    // Primary key

    protected $allowedFields = [
        'component_id',
        'user_id',
        'status',
        'borrow_date',
        'return_date',
        'approved_at',
        'return_status',
    ]; // Fields that can be inserted/updated

    protected $useTimestamps = false; // Disable automatic timestamps
}
