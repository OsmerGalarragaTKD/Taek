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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('type', 50)->nullable();
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->enum('status', ['New', 'In_Use', 'Repaired', 'Discarded'])->default('New');
            $table->date('acquisition_date')->nullable();
            $table->date('last_maintenance_date')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
