<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPermission>
 */
class UserPermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $permission = Permission::inRandomOrder()->where('name', '<>', 'Administrador')->first();

        return [
            'user_id' => User::factory(),
            'permission_id' => $permission->id,
        ];
    }
}
