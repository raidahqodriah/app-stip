<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Modul A: bahan/alat lab dan kit bahan default per mata kuliah + lab.
     */
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->restrictOnDelete();
            $table->foreignId('bmn_item_id')->nullable()->constrained('bmn_items')->nullOnDelete();
            $table->string('name');
            $table->string('type'); // consumable|equipment|module
            $table->string('unit'); // satuan
            $table->decimal('stock_qty', 12, 2)->default(0);
            $table->decimal('min_stock', 12, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('material_kits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->decimal('qty_per_participant', 12, 2)->nullable();
            $table->decimal('qty_per_session', 12, 2)->nullable();
            $table->timestamps();

            $table->unique(['subject_id', 'room_id', 'material_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_kits');
        Schema::dropIfExists('materials');
    }
};
