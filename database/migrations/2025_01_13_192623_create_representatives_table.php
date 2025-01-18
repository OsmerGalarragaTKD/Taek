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
        Schema::create('representatives', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('identity_document', 20)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('profession', 100)->nullable();
            $table->string('blood_type', 5)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->json('social_media')->nullable();
            $table->boolean('is_also_athlete')->default(false);
            $table->boolean('has_passport')->default(false);
            $table->date('passport_expiry')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('representatives');
    }
};
