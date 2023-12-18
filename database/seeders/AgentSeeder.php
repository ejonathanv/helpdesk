<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\UserPermission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Agent::factory()->count(10)->create()->each(function ($agent) {
            UserPermission::factory()->create([
                'user_id' => $agent->user_id
            ]);
        });
    }
}
