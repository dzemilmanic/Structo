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
        Schema::create('jobs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('title');
    $table->text('description');
    $table->string('category');
    $table->decimal('budget', 10, 2)->nullable();
    $table->string('location');
    $table->decimal('latitude', 10, 8);
    $table->decimal('longitude', 11, 8);
    $table->datetime('deadline')->nullable();
    $table->enum('status', ['open', 'in_progress', 'completed', 'cancelled'])->default('open');
    $table->foreignId('assigned_professional_id')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
