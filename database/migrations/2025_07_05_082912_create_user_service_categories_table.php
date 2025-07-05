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
        Schema::create('user_service_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Ensure a user can't have the same category twice
            $table->unique(['user_id', 'service_category_id'], 'user_service_category_unique');
            
            // Add index for faster queries
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_service_categories');
    }
};
