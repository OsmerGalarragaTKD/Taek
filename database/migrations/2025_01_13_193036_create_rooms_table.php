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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->json('schedule')->nullable();
            $table->bigInteger('instructor_id')->nullable();
            $table->integer('max_capacity')->nullable();
            $table->integer('level_required')->nullable();
            $table->enum('status', ['Active', 'Cancelled', 'Completed'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
