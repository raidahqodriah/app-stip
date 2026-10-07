<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Modul B: riwayat perpindahan lokasi BMN (append-only, hanya created_at).
     */
    public function up(): void
    {
        Schema::create('bmn_item_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bmn_item_id')->constrained('bmn_items')->cascadeOnDelete();
            $table->foreignId('from_room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->foreignId('to_room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->string('source'); // submission|return|manual
            $table->string('reason')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bmn_item_movements');
    }
};
