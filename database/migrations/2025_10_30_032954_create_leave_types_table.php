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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Cuti Tahunan, Cuti Sakit, Cuti Melahirkan, dll
            $table->string('code')->unique(); // ANNUAL, SICK, MATERNITY
            $table->text('description')->nullable();
            $table->integer('max_days')->nullable(); // Max hari yang bisa diambil
            $table->boolean('is_paid')->default(true); // Apakah dibayar
            $table->boolean('requires_document')->default(false); // Perlu surat keterangan dokter, dll
            $table->boolean('is_deducted_from_quota')->default(true); // Apakah mengurangi jatah cuti
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
