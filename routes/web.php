<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ChatController;

// 1. Accueil
Route::get('/', function () {
    return view('welcome');
});

// 2. Dashboard (Accès protégé)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

require __DIR__.'/auth.php';