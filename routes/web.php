<?php

use App\Http\Controllers\BmnController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\PublicPortalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResidenceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SILT STIP Jakarta
|--------------------------------------------------------------------------
*/

// Public Portal & Schedules
Route::get('/', [PublicPortalController::class, 'index'])->name('home');
Route::get('/jadwal', [PublicPortalController::class, 'schedule'])->name('public.schedule');
Route::get('/lab/{code}', [PublicPortalController::class, 'labDetail'])->name('public.lab.detail');

// Modul A: Lab / Simulator SPP
Route::prefix('layanan/lab')->name('lab.')->group(function () {
    Route::get('/booking', [LabController::class, 'bookingForm'])->name('booking');
    Route::post('/booking', [LabController::class, 'storeBooking'])->name('booking.store');
    Route::get('/monitoring', [LabController::class, 'monitoring'])->name('monitoring');
    Route::get('/booking/{id}', [LabController::class, 'detail'])->name('detail');
    Route::post('/booking/{id}/status', [LabController::class, 'updateStatus'])->name('status.update');
    Route::post('/booking/{id}/realisasi', [LabController::class, 'recordRealization'])->name('realization.store');
});

// Modul B: BMN (Barang Milik Negara)
Route::prefix('layanan/bmn')->name('bmn.')->group(function () {
    Route::get('/inventaris', [BmnController::class, 'inventory'])->name('inventory');
    Route::get('/pengajuan', [BmnController::class, 'submissionForm'])->name('submission');
    Route::post('/pengajuan', [BmnController::class, 'storeSubmission'])->name('submission.store');
    Route::get('/pengembalian', [BmnController::class, 'returnForm'])->name('return');
    Route::post('/pengembalian', [BmnController::class, 'storeReturn'])->name('return.store');
    Route::post('/pengembalian/{id}/proses', [BmnController::class, 'processReturn'])->name('return.process');
});

// Modul C: Rumah Dinas
Route::prefix('layanan/rumah-dinas')->name('residence.')->group(function () {
    Route::get('/pengajuan', [ResidenceController::class, 'submissionForm'])->name('submission');
    Route::post('/pengajuan', [ResidenceController::class, 'storeSubmission'])->name('submission.store');
    Route::get('/persetujuan', [ResidenceController::class, 'approvalWorkflow'])->name('approval');
    Route::post('/persetujuan/{id}/proses', [ResidenceController::class, 'processStep'])->name('process');
});

// Modul D: Perpustakaan
Route::prefix('layanan/perpustakaan')->name('library.')->group(function () {
    Route::get('/katalog', [LibraryController::class, 'catalog'])->name('catalog');
    Route::get('/sirkulasi', [LibraryController::class, 'circulation'])->name('circulation');
    Route::post('/pinjam', [LibraryController::class, 'storeLoan'])->name('loan.store');
    Route::post('/kembali', [LibraryController::class, 'storeReturn'])->name('return.store');
});

// Dashboard Eksekutif & Laporan Terpadu
Route::get('/dashboard-terpadu', [ReportController::class, 'dashboard'])->name('reports.dashboard');

// Pusat Dokumen Cetak Terpadu
Route::prefix('dokumen')->name('documents.')->group(function () {
    Route::get('/', [DocumentController::class, 'index'])->name('index');
    Route::get('/booking/{number}', [DocumentController::class, 'bookingConfirmation'])->name('booking');
    Route::get('/bmn-penetapan/{number}', [DocumentController::class, 'bmn_decree'])->name('bmn_decree');
    Route::get('/bmn-pengembalian/{number}', [DocumentController::class, 'bmnReturnReceipt'])->name('bmn_return');
    Route::get('/izin-rumah-dinas/{number}', [DocumentController::class, 'residencePermit'])->name('residence_permit');
});
