<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agent>
 */
class AgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $department = Department::inRandomOrder()->first();

        return [
            'user_id' => User::factory(),
            'department_id' => $department->id,
            'job_title' => $this->faker->jobTitle,
            'phone' => $this->faker->phoneNumber,
            'mobile' => $this->faker->phoneNumber,
        ];
    }
}
