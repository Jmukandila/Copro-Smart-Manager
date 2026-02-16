<?php

namespace App\Http\Controllers\Admin; // Note le "\Admin" ici

use App\Http\Controllers\Controller;
use App\Models\Report; // Ou Incident selon ton modèle
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index() 
    {
        $incidents = Report::with('user')->latest()->get();
        
        $stats = [
            'total' => $incidents->count(),
            'en_attente' => $incidents->where('status', 'en_attente')->count(),
            'en_cours' => $incidents->where('status', 'en_cours')->count(),
            'resolu' => $incidents->where('status', 'resolu')->count(),
        ];

        return view('admin.incidents.index', compact('incidents', 'stats'));
    }

    public function update(Request $request, Report $incident)
    {
        $incident->update($request->only(['status', 'admin_comment']));
        return back()->with('success', 'Mise à jour effectuée !');
    }
}