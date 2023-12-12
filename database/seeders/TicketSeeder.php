<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\ChatMessage;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::factory()
            ->count(10)
            ->create()
            ->each(function($ticket){

                $ticket->messages()->createMany(
                    ChatMessage::factory()
                        ->count(rand(5, 15))
                        ->make()
                        ->toArray()
                );

            });
    }
}
