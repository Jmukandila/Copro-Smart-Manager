{{-- <!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #14b8a6; padding-bottom: 10px; }
        .content { margin-top: 20px; }
        .label { font-weight: bold; color: #14b8a6; }
        .photo { margin-top: 10px; width: 100%; max-width: 400px; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport de Signalement #{{ $incident->id }}</h1>
        <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="content">
        <p><span class="label">Utilisateur :</span> {{ optional($incident->user)->name ?? '—' }}</p>
        <p><span class="label">Titre :</span> {{ $incident->title }}</p>
        <p><span class="label">Catégorie :</span> {{ $incident->category }}</p>
        <p><span class="label">Lieu/Appartement :</span> {{ $incident->location }}</p>
        <p><span class="label">Priorité :</span> {{ ucfirst($incident->priority) }}</p>
        <hr>
        <p><span class="label">Description :</span></p>
        <p>{{ $incident->description }}</p>

        @if($incident->photo_path)
            <div style="text-align: center;">
                <p class="label">Photo jointe :</p>
                <img src="{{ public_path('storage/' . $incident->photo_path) }}" class="photo" alt="photo">
            </div>
        @endif
    </div>
</body>
</html> --}}
