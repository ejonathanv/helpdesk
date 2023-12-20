<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Agent;
use App\Models\TicketCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $statuses = [1, 2, 3, 4, 5, 6];
        $priorities = [1, 2, 3, 4];
        $category = TicketCategory::inRandomOrder()->first();
        $severities = [1, 2, 3, 4];
        $agent = Agent::inRandomOrder()->first();

        return [
            'user_id' => User::factory(),
            'account_id' => '5e82ef7e06f75cac9',
            'account_name' => 'IP Media River',
            'contact_id' => "",
            'contact_name' => "",
            'contact_email' => "",
            'department_id' => $agent->department_id,
            'agent_id' => $agent->id,
            'subject' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'status_id' => $this->faker->randomElement($statuses),
            'priority_id' => $this->faker->randomElement($priorities),
            'category_id' => $category->id,
            'severity_id' => $this->faker->randomElement($severities),
        ];
    }
}
