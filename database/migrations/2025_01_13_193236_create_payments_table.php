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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->timestamp('payment_date')->useCurrent();
            $table->enum('payment_type', ['Monthly_Fee', 'Event_Registration', 'Equipment', 'Other']);
            $table->string('payment_method', 50)->nullable();
            $table->enum('status', ['Pending', 'Completed', 'Failed', 'Refunded'])->default('Pending');
            $table->string('reference_number', 50)->nullable();
            $table->string('receipt_url', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
