<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    // Affiche le beau formulaire Tailwind
    public function create() {
        return view('incidents.create');
    }

    // Traite l'envoi du formulaire
   public function store(Request $request) {
    $validated = $request->validate([
        'title' => 'required|string|max:100',
        'category' => 'required|string',
        'location' => 'required|string|max:255',
        'description' => 'required|string|min:10',
        'priority' => 'required|in:basse,moyenne,haute',
        'photo' => 'nullable|image|max:2048', 
    ]);

    // --- SÉCURITÉ ANTI-DOUBLON ---
    $alreadyExists = Incident::where('user_id', $request->user()->id)
        ->where('category', $request->category)
        ->where('status', 'ouvert') // Uniquement si l'ancien n'est pas encore résolu
        ->where('created_at', '>', now()->subDay()) // Moins de 24h
        ->exists();

    if ($alreadyExists) {
        return redirect()->back()
            ->withInput() // Garde les données tapées
            ->with('error', '⚠️ Vous avez déjà signalé un incident similaire aujourd\'hui. Le syndic traite déjà votre demande.');
    }
    // ----------------------------

    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('incidents', 'public');
        $validated['photo_path'] = $path;
    }

    $request->user()->incidents()->create($validated);

    return redirect()->route('dashboard')->with('success', 'Votre signalement a été transmis !');
}
}