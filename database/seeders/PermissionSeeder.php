<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::factory()->create([
            'name' => 'Administrator',
            'description' => 'Puede hacer todo en el sistema',
        ]);

        Permission::factory()->create([
            'name' => 'Colaborador',
            'description' => 'Puede ver y editar todos los tickets y asignarlos a otros ingenieros',
        ]);

        Permission::factory()->create([
            'name' => 'Ingeniero',
            'description' => 'Puede ver y editar sus tickets solamente',
        ]);

        Permission::factory()->create([
            'name' => 'Supervisor',
            'description' => 'Puede ver y editar todos los tickets de su departamento y asignarlos a otros agentes',
        ]);
    }
}
