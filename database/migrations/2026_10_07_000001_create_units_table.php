<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Unit = prodi (SPP) + unit kerja (BMN), hierarkis.
     * FK head_employee_id ditambahkan setelah tabel employees dibuat (relasi melingkar).
     */
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('units')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type')->index(); // study_program|work_unit|service_unit
            $table->unsignedBigInteger('head_employee_id')->nullable()->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
