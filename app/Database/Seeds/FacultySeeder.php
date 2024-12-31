<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Faculty of Science'],
            ['name' => 'Faculty of Medicine'],
            ['name' => 'Faculty of Finance and Management'],
            ['name' => 'Faculty of Technology'],
            ['name' => 'Faculty of Arts'],
            ['name' => 'Faculty of Nursing'],
            ['name' => 'Faculty of Education'],
        ];

        $this->db->table('faculties')->insertBatch($data);
    }
}
