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
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('reimbursement_category_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Judul pengajuan
            $table->text('description');
            $table->date('expense_date'); // Tanggal pengeluaran
            $table->decimal('amount', 15, 2); // Nominal yang diajukan
            $table->string('receipt')->nullable(); // Upload bukti/kwitansi
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->decimal('approved_amount', 15, 2)->nullable(); // Bisa berbeda dari amount yang diajukan
            $table->date('payment_date')->nullable(); // Tanggal dibayar
            $table->string('payment_method')->nullable(); // Transfer, Cash, dll
            $table->string('payment_reference')->nullable(); // Nomor referensi transfer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reimbursements');
    }
};
