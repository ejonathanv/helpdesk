<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::factory()->create([
            'name' => 'General',
            'description' => 'Departamento general',
        ]);

        Department::factory()->create([
            'name' => 'Servicio',
            'description' => 'Departamento de servicio',
        ]);

        Department::factory()->create([
            'name' => 'Comercial',
            'description' => 'Departamento comercial',
        ]);

        Department::factory()->create([
            'name' => 'Suministro',
            'description' => 'Departamento de suministro',
        ]);

        Department::factory()->create([
            'name' => 'Diseño',
            'description' => 'Departamento de diseño',
        ]);

        Department::factory()->create([
            'name' => 'Centro de control',
            'description' => 'Departamento de centro de control',
        ]);

        Department::factory()->create([
            'name' => 'Sistemas',
            'description' => 'Departamento de sistemas',
        ]);

        Department::factory()->create([
            'name' => 'ITS',
            'description' => 'Departamento de ITS',
        ]);
    }
}
