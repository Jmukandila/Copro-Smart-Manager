<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class IncidentController extends Controller 
{
    public function index(Request $request)
    {
        $query = Incident::with('user');

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

        $incidents = $query->latest()->paginate(10)->appends($request->query());

        $stats = [
            'total'      => Incident::count(),
            'en_attente' => Incident::where('status', 'en_attente')->count(),
            'en_cours'   => Incident::where('status', 'en_cours')->count(),
            'resolu'     => Incident::where('status', 'resolu')->count(),
        ];

        // Correction de l'erreur compact('')
        $view = auth()->user()->is_admin ? 'admin.incidents.index' : 'dashboard';
        return view($view, compact('incidents', 'stats'));
    }

    // Rapport INDIVIDUEL (Appelé par ta route admin.incidents.report)
    public function downloadReport($id) 
    {
        $incident = Incident::with('user')->findOrFail($id);
        
        // On transforme l'unique incident en collection pour ton pdf.blade.php
        $incidents = collect([$incident]);

        $pdf = Pdf::loadView('admin.incidents.pdf', compact('incidents'))
                  ->setPaper('a4', 'portrait');
        
        return $pdf->download("rapport-incident-{$id}.pdf");
    }

    // Export GLOBAL (Tous les incidents)
    public function exportPdf()
    {
        $incidents = Incident::with('user')->latest()->get();
        $pdf = Pdf::loadView('admin.incidents.pdf', compact('incidents'));
        return $pdf->download('rapport-global-' . now()->format('d-m-Y') . '.pdf');
    }

    public function destroy(Incident $incident)
    {
        if ($incident->photo_path && is_array($incident->photo_path)) {
            foreach ($incident->photo_path as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $incident->delete();
        return redirect()->back()->with('success', 'L\'incident a été supprimé définitivement.');
    }

    public function update(Request $request, Incident $incident)
    {
        $request->validate([
            'status'         => 'required|in:en_attente,en_cours,resolu',
            'internal_notes' => 'nullable|string|max:1000',
        ]);

        $incident->update($request->only(['status', 'internal_notes']));
        return back()->with('success', 'L\'incident a été mis à jour.');
    }
}