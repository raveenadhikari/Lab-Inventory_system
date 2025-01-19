<?php

namespace App\Models;

use CodeIgniter\Model;

class ComponentModel extends Model
{
    protected $table = 'components';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'date_bought', 'value', 'lab_id', 'state', 'photo', 'qr_code', 'created_at', 'updated_at'];
    protected $returnType = 'array';
}
