<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VulnerabilityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NvdController;
use App\Http\Controllers\VirusTotalController;

// Public
Route::get('/', function () {
    return view('welcome');
});

// Language Switch
Route::get('/lang/{locale}', function (string $locale) {
    // Only allow languages we actually support
    if (in_array($locale, ['en', 'pt_BR'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Requires login
Route::middleware(['auth', 'verified'])->group(function () {


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile management (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Analyst and Admin can access vulnerabilities (we'll build this next)
    Route::middleware(['role:admin|analyst'])->group(function () {
	    Route::resource('vulnerabilities', VulnerabilityController::class);
        Route::get('/nvd/lookup', [NvdController::class, 'lookup'])->name('nvd.lookup');
        Route::get('/vulnerabilities/{vulnerability}/scan', [VirusTotalController::class, 'scan'])->name('vulnerabilities.scan');
    });

    // Admin only
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

});

require __DIR__.'/auth.php';
