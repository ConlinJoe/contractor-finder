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
        Schema::create('company_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->decimal('overall_score', 5, 2);
            $table->decimal('review_score', 5, 2);
            $table->decimal('license_score', 5, 2);
            $table->decimal('volume_score', 5, 2);
            $table->json('score_breakdown')->nullable(); // Detailed breakdown of scoring
            $table->timestamp('scored_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_scores');
    }
};
