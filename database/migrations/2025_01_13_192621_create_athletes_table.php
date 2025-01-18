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
        Schema::create('athletes', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('identity_document', 20)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->enum('gender', ['M', 'F', 'Other'])->nullable();
            $table->string('civil_status', 20)->nullable();
            $table->string('profession', 100)->nullable();
            $table->string('institution', 100)->nullable();
            $table->string('academic_level', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->json('social_media')->nullable();
            $table->string('address_state', 50)->nullable();
            $table->string('address_city', 50)->nullable();
            $table->string('address_parish', 50)->nullable();
            $table->text('address_details')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('allergies')->nullable();
            $table->text('surgeries')->nullable();
            $table->string('emergency_contact_name', 100)->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->string('emergency_contact_relation', 50)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('current_weight', 5, 2)->nullable();
            $table->string('shirt_size', 10)->nullable();
            $table->string('pants_size', 10)->nullable();
            $table->string('shoe_size', 10)->nullable();
            $table->boolean('has_passport')->default(false);
            $table->date('passport_expiry')->nullable();
            $table->enum('status', ['Active', 'Rest', 'Inactive', 'Suspended', 'Retired'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athletes');
    }
};
