<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/app')->middleware('auth');

Route::middleware(['auth'])->group(function () {

    // Modul Pemohon (APP)
    Route::prefix('app')->name('app.')->group(function () {
        Route::get('/', fn () => redirect()->route('app.permohonan.index'));


        Route::get('/permohonan', \App\Livewire\Permohonan\Index::class)
            ->name('permohonan.index');

        Route::get('/permohonan/cipta', \App\Livewire\Permohonan\Form::class)
            ->name('permohonan.create');

        Route::get('/permohonan/{permohonan}', \App\Livewire\Permohonan\Show::class)
            ->name('permohonan.show');

        Route::get('/permohonan/{permohonan}/kemaskini', \App\Livewire\Permohonan\Form::class)
            ->name('permohonan.edit');

        Route::get('/test-livewire', function() {
            return view('livewire.permohonan.index');
        });
    });

    // Modul Admin


    Route::get('/login', function () {
        return redirect()->route('login'); // route Filament admin/login yang bernama "login"
    })->name('login');

    // Route::prefix('admin')
       // ->middleware(['permission:access admin'])
        // ->group(function () {
          //  Route::get('/', fn () => view('admin.dashboard'))->name('admin.dashboard');
      //  });
});
