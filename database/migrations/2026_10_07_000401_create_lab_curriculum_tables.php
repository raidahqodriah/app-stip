<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Modul A: mata kuliah, IMO Model Course, kompetensi, dan pivot pemetaannya.
     */
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('category')->index(); // teknika|nautika|kalk
            $table->unsignedTinyInteger('semester')->nullable();
            $table->unsignedTinyInteger('credits')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('imo_model_courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->unsignedSmallInteger('edition_year')->nullable();
            $table->timestamps();
        });

        Schema::create('competences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imo_model_course_id')->constrained('imo_model_courses')->restrictOnDelete();
            $table->string('code');
            $table->string('title');
            $table->string('stcw_reference')->nullable();
            $table->timestamps();

            $table->unique(['imo_model_course_id', 'code']);
        });

        Schema::create('competence_subject', function (Blueprint $table) {
            $table->foreignId('competence_id')->constrained('competences')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['competence_id', 'subject_id']);
        });

        Schema::create('room_subject', function (Blueprint $table) {
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['room_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_subject');
        Schema::dropIfExists('competence_subject');
        Schema::dropIfExists('competences');
        Schema::dropIfExists('imo_model_courses');
        Schema::dropIfExists('subjects');
    }
};
