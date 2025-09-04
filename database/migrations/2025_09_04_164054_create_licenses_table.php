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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('license_no')->unique();
            $table->date('last_update');
            $table->string('business_name');
            $table->string('bus_name_2')->nullable();
            $table->string('full_business_name')->nullable();
            $table->string('mailing_address');
            $table->string('city');
            $table->string('state');
            $table->string('county');
            $table->string('zip_code');
            $table->string('country')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_type');
            $table->date('issue_date');
            $table->date('reissue_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->date('inactivation_date')->nullable();
            $table->date('reactivation_date')->nullable();
            $table->string('pending_suspension')->nullable();
            $table->string('pending_class_removal')->nullable();
            $table->string('pending_class_replace')->nullable();
            $table->string('primary_status');
            $table->string('secondary_status')->nullable();
            $table->text('classifications')->nullable();
            $table->string('asbestos_reg')->nullable();
            $table->string('workers_comp_coverage_type')->nullable();
            $table->string('wc_insurance_company')->nullable();
            $table->string('wc_policy_number')->nullable();
            $table->date('wc_effective_date')->nullable();
            $table->date('wc_expiration_date')->nullable();
            $table->date('wc_cancellation_date')->nullable();
            $table->date('wc_suspend_date')->nullable();
            $table->string('cb_surety_company')->nullable();
            $table->string('cb_number')->nullable();
            $table->date('cb_effective_date')->nullable();
            $table->date('cb_cancellation_date')->nullable();
            $table->decimal('cb_amount', 10, 2)->nullable();
            $table->string('wb_surety_company')->nullable();
            $table->string('wb_number')->nullable();
            $table->date('wb_effective_date')->nullable();
            $table->date('wb_cancellation_date')->nullable();
            $table->decimal('wb_amount', 10, 2)->nullable();
            $table->string('db_surety_company')->nullable();
            $table->string('db_number')->nullable();
            $table->date('db_effective_date')->nullable();
            $table->date('db_cancellation_date')->nullable();
            $table->decimal('db_amount', 10, 2)->nullable();
            $table->date('date_required')->nullable();
            $table->string('discp_case_region')->nullable();
            $table->string('db_bond_reason')->nullable();
            $table->string('db_case_no')->nullable();
            $table->string('name_tp_2')->nullable();
            $table->timestamps();

            // Add indexes for better performance
            $table->index('business_name');
            $table->index('city');
            $table->index('county');
            $table->index('primary_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
