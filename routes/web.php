<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Since we are primarily using API and Filament Admin, we don't need the default Inertia routes.
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::inertia('dashboard', 'dashboard')->name('dashboard');
// });

// require __DIR__.'/settings.php';
