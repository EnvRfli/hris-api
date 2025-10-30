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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('check_in_location')->nullable(); // GPS coordinates
            $table->string('check_out_location')->nullable();
            $table->string('check_in_photo')->nullable(); // Foto saat check-in
            $table->string('check_out_photo')->nullable();
            $table->enum('status', ['present', 'late', 'absent', 'half_day', 'sick', 'leave', 'holiday', 'remote'])->default('present');
            $table->integer('late_duration')->default(0); // dalam menit
            $table->integer('work_duration')->default(0); // dalam menit
            $table->text('notes')->nullable();
            $table->boolean('is_overtime')->default(false);
            $table->integer('overtime_duration')->default(0); // dalam menit
            $table->timestamps();
            
            $table->unique(['user_id', 'date']); // Satu user hanya bisa 1 attendance per hari
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
