<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ruangan (BMN) + lab (SPP). Lab yang dapat dibooking: is_bookable = true.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->foreignId('pic_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type'); // lab|classroom|office|warehouse|other
            $table->boolean('is_bookable')->default(false);
            $table->string('lab_category')->nullable();
            $table->unsignedSmallInteger('capacity')->nullable();
            $table->json('operating_hours')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_bookable', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
