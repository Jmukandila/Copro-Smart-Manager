<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Pour l'étape suivante (PDF)

class IncidentController extends Controller
{
    /**
     * Liste des incidents (Admin : tout + recherche | User : les siens)
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Initialisation de la requête
        // Si admin, on prend tout avec les infos de l'utilisateur. Sinon, juste les siens.
        $query = $user->is_admin 
            ? Incident::with('user') 
            : $user->incidents();

        // 2. Logique de recherche (Uniquement pour l'admin ou pour filtrer ses propres dossiers)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 3. Pagination et vue
        $incidents = $query->latest()->paginate(10);
        
        // On retourne la vue admin si c'est un admin, sinon le dashboard classique
        $view = $user->is_admin ? 'incidents.index' : 'dashboard';
        
        return view($view, compact('incidents'));
    }

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
        // 1. Validation
        $validated = $request->validate([
            'title'         => 'required|string|max:100',
            'category'      => 'required|string',
            'location'      => 'required|string|max:255',
            'description'   => 'required|string|min:10',
            'priority'      => 'required|in:basse,moyenne,haute',
            'photo'         => 'nullable|image|max:2048', 
            'other_details' => 'nullable|string|max:100',
        ]);

        // 2. Anti-doublon (24h)
        $alreadyExists = Incident::where('user_id', auth()->id())
            ->where('category', $request->category)
            ->where('title', $request->title)
            ->where('created_at', '>', now()->subDay())
            ->exists();

        if ($alreadyExists) {
            return redirect()->back()
                ->withInput()
                ->with('error', '⚠️ Signalement déjà enregistré il y a moins de 24h.');
        }

        // 3. Gestion de l'image
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('incidents', 'public');
            $validated['photo_path'] = $path;
        }

        unset($validated['photo']);
        
        // 4. Création
        auth()->user()->incidents()->create($validated);

        return redirect()->route('incidents.index')->with('success', 'Signalement transmis avec succès !');
    }

    /**
     * Génération du rapport PDF (Etape suivante)
     */
    public function downloadReport($id)
    {
        $incident = Incident::with('user')->findOrFail($id);
        
        // Vérification de sécurité (seul l'admin ou le propriétaire peut télécharger)
        if (!auth()->user()->is_admin && $incident->user_id !== auth()->id()) {
            abort(403);
        }

        $pdf = Pdf::loadView('incidents.report', compact('incident'));
        return $pdf->download("rapport-incident-{$incident->id}.pdf");
    }
}