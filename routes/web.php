<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Since we are primarily using API and Filament Admin, we don't need the default Inertia routes.
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::inertia('dashboard', 'dashboard')->name('dashboard');
// });

Route::get('/setup-app', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    \Illuminate\Support\Facades\Artisan::call('optimize');
    return 'Setup and Optimization Complete! Caches cleared.';
});

// require __DIR__.'/settings.php';
