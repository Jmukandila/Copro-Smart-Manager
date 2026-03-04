<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\IncidentController as AdminIncidentController;
use App\Models\Incident;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'verified'])->group(function () {
    
    
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            $incidents = Incident::latest()->get();
        } else {
            $incidents = auth()->user()->incidents()->latest()->get();
        }
        return view('dashboard', ['incidents' => $incidents]);
    })->name('dashboard');

    Route::get('/incidents/create', [IncidentController::class, 'create'])->name('incidents.create');
    Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');
    Route::get('/incidents/{id}/report', [IncidentController::class, 'downloadReport'])->name('incidents.report');
    Route::post('/ai/chat', [ChatController::class, 'ask'])->name('ai.chat');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
   
    Route::get('/incidents', [AdminIncidentController::class, 'index'])->name('incidents.index');
    Route::patch('/incidents/{incident}', [AdminIncidentController::class, 'update'])->name('incidents.update');
    Route::delete('/incidents/{incident}', [AdminIncidentController::class, 'destroy'])->name('incidents.destroy');
    Route::get('/incidents/export', [AdminIncidentController::class, 'exportPdf'])->name('incidents.export');
    Route::get('/incidents/{incident}/report', [AdminIncidentController::class, 'downloadReport'])->name('incidents.report');

   
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

Route::get('/waiting', fn() => view('waiting-room'))->name('waiting');

require __DIR__.'/auth.php';
