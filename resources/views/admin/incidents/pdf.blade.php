<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #14b8a6; padding-bottom: 10px; }
        .incident { margin-top: 20px; }
        .label { font-weight: bold; color: #14b8a6; }
        .photo { margin-top: 10px; width: 100%; max-width: 400px; border-radius: 6px; }
        .separator { border-top: 1px dashed #ccc; margin: 15px 0; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport des Signalements</h1>
        <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>
        <p>Total : {{ $incidents->count() }} signalement(s)</p>
    </div>

    @foreach($incidents as $incident)
        <div class="incident">
            <p><span class="label">ID :</span> {{ $incident->id }}</p>
            <p><span class="label">Utilisateur :</span> {{ optional($incident->user)->name ?? '—' }}</p>
            <p><span class="label">Titre :</span> {{ $incident->title }}</p>
            <p><span class="label">Catégorie :</span> {{ $incident->category }}</p>
            <p><span class="label">Lieu/Appartement :</span> {{ $incident->location }}</p>
            <p><span class="label">Priorité :</span> {{ ucfirst($incident->priority) }}</p>
            <div class="separator"></div>
            <p><span class="label">Description :</span></p>
            <p>{{ $incident->description }}</p>

            @if($incident->photo_path)
                <div style="text-align: center;">
                    <p class="label">Photo jointe :</p>
                    <img src="{{ public_path('storage/' . $incident->photo_path) }}" class="photo" alt="photo">
                </div>
            @endif
        </div>

        @if(! $loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>