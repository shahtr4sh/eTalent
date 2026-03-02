<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Auth\LoginController;
use App\Livewire\App\Profile\ShowProfile;
use App\Livewire\App\Permohonan\Index as PermohonanIndex;

/*
|--------------------------------------------------------------------------
| APP MODULE (Pemohon)
|--------------------------------------------------------------------------
*/

// Login APP
Route::prefix('app')->group(function () {

    Route::get('/login', [LoginController::class, 'show'])
        ->name('app.login');

    Route::post('/login', [LoginController::class, 'authenticate'])
        ->name('app.login.submit');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->middleware('auth')
        ->name('app.logout');

    // Protected routes
    Route::middleware('auth')->group(function () {

        // Dashboard /app
        Route::get('/', ShowProfile::class)
            ->name('app.dashboard');

        Route::get('/profil', ShowProfile::class)
            ->name('app.profil');

        Route::view('/permohonan', 'app.permohonan.index')
            ->name('app.permohonan.index');
    });
});


/*
|--------------------------------------------------------------------------
| ADMIN (Filament)
|--------------------------------------------------------------------------
*/

// Biarkan Filament urus sendiri /admin
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

Route::view('/test', 'layouts.test');


