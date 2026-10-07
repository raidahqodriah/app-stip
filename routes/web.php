<?php

use App\Livewire\Front\AnnouncementIndex;
use App\Livewire\Front\HomePage;
use App\Livewire\Front\LabCatalog;
use App\Livewire\Front\LabDetail;
use App\Livewire\Front\LabSchedule;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SILT-STIP Terpadu (Livewire 4 Frontend)
|--------------------------------------------------------------------------
*/

Route::livewire('/', HomePage::class)->name('home');
Route::livewire('/lab', LabCatalog::class)->name('lab.catalog');
Route::livewire('/lab/{code}', LabDetail::class)->name('lab.detail');
Route::livewire('/jadwal', LabSchedule::class)->name('lab.schedule');
Route::livewire('/pengumuman', AnnouncementIndex::class)->name('announcements');

Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['id', 'en'], true)) {
        session(['locale' => $locale]);
        cookie()->queue(cookie('app_locale', $locale, 60 * 24 * 365));
    }

    return redirect()->back();
})->name('locale.switch');
