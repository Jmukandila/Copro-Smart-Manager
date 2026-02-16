<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function ask(Request $request)
    {
        $userMessage = $request->input('message');

        // Appel à l'API Groq
        $response = Http::withToken(env('GROQ_API_KEY'))
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'system', 
                        'content' => 'Tu es l assistant CoproSmart. Tu dois aider l utilisateur. 
                        Si l utilisateur décrit un incident, réponds EXCLUSIVEMENT sous ce format JSON :
                        {"reply": "Ton message d aide ici", "data": {"category": "plomberie|electricite|ascenseur|securite|chauffage", "title": "...", "location": "...", "description": "..."}}'
                    ],
                    ['role' => 'user', 'content' => $userMessage],
                ],
            ]);

        // On récupère le contenu de la réponse de l'IA
        $aiContent = $response->json()['choices'][0]['message']['content'] ?? 'Désolé, je rencontre une petite erreur technique.';

        return response()->json([
            'reply' => $aiContent
        ]);
    }
}