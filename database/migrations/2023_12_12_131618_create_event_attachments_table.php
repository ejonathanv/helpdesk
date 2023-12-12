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
        Schema::create('event_attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->integer('ticket_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_ext');
            $table->string('original_file_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_attachments');
    }
};
