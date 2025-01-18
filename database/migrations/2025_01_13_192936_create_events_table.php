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
            $table->string('name', 200);
            $table->enum('type', ['Competition', 'Promotion_Test', 'Training', 'Other']);
            $table->foreignId('venue_id')->nullable()->constrained()->onDelete('set null');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('registration_deadline')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['Planned', 'Active', 'Completed', 'Cancelled'])->default('Planned');
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
