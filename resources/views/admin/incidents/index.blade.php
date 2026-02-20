<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-black text-2xl text-slate-800 tracking-tight">
                    {{ __('Console de Gestion Syndic') }}
                </h2>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Immeuble "Le Mirage" — Administration</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.users.index') }}" class="bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-2xl text-xs font-black shadow-sm hover:bg-slate-50 transition flex items-center gap-2">
                    👥 USERS
                </a>
                {{-- Correction : La route export globale ne doit pas avoir d'ID --}}
                <a href="{{ route('admin.incidents.export') }}" class="bg-red-600 text-white px-5 py-2 rounded-2xl text-xs font-black shadow-lg shadow-red-200 hover:bg-red-700 transition flex items-center gap-2">
                    📥 EXPORTER TOUS
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- 1. ANALYTICS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                @foreach(['en_attente' => ['amber', 'À Traiter'], 'en_cours' => ['indigo', 'En Travaux'], 'resolu' => ['emerald', 'Résolus']] as $key => $val)
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <div class="flex justify-between items-end mb-4">
                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">{{ $val[1] }}</span>
                        <span class="text-3xl font-black text-slate-800">{{ $stats[$key] ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        @php $percent = ($stats['total'] ?? 0) > 0 ? (($stats[$key] ?? 0) / $stats['total']) * 100 : 0; @endphp
                        <div class="bg-{{ $val[0] }}-500 h-full rounded-full transition-all duration-1000 shadow-sm" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
<div class="mb-8">
    <form action="{{ route('admin.incidents.index') }}" method="GET" class="flex flex-wrap gap-4 items-center bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
        <div class="flex-1 min-w-[300px] relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Rechercher un nom, un appartement, un problème..." 
                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl text-xs font-black shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">
                FILTRER
            </button>
            
            @if(request('search'))
                <a href="{{ route('admin.incidents.index') }}" class="bg-slate-100 text-slate-500 px-6 py-3 rounded-2xl text-xs font-black hover:bg-slate-200 transition">
                    ANNULER
                </a>
            @endif
        </div>
    </form>
</div>

            {{-- 2. MESSAGES --}}
            @if (session('success'))
                <div class="mb-8 p-4 bg-emerald-500 text-white rounded-2xl shadow-lg font-bold text-sm animate-pulse">
                    ✨ {{ session('success') }}
                </div>
            @endif

            {{-- 3. LISTE DES INCIDENTS --}}
            <div class="space-y-8">
                @forelse($incidents as $incident)
                <div class="bg-white rounded-[3rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden hover:scale-[1.01] transition-transform duration-300">
                    <div class="flex flex-col md:flex-row">
                        
                        {{-- PHOTO : Correction de image_path vers photo_path --}}
                        <div class="md:w-72 h-64 md:h-auto bg-slate-100 relative group overflow-hidden">
                            @if($incident->photo_path)
                                <img src="{{ asset('storage/' . $incident->photo_path) }}" 
                                     onclick="openModal(this.src)"
                                     class="w-full h-full object-cover cursor-zoom-in transition group-hover:scale-110 duration-500">
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center pointer-events-none">
                                    <span class="text-white font-black text-xs uppercase tracking-widest border-2 border-white px-4 py-2 rounded-full">Agrandir</span>
                                </div>
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-[10px] font-black uppercase">Pas d'image</span>
                                </div>
                            @endif
                            <div class="absolute top-6 left-6">
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black shadow-xl {{ $incident->priority == 'haute' ? 'bg-red-600 text-white' : 'bg-sky-500 text-white' }}">
                                    🚨 {{ strtoupper($incident->priority) }}
                                </span>
                            </div>
                        </div>

                        {{-- CONTENU --}}
                        <div class="flex-1 p-8 md:p-10">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-2xl font-black text-slate-800 tracking-tight mb-1">{{ $incident->title }}</h3>
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                                        <p class="text-indigo-600 font-black text-[11px] uppercase tracking-tighter">
                                            Lieu : {{ $incident->location }} — {{ $incident->user->name }}
                                        </p>
                                    </div>
                                </div>
                                <span class="text-[10px] text-slate-400 font-bold bg-slate-50 px-3 py-1 rounded-full uppercase">{{ $incident->created_at->format('d M Y') }}</span>
                            </div>
                            
                            <p class="text-slate-500 text-sm leading-relaxed mb-8 italic border-l-4 border-slate-100 pl-4">
                                "{{ $incident->description }}"
                            </p>

                            {{-- ACTIONS --}}
                            <form action="{{ route('admin.incidents.update', $incident) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100">
                                @csrf @method('PATCH')
                                
                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase mb-2 ml-1">Statut Dossier</label>
                                    <select name="status" class="w-full text-xs font-black border-none rounded-xl bg-white shadow-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                        <option value="en_attente" {{ $incident->status == 'en_attente' ? 'selected' : '' }}>⌛ EN ATTENTE</option>
                                        <option value="en_cours" {{ $incident->status == 'en_cours' ? 'selected' : '' }}>🔧 TRAVAUX</option>
                                        <option value="resolu" {{ $incident->status == 'resolu' ? 'selected' : '' }}>✅ RÉSOLU</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase mb-2 ml-1">Message au Locataire</label>
                                    <input type="text" name="admin_comment" value="{{ $incident->admin_comment }}" 
                                           placeholder="Visible par l'habitant..."
                                           class="w-full text-xs font-bold border-none rounded-xl bg-white shadow-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>

                                <div class="flex gap-2 items-end">
                                    <div class="flex-1">
                                        <label class="block text-[9px] font-black text-slate-400 uppercase mb-2 ml-1 text-indigo-500">Note Interne</label>
                                        <input type="text" name="internal_notes" value="{{ $incident->internal_notes }}" 
                                               placeholder="Privé..."
                                               class="w-full text-xs font-bold border-dashed border-indigo-200 rounded-xl bg-indigo-50/30 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                    </div>
                                    <button type="submit" class="bg-slate-900 text-white p-3 rounded-xl hover:bg-black transition shadow-lg group">
                                        <svg class="w-5 h-5 group-hover:rotate-12 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </button>

                                    {{-- Correction : La route pour un rapport unique --}}
                                    <a href="{{ route('admin.incidents.report', $incident->id) }}" class="bg-red-600 text-white p-3 rounded-xl hover:bg-red-700 transition shadow-lg text-xs flex items-center">
                                        📥
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-24 bg-white rounded-[3rem] border border-dashed border-slate-200">
                    <p class="text-slate-400 font-black uppercase tracking-widest">Aucun incident à signaler</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    {{-- MODAL IMAGE (Inchangé mais vérifié) --}}
    <div id="imageModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/95 backdrop-blur-xl p-4" onclick="closeModal()">
        <img id="modalImage" src="" class="max-w-full max-h-[85vh] rounded-[2rem] shadow-2xl border-4 border-white/20 transform scale-95 transition-all duration-300">
    </div>

    <script>
        function openModal(src) {
            const m = document.getElementById('imageModal');
            const i = document.getElementById('modalImage');
            i.src = src;
            m.classList.remove('hidden');
            setTimeout(() => i.classList.replace('scale-95', 'scale-100'), 10);
        }
        function closeModal() {
            const m = document.getElementById('imageModal');
            const i = document.getElementById('modalImage');
            i.classList.replace('scale-100', 'scale-95');
            setTimeout(() => m.classList.add('hidden'), 200);
        }
    </script>
</x-app-layout>