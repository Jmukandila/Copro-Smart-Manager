<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class IncidentController extends Controller 
{
    /**
     * Affiche la console admin avec recherche et statistiques.
     */
    public function index(Request $request)
    {
        // 1. Initialisation de la requête avec la relation utilisateur
        $query = Incident::with('user');

        // 2. Logique de recherche (Nom, Appart, Titre, Description)
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

        // 3. Récupération des données (Pagination pour éviter les lenteurs)
        // appends() permet de garder le mot-clé de recherche quand on change de page
        $incidents = $query->latest()->paginate(10)->appends($request->query());

        // 4. Calcul des statistiques (Basé sur la table complète pour les compteurs fixes)
        $stats = [
            'total'      => Incident::count(),
            'en_attente' => Incident::where('status', 'en_attente')->count(),
            'en_cours'   => Incident::where('status', 'en_cours')->count(),
            'resolu'     => Incident::where('status', 'resolu')->count(),
        ];

        return view('admin.incidents.index', compact('incidents', 'stats'));
    }

    /**
     * Mise à jour du statut et des notes par l'admin.
     */
    public function update(Request $request, Incident $incident)
    {
        $request->validate([
            'status'         => 'required|in:en_attente,en_cours,resolu',
            'admin_comment'  => 'nullable|string|max:1000',
            'internal_notes' => 'nullable|string|max:1000',
        ]);

        $incident->update([
            'status'         => $request->status,
            'admin_comment'  => $request->admin_comment,
            'internal_notes' => $request->internal_notes,
        ]);

        return back()->with('success', 'L\'incident a été mis à jour avec succès !');
    }

    /**
     * Exporte TOUS les incidents en un seul PDF.
     */
    public function exportPdf()
    {
        $incidents = Incident::with('user')->latest()->get();
        
        // Assure-toi que le fichier resources/views/admin/incidents/pdf.blade.php existe
        $pdf = Pdf::loadView('admin.incidents.pdf', compact('incidents'));
        
        return $pdf->download('rapport-global-' . now()->format('d-m-Y') . '.pdf');
    }

    /**
     * Téléchargement PDF d'un rapport pour un incident unique.
     */
    public function downloadReport($id)
    {
        $incident = Incident::with('user')->findOrFail($id);

        // Assure-toi que le fichier resources/views/admin/incidents/report.blade.php existe
        $pdf = Pdf::loadView('admin.incidents.report', compact('incident'));
        
        return $pdf->download("rapport-incident-{$incident->id}.pdf");
    }
}