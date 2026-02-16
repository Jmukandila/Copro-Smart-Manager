<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\IncidentController as AdminIncidentController;

// 1. Accueil
Route::get('/', function () {
    return view('welcome');
});


// 2. Dashboard (Liste des incidents)
Route::get('/dashboard', [IncidentController::class, 'index'])->middleware(['auth'])->name('dashboard');
// 3. Profil utilisateur
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 4. Salle d'attente (Si besoin)
Route::get('/waiting', function () {
    return view('waiting-room');
})->name('waiting');

// 5. Gestion des Incidents (Regroupés par sécurité)
Route::middleware(['auth', 'verified'])->group(function () {
    // Formulaire de création
    Route::get('/incidents/create', [IncidentController::class, 'create'])->name('incidents.create');
    
    // Traitement de l'envoi du formulaire (POST)
    Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');
    
    // IA Chat
    Route::post('/ai/chat', [ChatController::class, 'ask'])->name('ai.chat');
});

// Assure-toi que les noms et les URLs sont bien distincts
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Route pour AFFICHER la liste (GET)
    Route::get('/incidents', [AdminIncidentController::class, 'index'])->name('incidents.index');
    
    // Route pour MODIFIER (PATCH) - l'URL doit contenir l'ID {incident}
    Route::patch('/incidents/{incident}', [AdminIncidentController::class, 'update'])->name('incidents.update');
});
require __DIR__.'/auth.php';