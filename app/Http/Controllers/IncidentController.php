<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    /**
     * Affiche le formulaire de signalement.
     */
    public function create() 
    {
        return view('incidents.create');
    }

    /**
     * Enregistre le signalement dans la base de données.
     */
    public function store(Request $request) 
    {
        // 1. Validation des données
        $validated = $request->validate([
            'title'       => 'required|string|max:100',
            'category'    => 'required|string',
            'location'    => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'priority'    => 'required|in:basse,moyenne,haute',
            'photo'       => 'nullable|image|max:2048', 
        ]);

        // 2. Sécurité anti-doublon améliorée
        $alreadyExists = Incident::where('user_id', $request->user()->id)
            ->where('category', $request->category)
            ->where('title', $request->title) // On vérifie aussi le titre pour plus de précision
            ->where('created_at', '>', now()->subDay())
            ->exists();

        if ($alreadyExists) {
            return redirect()->back()
                ->withInput()
                ->with('error', '⚠️ Signalement déjà enregistré. Le syndic a déjà reçu une demande identique de votre part il y a moins de 24h.');
        }

        // 3. Gestion de l'image
        if ($request->hasFile('photo')) {
            // 'public' signifie storage/app/public/incidents
            $path = $request->file('photo')->store('incidents', 'public');
            $validated['photo_path'] = $path;
        }

        // 4. Nettoyage du tableau avant insertion
        unset($validated['photo']);
        
        // 5. Création sécurisée via la relation
        $request->user()->incidents()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Votre signalement a été transmis avec succès !');
    }
}