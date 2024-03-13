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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('account_id')->nullable()->default('');
            $table->string('account_name')->nullable()->default('');
            $table->string('contact_id')->nullable()->default('');
            $table->string('contact_name')->nullable()->default('');
            $table->string('contact_email')->nullable()->default('');
            $table->integer('department_id')->nullable();
            $table->integer('agent_id')->nullable();
            $table->string('subject');
            $table->text('content');
            $table->integer('status_id')->default(1);
            $table->integer('priority_id')->default(1);
            $table->integer('category_id')->nullable();
            $table->integer('severity_id')->default(1);
            $table->string('cancellation_reason')->nullable()->default('');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
