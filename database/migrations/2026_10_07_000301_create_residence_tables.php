<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Modul C: rumah dinas dan surat izin penghuni (disetujui Ketua STIP).
     */
    public function up(): void
    {
        Schema::create('official_residences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->string('house_number');
            $table->string('address');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('residence_permits', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number')->nullable()->unique(); // terisi saat surat terbit
            $table->foreignId('employee_id')->constrained('employees')->restrictOnDelete();
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->foreignId('official_residence_id')->constrained('official_residences')->restrictOnDelete();
            $table->foreignId('submitted_by')->constrained('employees')->restrictOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->date('occupancy_start');
            $table->date('occupancy_end');
            $table->text('occupancy_notes')->nullable();
            $table->string('status')->default('draft')->index(); // RequestStatus
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residence_permits');
        Schema::dropIfExists('official_residences');
    }
};
