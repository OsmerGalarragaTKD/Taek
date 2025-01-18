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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->unsignedBigInteger('athlete_id')->nullable();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('generated_date')->useCurrent();
            $table->text('content')->nullable();
            $table->string('file_url', 255)->nullable();
            $table->enum('status', ['Draft', 'Final', 'Archived'])->default('Draft');
            $table->foreign('template_id')->references('id')->on('document_templates')->onDelete('set null');
            $table->foreign('athlete_id')->references('id')->on('athletes')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
