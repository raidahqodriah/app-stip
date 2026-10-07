<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Modul B: pengembalian BMN. damage_note + foto (attachments) wajib bila rusak (validasi aplikasi).
     */
    public function up(): void
    {
        Schema::create('bmn_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bmn_item_id')->constrained('bmn_items')->restrictOnDelete();
            $table->foreignId('requested_by')->constrained('employees')->restrictOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('from_room_id')->constrained('rooms')->restrictOnDelete();
            $table->foreignId('destination_room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->date('return_date');
            $table->text('reason');
            $table->string('condition'); // ItemCondition
            $table->text('damage_note')->nullable();
            $table->string('status')->default('draft')->index(); // RequestStatus
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bmn_returns');
    }
};
