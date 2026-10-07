<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Modul B: rekap inventaris BMN. Baris dibuat/diubah hanya saat pengajuan disetujui.
     */
    public function up(): void
    {
        Schema::create('bmn_items', function (Blueprint $table) {
            $table->id();
            $table->string('bmn_code')->nullable();
            $table->string('register_number')->nullable(); // NUP
            $table->string('item_name');
            $table->unsignedInteger('quantity')->default(1);
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->restrictOnDelete();
            $table->foreignId('responsible_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('responsible_name');
            $table->date('acquisition_date');
            $table->string('acquisition_source');
            $table->string('condition')->default('good'); // ItemCondition
            $table->boolean('requires_decree')->default(false);
            $table->string('status')->default('active'); // active|returned|disposed
            $table->timestamps();

            // Q16 default: kode unik per unit (kode barang + NUP).
            $table->unique(['unit_id', 'bmn_code', 'register_number']);
            $table->index(['status', 'condition']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bmn_items');
    }
};
