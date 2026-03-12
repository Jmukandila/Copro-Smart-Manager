<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

        $incidents = $query
            ->orderByRaw("CASE priority WHEN 'haute' THEN 1 WHEN 'moyenne' THEN 2 WHEN 'basse' THEN 3 ELSE 4 END")
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        $stats = [
            'total'      => Incident::count(),
            'en_attente' => Incident::where('status', 'en_attente')->count(),
            'en_cours'   => Incident::where('status', 'en_cours')->count(),
            'resolu'     => Incident::where('status', 'resolu')->count(),
        ];

        // Correction de l'erreur compact('')
        $view = auth()->user()->isAdmin() ? 'admin.incidents.index' : 'dashboard';
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
            'admin_comment'  => 'nullable|string|max:2000',
        ]);

        $incident->update($request->only(['status', 'internal_notes', 'admin_comment']));
        return back()->with('success', 'L\'incident a été mis à jour.');
    }

    public function aiReply(Request $request, Incident $incident)
    {
        $messages = [
            [
                'role' => 'system',
                'content' => 'Tu es l assistant CoproSmart côté syndic. '
                    . 'Génère une réponse courte, professionnelle et rassurante pour le locataire. '
                    . 'Retourne EXCLUSIVEMENT un JSON: '
                    . '{"admin_comment":"...", "suggested_priority":"basse|moyenne|haute"}',
            ],
            [
                'role' => 'user',
                'content' => 'Incident: '
                    . 'Titre: ' . $incident->title . '. '
                    . 'Catégorie: ' . $incident->category . '. '
                    . 'Lieu: ' . $incident->location . '. '
                    . 'Priorité actuelle: ' . $incident->priority . '. '
                    . 'Description: ' . Str::limit($incident->description, 1200) . '. '
                    . 'Locataire: ' . ($incident->user->name ?? 'Résident') . '.',
            ],
        ];

        $aiContent = $this->callGroq($messages);
        $decoded = json_decode($aiContent, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return response()->json([
                'admin_comment' => $decoded['admin_comment'] ?? '',
                'suggested_priority' => $decoded['suggested_priority'] ?? null,
                'raw' => $aiContent,
            ]);
        }

        return response()->json([
            'admin_comment' => $aiContent,
            'suggested_priority' => null,
            'raw' => $aiContent,
        ]);
    }

    public function aiDigest()
    {
        $start = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $end = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $weekly = Incident::whereBetween('created_at', [$start, $end])->get();

        $stats = [
            'total' => $weekly->count(),
            'resolu' => $weekly->where('status', 'resolu')->count(),
            'en_cours' => $weekly->where('status', 'en_cours')->count(),
            'en_attente' => $weekly->where('status', 'en_attente')->count(),
        ];

        $topCategory = $weekly->groupBy('category')->sortByDesc(fn($g) => $g->count())->keys()->first();

        $messages = [
            [
                'role' => 'system',
                'content' => 'Tu es l assistant CoproSmart côté syndic. '
                    . 'Rédige un digest court (3-4 phrases max) en français, ton professionnel. '
                    . 'Retourne EXCLUSIVEMENT un JSON: {"digest":"..."}',
            ],
            [
                'role' => 'user',
                'content' => 'Semaine: ' . $start->format('d/m/Y') . ' au ' . $end->format('d/m/Y') . '. '
                    . 'Total: ' . $stats['total'] . '. '
                    . 'Résolus: ' . $stats['resolu'] . '. '
                    . 'En cours: ' . $stats['en_cours'] . '. '
                    . 'En attente: ' . $stats['en_attente'] . '. '
                    . 'Catégorie principale: ' . ($topCategory ?? 'aucune') . '.',
            ],
        ];

        $aiContent = $this->callGroq($messages);
        $decoded = json_decode($aiContent, true);

        return response()->json([
            'digest' => (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
                ? ($decoded['digest'] ?? $aiContent)
                : $aiContent,
            'period' => [
                'start' => $start->toDateString(),
                'end' => $end->toDateString(),
            ],
        ]);
    }

    private function callGroq(array $messages): string
    {
        try {
            $response = Http::withToken(env('GROQ_API_KEY'))
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => $messages,
                ]);

            if (! $response->successful()) {
                return 'Le service IA est momentanement indisponible.';
            }

            $choices = $response->json('choices');
            return $choices[0]['message']['content'] ?? 'Reponse IA indisponible.';
        } catch (\Throwable $e) {
            return 'Le service IA est momentanement indisponible.';
        }
    }
}
