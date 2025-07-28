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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('license_number')->nullable();
            $table->string('license_status')->nullable();
            $table->string('yelp_id')->nullable();
            $table->string('google_place_id')->nullable();
            $table->string('facebook_id')->nullable();
            $table->decimal('average_rating', 3, 2)->nullable();
            $table->integer('total_reviews')->default(0);
            $table->json('pros')->nullable();
            $table->json('cons')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamp('last_scraped_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
