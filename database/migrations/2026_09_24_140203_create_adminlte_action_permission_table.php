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
        Schema::create('adminlte_action_permission', function (Blueprint $table) {
             $table->foreignId('permission_id')
                ->constrained('adminlte_permissions')
                ->cascadeOnDelete();

            $table->foreignId('action_id')
                ->constrained('adminlte_actions')
                ->cascadeOnDelete();

            $table->primary([
                'permission_id',
                'action_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adminlte_action_permission');
    }
};
