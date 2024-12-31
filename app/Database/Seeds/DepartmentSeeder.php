<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Department of Physics', 'faculty_id' => 1],
            ['name' => 'Department of Chemistry', 'faculty_id' => 1],
            ['name' => 'Department of Zoology', 'faculty_id' => 1],
            ['name' => 'Department of Mathematics', 'faculty_id' => 1],
            ['name' => 'Department of Nuclear Science', 'faculty_id' => 1],
            ['name' => 'Department of Statistics', 'faculty_id' => 1],
            ['name' => 'Department of Plant Science', 'faculty_id' => 1],
            // Add departments for other faculties
        ];

        $this->db->table('departments')->insertBatch($data);
    }
}
