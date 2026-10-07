<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Modul A: booking lab beserta kompetensi, bahan, slot anti-bentrok, dan realisasi.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('room_id')->constrained('rooms')->restrictOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->restrictOnDelete();
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->morphs('requester'); // employee|student
            $table->foreignId('responsible_lecturer_id')->constrained('employees')->restrictOnDelete();
            $table->uuid('recurrence_series_id')->nullable()->index(); // tanpa FK, pengelompok seri
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('purpose');
            $table->unsignedSmallInteger('participant_count');
            $table->string('class_group');
            $table->string('status')->default('draft')->index(); // RequestStatus
            $table->timestamp('submitted_at')->nullable(); // null selama draft, dasar FCFS
            $table->text('notes')->nullable();
            $table->string('external_institution')->nullable();
            $table->timestamps();

            $table->index(['room_id', 'start_at']);
            $table->index('start_at');
        });

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb', 'pgsql'], true)) {
            DB::statement('ALTER TABLE bookings ADD CONSTRAINT bookings_time_range_check CHECK (end_at > start_at)');
        }

        Schema::create('booking_competence', function (Blueprint $table) {
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('competence_id')->constrained('competences')->restrictOnDelete();
            $table->timestamps();

            $table->primary(['booking_id', 'competence_id']);
        });

        Schema::create('booking_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->restrictOnDelete();
            $table->decimal('qty_requested', 12, 2);
            $table->decimal('qty_approved', 12, 2)->nullable();
            $table->decimal('qty_used', 12, 2)->nullable();
            $table->decimal('qty_returned', 12, 2)->nullable();
            $table->text('condition_note')->nullable();
            $table->timestamps();

            $table->unique(['booking_id', 'material_id']);
        });

        // Penegak anti-bentrok: satu slot per lab per waktu. Baris dihapus saat booking melepas slot.
        Schema::create('booking_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->restrictOnDelete();
            $table->dateTime('slot_start');
            $table->timestamps();

            $table->unique(['room_id', 'slot_start']);
        });

        Schema::create('booking_realizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('recorded_by')->constrained('employees')->restrictOnDelete();
            $table->dateTime('actual_start_at');
            $table->dateTime('actual_end_at');
            $table->unsignedSmallInteger('actual_participant_count');
            $table->string('condition_after'); // ItemCondition
            $table->text('incident_note')->nullable();
            $table->timestamps();
        });

        Schema::create('blackout_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->cascadeOnDelete(); // null = semua lab
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('reason');
            $table->timestamps();

            $table->index(['start_at', 'end_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blackout_dates');
        Schema::dropIfExists('booking_realizations');
        Schema::dropIfExists('booking_slots');
        Schema::dropIfExists('booking_materials');
        Schema::dropIfExists('booking_competence');
        Schema::dropIfExists('bookings');
    }
};
