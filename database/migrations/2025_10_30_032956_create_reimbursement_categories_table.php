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
        Schema::create('reimbursement_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Transport, Makan, Akomodasi, Kesehatan, dll
            $table->string('code')->unique(); // TRANSPORT, MEAL, ACCOM, HEALTH
            $table->text('description')->nullable();
            $table->decimal('max_amount', 15, 2)->nullable(); // Maksimal nominal yang bisa di-reimburse
            $table->boolean('requires_receipt')->default(true); // Perlu bukti/kwitansi
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reimbursement_categories');
    }
};
