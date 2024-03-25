<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Agent;
use App\Models\UserPermission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Jonathan Velazquez',
            'email' => 'admin@admin.com',
        ])->each(function ($user) {
            UserPermission::factory()->create([
                'user_id' => $user->id,
                'permission_id' => 1,
            ]);
            Agent::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
