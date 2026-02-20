<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function ask(Request $request)
    {
        $userMessage = $request->input('message');
        // Appel à l'API Groq avec gestion d'erreurs
        try {
            $response = Http::withToken(env('GROQ_API_KEY'))
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Tu es l assistant CoproSmart. Tu dois aider l utilisateur. '
                                . 'Si l utilisateur décrit un incident, réponds EXCLUSIVEMENT sous ce format JSON : '
                                . '{"reply": "Ton message d aide ici", "data": {"category": "plomberie|electricite|ascenseur|securite|chauffage", "title": "...", "location": "...", "description": "..."}}'
                        ],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                ]);

            if (!$response->successful()) {
                $aiContent = 'Désolé, l\'assistant est momentanément indisponible.';
            } else {
                $choices = $response->json('choices');
                $aiContent = $choices[0]['message']['content'] ?? 'Désolé, je rencontre une petite erreur technique.';
            }
        } catch (\Throwable $e) {
            // En cas d'erreur réseau ou d'exception, on renvoie un message lisible côté client
            $aiContent = 'Désolé, je rencontre une erreur technique. Réessayez plus tard.';
        }

        // Si l'IA renvoie du texte brut, on encapsule la réponse dans une chaîne JSON
        $decoded = json_decode($aiContent, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $reply = $aiContent; // L'IA a renvoyé un JSON (sous forme de chaîne) — on le propage tel quel
        } else {
            $reply = json_encode([
                'reply' => $aiContent,
                'data' => null,
            ]);
        }

        return response()->json([
            'reply' => $reply,
        ]);
    }
}