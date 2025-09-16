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
        Schema::table('companies', function (Blueprint $table) {
            $table->foreignId('job_type_id')->nullable()->after('state')->constrained()->onDelete('set null');
            $table->index('job_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['job_type_id']);
            $table->dropIndex(['job_type_id']);
            $table->dropColumn('job_type_id');
        });
    }
};
