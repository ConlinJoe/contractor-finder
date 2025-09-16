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
        Schema::create('job_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "Plumbing", "Electrical", "Roofing"
            $table->string('slug')->unique(); // e.g., "plumbing", "electrical", "roofing"
            $table->text('description')->nullable(); // Description of the job type
            $table->string('category')->nullable(); // e.g., "Home Improvement", "Commercial"
            $table->json('keywords')->nullable(); // Array of related keywords for search
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_types');
    }
};
