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
        Schema::create('athlete_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_id')->constrained()->onDelete('cascade');
            $table->foreignId('training_plan_id')->constrained()->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->enum('status', ['In_Progress', 'Completed', 'Cancelled'])->default('In_Progress');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athlete_trainings');
    }
};
