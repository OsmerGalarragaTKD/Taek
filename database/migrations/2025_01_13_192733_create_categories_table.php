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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('type', ['Kyorugui', 'Poomsae', 'Para-Kyorugui', 'Para-Poomsae']);
            $table->string('division', 50)->nullable();
            $table->string('age_group', 50)->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->decimal('min_weight', 5, 2)->nullable();
            $table->decimal('max_weight', 5, 2)->nullable();
            $table->enum('gender', ['M', 'F', 'Mixed'])->nullable();
            $table->string('disability_type', 50)->nullable();
            $table->string('disability_class', 10)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
