<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\IncidentController as AdminIncidentController;

// 1. Accueil
Route::get('/', function () {
    return view('welcome');
});

// 2. Espace Utilisateur (Locataires)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Redirection intelligente vers le dashboard
    Route::get('/dashboard', [IncidentController::class, 'index'])->name('dashboard');

    // Signalement d'incidents
    Route::get('/incidents/create', [IncidentController::class, 'create'])->name('incidents.create');
    Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');
    
    // Téléchargement PDF pour l'utilisateur (son propre rapport)
    Route::get('/incidents/{id}/report', [IncidentController::class, 'downloadReport'])->name('incidents.report');

    // IA Chat
    Route::post('/ai/chat', [ChatController::class, 'ask'])->name('ai.chat');

    // Profil
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

// 3. Espace Administration (Syndic)
// Note : Le middleware 'admin' doit être défini dans ton Kernel ou via une Gate
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Gestion des incidents par l'admin
    Route::get('/incidents', [AdminIncidentController::class, 'index'])->name('incidents.index');
    Route::patch('/incidents/{incident}', [AdminIncidentController::class, 'update'])->name('incidents.update');
    
    // Export PDF global ou spécifique
    Route::get('/incidents/export', [AdminIncidentController::class, 'exportPdf'])->name('incidents.export');
    // Téléchargement PDF d'un incident spécifique (admin)
    Route::get('/incidents/{incident}/report', [AdminIncidentController::class, 'downloadReport'])->name('incidents.report');

    // Gestion des Utilisateurs
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggle');
});

// Autres
Route::get('/waiting', fn() => view('waiting-room'))->name('waiting');

require __DIR__.'/auth.php';