<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = $user->is_admin ? Incident::with('user') : $user->incidents();

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
        
        $data = ['incidents' => $incidents];

        if ($user->is_admin) {
            $data['stats'] = [
                'total'      => Incident::count(),
                'en_attente' => Incident::where('status', 'en_attente')->count(),
                'en_cours'   => Incident::where('status', 'en_cours')->count(),
                'resolu'     => Incident::where('status', 'resolu')->count(),
            ];
            return view('admin.incidents.index', $data);
        }

        return view('dashboard', $data);
    }

    public function create() 
    {
        return view('incidents.create');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'title'         => 'required|string|max:100',
            'category'      => 'required|string',
            'location'      => 'required|string|max:255',
            'description'   => 'required|string|min:10',
            'priority'      => 'required|in:basse,moyenne,haute',
            'photo_path'    => 'nullable|array|max:5',
            'photo_path.*'  => 'image|max:2048',
            'other_details' => 'nullable|string|max:100',
        ]);

        // Anti-doublon 24h
        $alreadyExists = Incident::where('user_id', auth()->id())
            ->where('category', $request->category)
            ->where('title', $request->title)
            ->where('created_at', '>', now()->subDay())
            ->exists();

        if ($alreadyExists) {
            return redirect()->back()->withInput()->with('error', '⚠️ Signalement déjà enregistré il y a moins de 24h.');
        }

        $paths = [];
        if ($request->hasFile('photo_path')) {
            foreach ($request->file('photo_path') as $image) {
                // Stockage physique dans storage/app/public/incidents
                $paths[] = $image->store('incidents', 'public');
            }
        }

        auth()->user()->incidents()->create([
            'title' => $request->title,
            'category' => $request->category,
            'location' => $request->location,
            'description' => $request->description,
            'priority' => $request->priority,
            'other_details' => $request->other_details,
            'photo_path' => $paths, // Sera casté en JSON
            'status' => 'en_attente',
        ]);

        return redirect()->route('dashboard')->with('success', 'Signalement envoyé avec succès !');
    }

    public function update(Request $request, Incident $incident)
    {
        $request->validate([
            'status' => 'required|in:en_attente,en_cours,resolu',
            'internal_notes' => 'nullable|string|max:255'
        ]);

        $incident->update($request->only(['status', 'internal_notes']));

        return redirect()->back()->with('success', 'Incident mis à jour.');
    }

    public function destroy(Incident $incident)
    {
        if($incident->photo_path) {
            foreach($incident->photo_path as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        $incident->delete();
        return redirect()->back()->with('success', 'Incident supprimé.');
    }
}