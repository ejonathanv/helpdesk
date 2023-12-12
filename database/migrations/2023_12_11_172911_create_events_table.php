<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('ticket_id');
            $table->text('comments');
            // Necesitamos obtener los dias, horas y minutos invertidos en el ticket
            $table->integer('days')->default(0);
            $table->integer('hours')->default(0);
            $table->integer('minutes')->default(0);
            // Necesitamos obtener el tiempo total invertido en el ticket
            $table->integer('total_time')->default(0);
            $table->string('type')->default('remote'); // remote, on-site
            $table->string('publicAs')->default('public'); // public, private
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
