@section('title', 'Tableau de bord')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord des incidents') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Messages d'alerte --}}
            @if (session('success'))
                <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-100 shadow-sm animate__animated animate__fadeIn" role="alert">
                    <span class="font-bold">Succès !</span> {{ session('success') }}
                </div>
            @endif

            {{-- En-tête de section --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Mes Signalements</h2>
                    <p class="text-sm text-slate-500">Suivez l'état de vos demandes et les interventions du syndic.</p>
                </div>
                <a href="{{ route('incidents.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nouveau signalement
                </a>
            </div>

            @if($incidents->isEmpty())
                <div class="bg-white p-16 rounded-[2rem] text-center shadow-sm border border-slate-100">
                    <div class="mb-4 flex justify-center">
                        <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 21h18M4 21V5a2 2 0 012-2h5v18M15 21V9a2 2 0 012-2h3v14M8 9h2m-2 4h2m-2 4h2"/></svg>
                    </div>
                    <p class="text-slate-500 font-medium text-lg">Aucun incident signalé pour le moment.</p>
                    <p class="text-slate-400 text-sm">Tout semble être en ordre dans votre résidence.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($incidents as $incident)
                        <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 flex flex-col group">
                            
                            {{-- Image et Badge de Priorité --}}
                            <div class="relative h-44 overflow-hidden">
                                @if($incident->photo_path)
                                    @php
                                        $firstPhoto = is_array($incident->photo_path) ? ($incident->photo_path[0] ?? null) : $incident->photo_path;
                                    @endphp
                                        @if($firstPhoto)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($firstPhoto) }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @else
                                        <div class="h-full w-full bg-slate-200 flex items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12 opacity-20" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                @else
                                    <div class="h-full w-full bg-slate-200 flex items-center justify-center text-slate-400">
                                        <svg class="w-12 h-12 opacity-20" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                
                                <div class="absolute top-4 left-4">
                                    @php
                                        $statusClasses = [
                                            'en_attente' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'en_cours' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'resolu' => 'bg-green-100 text-green-700 border-green-200'
                                        ];
                                        $statusLabels = [
                                            'en_attente' => 'En attente',
                                            'en_cours' => 'Intervention...',
                                            'resolu' => 'Terminé'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-full border shadow-sm {{ $statusClasses[$incident->status] ?? 'bg-slate-100' }}">
                                        {{ $statusLabels[$incident->status] ?? $incident->status }}
                                    </span>
                                </div>
                            </div>

                            {{-- Contenu --}}
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wider">
                                        {{ ucfirst($incident->category) }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 ml-auto">
                                        {{ $incident->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <h3 class="font-extrabold text-lg text-slate-900 mb-2 leading-tight">
                                    {{ $incident->title }}
                                </h3>
                                
                                <p class="text-slate-500 text-sm line-clamp-2 mb-4 italic">
                                    "{{ $incident->description }}"
                                </p>

                                {
                                @if($incident->admin_comment)
                                    <div class="mt-auto mb-4 p-3 bg-indigo-50 border-l-4 border-indigo-400 rounded-r-xl">
                                        <p class="text-[11px] font-bold text-indigo-700 uppercase mb-1">Réponse du syndic :</p>
                                        <p class="text-xs text-indigo-900 leading-relaxed">{{ $incident->admin_comment }}</p>
                                    </div>
                                @endif

                                <div class="flex items-center pt-4 border-t border-slate-50 text-slate-400 text-[11px] font-medium mt-auto">
                                    <span class="flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        Appartement {{ $incident->location }}
                                    </span>
                                    
                                    <span class="ml-auto flex items-center">
                                        <div class="w-2 h-2 rounded-full mr-1.5 {{ $incident->priority == 'haute' ? 'bg-red-500' : ($incident->priority == 'moyenne' ? 'bg-orange-400' : 'bg-green-400') }}"></div>
                                        Urgence {{ $incident->priority }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
