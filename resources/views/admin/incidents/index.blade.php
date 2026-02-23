<x-app-layout>
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-3xl text-slate-800 tracking-tighter">{{ __('Console Syndic') }}</h2>
                <p class="text-[10px] text-indigo-500 font-black uppercase tracking-[0.2em] mt-1 flex items-center gap-2">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                    Immeuble "Le Mirage" — Live Admin
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                {{-- RECHERCHE --}}
                <form action="{{ route('admin.incidents.index') }}" method="GET" class="relative group w-full sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un dossier..." 
                           class="w-full pl-10 pr-4 py-2 bg-white border-none rounded-2xl text-xs font-bold shadow-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                </form>

                <div class="flex gap-2 w-full sm:w-auto">
                    <a href="{{ route('admin.users.index') }}" class="flex-1 sm:flex-none bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-2xl text-[10px] font-black shadow-sm hover:shadow-md transition flex items-center justify-center gap-2">👥 UTILISATEURS</a>
                    <a href="{{ route('admin.incidents.export') }}" class="flex-1 sm:flex-none bg-red-600 text-white px-4 py-2 rounded-2xl text-[10px] font-black shadow-lg shadow-red-200 hover:bg-red-700 transition flex items-center justify-center gap-2">📥 EXPORT GLOBAL</a>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- TOAST NOTIFICATION --}}
    @if (session('success'))
        <div id="status-toast" class="fixed top-6 left-1/2 -translate-x-1/2 z-[110] animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="bg-slate-900/90 backdrop-blur-md text-white px-6 py-2.5 rounded-full shadow-2xl border border-white/10 flex items-center gap-3">
                <span class="text-[11px] font-black">✨ {{ session('success') }}</span>
                <button onclick="document.getElementById('status-toast').remove()" class="text-slate-400 hover:text-white">✕</button>
            </div>
        </div>
        <script>setTimeout(() => { document.getElementById('status-toast')?.remove() }, 4000);</script>
    @endif

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- STATS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                @foreach(['en_attente' => ['amber', 'Attente'], 'en_cours' => ['indigo', 'En Travaux'], 'resolu' => ['emerald', 'Terminés']] as $key => $val)
                <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-{{ $val[0] }}-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest">{{ $val[1] }}</span>
                            <span class="bg-{{ $val[0] }}-100 text-{{ $val[0] }}-600 text-[10px] font-black px-2 py-0.5 rounded-lg">
                                {{ ($stats['total'] ?? 0) > 0 ? round((($stats[$key] ?? 0) / $stats['total']) * 100) : 0 }}%
                            </span>
                        </div>
                        <span class="text-4xl font-black text-slate-800">{{ $stats[$key] ?? 0 }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- LISTE DES INCIDENTS --}}
            <div class="grid grid-cols-1 gap-8">
                @forelse($incidents as $incident)
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-white overflow-hidden group transition-all duration-300 hover:border-indigo-100">
                    <div class="flex flex-col lg:flex-row">
                        
                        {{-- GALERIE PHOTO --}}
                        <div class="lg:w-80 bg-slate-100 relative">
                            @if($incident->photo_path && count($incident->photo_path) > 0)
                                <div class="flex overflow-x-auto snap-x snap-mandatory scrollbar-hide h-72 lg:h-full">
                                    @foreach($incident->photo_path as $path)
                                        <div class="flex-none w-full h-full snap-center overflow-hidden">
                                            <img src="{{ asset('storage/' . $path) }}" 
                                                 onclick="openModal(this.src)" 
                                                 class="w-full h-full object-cover cursor-zoom-in hover:scale-110 transition duration-700">
                                        </div>
                                    @endforeach
                                </div>
                                <div class="absolute bottom-4 right-4 bg-black/60 backdrop-blur-md text-white px-2 py-1 rounded-lg text-[10px] font-black">
                                    {{ count($incident->photo_path) }} PHOTOS
                                </div>
                            @else
                                <div class="w-full h-72 lg:h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                    <svg class="w-12 h-12 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-[10px] font-black uppercase tracking-tighter">Aucun visuel</span>
                                </div>
                            @endif

                            {{-- BADGE PRIORITÉ --}}
                            <div class="absolute top-6 left-6 z-10">
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black shadow-xl {{ $incident->priority == 'haute' ? 'bg-red-600 text-white animate-pulse' : 'bg-sky-500 text-white' }}">
                                    🚨 {{ strtoupper($incident->priority) }}
                                </span>
                            </div>
                        </div>

                        {{-- INFOS & ACTIONS --}}
                        <div class="flex-1 p-8 lg:p-10 flex flex-col">
                            <div class="flex flex-wrap justify-between items-start mb-6 gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-500 text-[8px] font-black rounded uppercase">ID #{{ $incident->id }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $incident->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <h3 class="text-2xl font-black text-slate-800 leading-none">{{ $incident->title }}</h3>
                                    <p class="text-indigo-500 text-[11px] font-black uppercase mt-2 tracking-wider">📍 {{ $incident->location }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-slate-800">{{ $incident->user->name ?? 'Anonyme' }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Locataire Mirage</p>
                                </div>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-5 mb-8 border border-slate-100 text-sm font-medium text-slate-600 italic">
                                "{{ $incident->description }}"
                            </div>

                            {{-- ACTIONS FORM --}}
                            <div class="mt-auto pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-end gap-4">
                                <form action="{{ route('admin.incidents.update', $incident) }}" method="POST" class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                                    @csrf @method('PATCH')
                                    <div>
                                        <label class="block text-[8px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Modifier Statut</label>
                                        <select name="status" class="w-full text-[10px] font-black border-slate-200 rounded-xl bg-slate-50 focus:ring-indigo-500 transition-all">
                                            <option value="en_attente" {{ $incident->status == 'en_attente' ? 'selected' : '' }}>⌛ EN ATTENTE</option>
                                            <option value="en_cours" {{ $incident->status == 'en_cours' ? 'selected' : '' }}>🔧 TRAVAUX EN COURS</option>
                                            <option value="resolu" {{ $incident->status == 'resolu' ? 'selected' : '' }}>✅ DOSSIER RÉSOLU</option>
                                        </select>
                                    </div>
                                    <div class="flex gap-2 items-end">
                                        <div class="flex-1">
                                            <label class="block text-[8px] font-black text-indigo-400 uppercase mb-2 ml-1 tracking-widest">Note Interne</label>
                                            <input type="text" name="internal_notes" value="{{ $incident->internal_notes }}" placeholder="R.A.S" class="w-full text-[10px] font-bold border-dashed border-indigo-200 rounded-xl bg-indigo-50/30">
                                        </div>
                                        <button type="submit" title="Sauvegarder" class="bg-slate-900 text-white p-2.5 rounded-xl hover:bg-indigo-600 transition-all shadow-md active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </div>
                                </form>

                                {{-- AUTRES ACTIONS (PDF & DELETE) --}}
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.incidents.report', $incident->id) }}" title="Télécharger le rapport" class="bg-white border border-slate-200 text-slate-400 p-2.5 rounded-xl hover:text-red-500 hover:border-red-200 transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.incidents.destroy', $incident) }}" method="POST" onsubmit="return confirm('Attention ! Cette action est irréversible. Supprimer l\'incident ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Supprimer" class="bg-red-50 text-red-500 p-2.5 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-100 text-slate-400 font-black uppercase text-xs tracking-widest">
                    Aucun incident trouvé pour cette recherche
                </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="mt-12 mb-10 flex justify-center">
                {{ $incidents->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL IMAGE (Agrandissement) --}}
    <div id="imageModal" class="fixed inset-0 z-[150] hidden flex items-center justify-center bg-slate-950/95 backdrop-blur-xl p-4" onclick="closeModal()">
        <img id="modalImage" src="" class="max-w-full max-h-[85vh] rounded-3xl shadow-2xl border border-white/10 transform scale-95 transition-all duration-300">
        <div class="absolute bottom-10 text-white/50 text-[10px] font-bold uppercase tracking-widest">Cliquez n'importe où pour fermer</div>
    </div>

    <script>
        function openModal(src) {
            const m = document.getElementById('imageModal');
            const i = document.getElementById('modalImage');
            i.src = src;
            m.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Bloque le scroll arrière
            setTimeout(() => i.classList.replace('scale-95', 'scale-100'), 10);
        }
        function closeModal() {
            const m = document.getElementById('imageModal');
            const i = document.getElementById('modalImage');
            i.classList.replace('scale-100', 'scale-95');
            document.body.style.overflow = 'auto';
            setTimeout(() => m.classList.add('hidden'), 200);
        }
    </script>
</x-app-layout>