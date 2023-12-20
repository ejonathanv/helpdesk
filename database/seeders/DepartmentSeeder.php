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
        ]);

        Department::factory()->create([
            'name' => 'Servicio',
        ]);

        Department::factory()->create([
            'name' => 'Comercial',
        ]);

        Department::factory()->create([
            'name' => 'Suministro',
        ]);

        Department::factory()->create([
            'name' => 'Diseño',
        ]);

        Department::factory()->create([
            'name' => 'Centro de control',
        ]);

        Department::factory()->create([
            'name' => 'Sistemas',
        ]);

        Department::factory()->create([
            'name' => 'ITS',
        ]);
    }
}
