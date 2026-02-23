<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; font-size: 12px; line-height: 1.5; }
        .header { text-align: center; border-bottom: 3px solid #6366f1; padding-bottom: 15px; margin-bottom: 30px; }
        .header h1 { color: #1e293b; margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        
        .incident-card { margin-bottom: 30px; padding: 20px; border: 1px solid #f1f5f9; background: #fff; }
        .label { font-weight: bold; color: #6366f1; text-transform: uppercase; font-size: 10px; display: block; margin-top: 8px; }
        .value { color: #1e293b; font-size: 12px; margin-bottom: 5px; }
        
        .description-box { background: #f8fafc; padding: 15px; border-left: 4px solid #e2e8f0; margin: 15px 0; font-style: italic; }
        
        /* Grille d'images pour le PDF */
        .photo-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .photo-cell { width: 33.33%; padding: 5px; text-align: center; }
        .photo-container { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background-color: #f8fafc; }
        .photo-img { width: 100%; height: 150px; object-fit: cover; display: block; }
        
        .page-break { page-break-after: always; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport Global des Signalements</h1>
        <p>Immeuble "Le Mirage" — Généré le {{ now()->format('d/m/Y H:i') }}</p>
        <p><strong>Total : {{ $incidents->count() }} dossiers</strong></p>
    </div>

    @foreach($incidents as $incident)
        <div class="incident-card">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <span class="label">ID Incident</span>
                        <div class="value">#{{ $incident->id }}</div>
                        
                        <span class="label">Locataire</span>
                        <div class="value">{{ optional($incident->user)->name ?? 'Utilisateur inconnu' }}</div>
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <span class="label">Statut</span>
                        <div class="value" style="font-weight: bold;">{{ strtoupper($incident->status) }}</div>
                        
                        <span class="label">Date</span>
                        <div class="value">{{ $incident->created_at->format('d/m/Y') }}</div>
                    </td>
                </tr>
            </table>

            <span class="label">Problème & Lieu</span>
            <div class="value"><strong>{{ $incident->title }}</strong> — {{ $incident->location }} ({{ ucfirst($incident->category) }})</div>

            <div class="description-box">
                "{{ $incident->description }}"
            </div>

            {{-- Galerie d'images alignée --}}
            @if($incident->photo_path && count($incident->photo_path) > 0)
                <span class="label">Pièces Jointes ({{ count($incident->photo_path) }})</span>
                <table class="photo-table">
                    <tr>
                        @foreach($incident->photo_path as $index => $path)
                            @if($index > 0 && $index % 3 == 0)
                                </tr><tr>
                            @endif
                            <td class="photo-cell">
                                <div class="photo-container">
                                    <img src="{{ public_path('storage/' . $path) }}" class="photo-img">
                                </div>
                            </td>
                        @endforeach
                        
                        {{-- Cellules vides pour maintenir l'alignement --}}
                        @for ($i = count($incident->photo_path) % 3; $i < 3 && $i != 0; $i++)
                            <td class="photo-cell"></td>
                        @endfor
                    </tr>
                </table>
            @endif
        </div>

        @if(! $loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    <div class="footer">
        Document confidentiel — Gestion Syndic Connect
    </div>
</body>
</html>