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
        Schema::table('profi_requests', function (Blueprint $table) {
            // Remove the old image column if it exists
            if (Schema::hasColumn('profi_requests', 'image')) {
                $table->dropColumn('image');
            }
            
            // Add the new files column
            $table->json('files')->nullable()->after('specialization');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profi_requests', function (Blueprint $table) {
            // Remove the files column
            $table->dropColumn('files');
            
            // Add back the image column
            $table->string('image')->nullable()->after('specialization');
        });
    }
};