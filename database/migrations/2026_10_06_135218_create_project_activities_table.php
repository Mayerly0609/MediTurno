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
        Schema::create('project_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();

            $table->foreignId('phase_id')
                ->constrained('project_phases')
                ->cascadeOnDelete();

            $table->string('title');

            $table->text('description')->nullable();

            $table->foreignId('responsible_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('status')
                ->default('pending');

            $table->string('priority')
                ->default('medium');

            $table->date('due_date')->nullable();

            $table->decimal('estimated_hours', 8, 2)
                ->nullable();

            $table->unsignedTinyInteger('progress')
                ->default(0);

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_activities');
    }
};
