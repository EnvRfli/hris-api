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
        Schema::table('employee_profiles', function (Blueprint $table) {
            // Add missing identity columns
            $table->string('nip')->nullable()->after('employee_id');
            $table->string('nik')->nullable()->after('nip');
            
            // Add birth information
            $table->string('birth_place')->nullable()->after('birth_date');
            
            // Add personal information
            $table->string('religion')->nullable()->after('gender');
            $table->string('marital_status')->nullable()->after('religion');
            
            // Add employment details
            $table->string('employment_type')->nullable()->after('employment_status');
            $table->date('probation_end_date')->nullable()->after('permanent_date');
            $table->date('end_date')->nullable()->after('resign_date');
            
            // Add financial information
            $table->decimal('allowances', 15, 2)->nullable()->after('basic_salary');
            $table->string('tax_number')->nullable()->after('bank_account_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'nip',
                'nik',
                'birth_place',
                'religion',
                'marital_status',
                'employment_type',
                'probation_end_date',
                'end_date',
                'allowances',
                'tax_number',
            ]);
        });
    }
};
