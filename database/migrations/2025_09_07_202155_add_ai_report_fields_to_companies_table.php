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
            $table->longText('ai_report_markdown')->nullable()->after('cons');
            $table->json('ai_report_json')->nullable()->after('ai_report_markdown');
            $table->timestamp('ai_report_generated_at')->nullable()->after('ai_report_json');
            $table->boolean('ai_report_available')->default(false)->after('ai_report_generated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'ai_report_markdown',
                'ai_report_json',
                'ai_report_generated_at',
                'ai_report_available'
            ]);
        });
    }
};
