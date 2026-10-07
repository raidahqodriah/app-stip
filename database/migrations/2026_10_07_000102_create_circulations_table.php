<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Modul D: Perpustakaan, sirkulasi (borrowed -> returned). Terlambat = turunan dari due_date.
     * book_id restrict: buku yang punya riwayat/sedang dipinjam tidak dapat dihapus.
     */
    public function up(): void
    {
        Schema::create('circulations', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->morphs('borrower'); // employee|student
            $table->foreignId('book_id')->constrained('books')->restrictOnDelete();
            $table->foreignId('loaned_by')->constrained('employees')->restrictOnDelete();
            $table->foreignId('returned_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->string('status')->default('borrowed'); // borrowed|returned
            $table->string('return_condition')->nullable(); // ItemCondition
            $table->unsignedSmallInteger('late_days')->nullable();
            $table->unsignedInteger('fine_amount')->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->timestamp('fine_paid_at')->nullable();
            $table->text('officer_note')->nullable();
            $table->timestamps();

            $table->index(['status', 'due_date']);
            $table->index(['borrower_type', 'borrower_id', 'status']);
            $table->index('loan_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('circulations');
    }
};
