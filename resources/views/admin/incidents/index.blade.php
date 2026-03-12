@section('title', 'Console syndic')
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
                    <a href="{{ route('admin.users.index') }}" class="flex-1 sm:flex-none bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-2xl text-[10px] font-black shadow-sm hover:shadow-md transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m8-4.13a4 4 0 11-8 0 4 4 0 018 0zm-8 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        UTILISATEURS
                    </a>
                    <a href="{{ route('admin.incidents.export') }}" class="flex-1 sm:flex-none bg-red-600 text-white px-4 py-2 rounded-2xl text-[10px] font-black shadow-lg shadow-red-200 hover:bg-red-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        EXPORT GLOBAL
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- TOAST NOTIFICATION --}}
    @if (session('success'))
        <div id="status-toast" class="fixed top-6 left-1/2 -translate-x-1/2 z-[110] animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="bg-slate-900/90 backdrop-blur-md text-white px-6 py-2.5 rounded-full shadow-2xl border border-white/10 flex items-center gap-3">
                <span class="text-[11px] font-black">{{ session('success') }}</span>
                <button onclick="document.getElementById('status-toast').remove()" class="text-slate-400 hover:text-white">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
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

            {{-- DIGEST IA --}}
            <div class="mb-12 bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <div class="flex-1">
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Résumé des Incidents</h3>
                        <p class="text-xs text-slate-500 mt-1">Cliquez sur le bouton noir a votre droite pour générer un résumé automatique des incidents de la semaine.</p>
                        <div id="digest-output" class="mt-4 text-sm text-slate-700 italic"></div>
                    </div>
                    <button id="generate-digest" data-url="{{ route('admin.incidents.digest') }}" class="bg-slate-900 text-white px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition">
                        Generer le Résumé
                    </button>
                </div>
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
                                    <svg class="w-3.5 h-3.5 inline-block -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.33 16a2 2 0 001.74 3z"/></svg>
                                    {{ strtoupper($incident->priority) }}
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
                                    <p class="text-indigo-500 text-[11px] font-black uppercase mt-2 tracking-wider">
                                        <svg class="w-3.5 h-3.5 inline-block -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a8 8 0 00-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 00-8-8z"/></svg>
                                        {{ $incident->location }}
                                    </p>
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
                                            <option value="en_attente" {{ $incident->status == 'en_attente' ? 'selected' : '' }}>EN ATTENTE</option>
                                            <option value="en_cours" {{ $incident->status == 'en_cours' ? 'selected' : '' }}>TRAVAUX EN COURS</option>
                                            <option value="resolu" {{ $incident->status == 'resolu' ? 'selected' : '' }}>DOSSIER RESOLU</option>
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
                                    <div class="sm:col-span-2">
                                        <label class="block text-[8px] font-black text-emerald-500 uppercase mb-2 ml-1 tracking-widest">Reponse Au Locataire</label>
                                        <textarea id="admin-comment-{{ $incident->id }}" name="admin_comment" rows="2" placeholder="Message au locataire..." class="w-full text-[10px] font-bold border-dashed border-emerald-200 rounded-xl bg-emerald-50/30">{{ $incident->admin_comment }}</textarea>
                                        <div class="mt-2 flex items-center gap-2">
                                            <button type="button" class="ai-reply-btn bg-white border border-slate-200 text-slate-700 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:border-indigo-300 hover:text-indigo-600 transition" data-url="{{ route('admin.incidents.aiReply', $incident) }}" data-incident="{{ $incident->id }}">
                                                Proposer Reponse IA
                                            </button>
                                            <span id="ai-priority-{{ $incident->id }}" class="text-[9px] font-black uppercase text-slate-400"></span>
                                        </div>
                                    </div>
                                </form>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.incidents.report', $incident->id) }}" title="Télécharger le rapport" class="bg-white border border-slate-200 text-slate-400 p-2.5 rounded-xl hover:text-red-500 hover:border-red-200 transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.incidents.destroy', $incident) }}" method="POST" class="js-confirm" data-confirm="Attention ! Cette action est irreversible. Supprimer l'incident ?">
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

        
            <div class="mt-12 mb-10 flex justify-center">
                {{ $incidents->links() }}
            </div>
        </div>
    </div>

    <div id="imageModal" class="fixed inset-0 z-[150] hidden flex items-center justify-center bg-slate-950/95 backdrop-blur-xl p-4" onclick="closeModal()">
        <img id="modalImage" src="" class="max-w-full max-h-[85vh] rounded-3xl shadow-2xl border border-white/10 transform scale-95 transition-all duration-300">
        <div class="absolute bottom-10 text-white/50 text-[10px] font-bold uppercase tracking-widest">Cliquez n'importe où pour fermer</div>
    </div>

    <div id="confirm-modal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-slate-900/70 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-md p-6">
            <div class="flex items-start gap-3">
                <div class="h-10 w-10 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.33 16a2 2 0 001.74 3z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-black text-slate-800">Confirmation</p>
                    <p id="confirm-message" class="text-xs text-slate-500 mt-1"></p>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button id="confirm-cancel" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest bg-slate-100 text-slate-700 hover:bg-slate-200">Annuler</button>
                <button id="confirm-ok" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest bg-red-600 text-white hover:bg-red-700">Supprimer</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(src) {
            const m = document.getElementById('imageModal');
            const i = document.getElementById('modalImage');
            i.src = src;
            m.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => i.classList.replace('scale-95', 'scale-100'), 10);
        }
        function closeModal() {
            const m = document.getElementById('imageModal');
            const i = document.getElementById('modalImage');
            i.classList.replace('scale-100', 'scale-95');
            document.body.style.overflow = 'auto';
            setTimeout(() => m.classList.add('hidden'), 200);
        }

        const digestBtn = document.getElementById('generate-digest');
        const digestOutput = document.getElementById('digest-output');
        digestBtn?.addEventListener('click', async () => {
            digestBtn.disabled = true;
            digestBtn.textContent = 'IA en cours...';
            try {
                const res = await fetch(digestBtn.dataset.url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });
                const data = await res.json();
                digestOutput.textContent = data.digest || 'Digest indisponible.';
            } catch (e) {
                digestOutput.textContent = 'Digest indisponible.';
            } finally {
                digestBtn.disabled = false;
                digestBtn.textContent = 'Generer Digest IA';
            }
        });

        document.querySelectorAll('.ai-reply-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.dataset.incident;
                const textarea = document.getElementById(`admin-comment-${id}`);
                const priorityBadge = document.getElementById(`ai-priority-${id}`);
                btn.disabled = true;
                btn.textContent = 'IA...';
                try {
                    const res = await fetch(btn.dataset.url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    });
                    const data = await res.json();
                    if (textarea) textarea.value = data.admin_comment || '';
                    if (priorityBadge) {
                        priorityBadge.textContent = data.suggested_priority ? `Priorite suggeree: ${data.suggested_priority}` : '';
                    }
                } catch (e) {
                    if (priorityBadge) priorityBadge.textContent = 'IA indisponible';
                } finally {
                    btn.disabled = false;
                    btn.textContent = 'Proposer Reponse IA';
                }
            });
        });

        const confirmModal = document.getElementById('confirm-modal');
        const confirmMessage = document.getElementById('confirm-message');
        const confirmOk = document.getElementById('confirm-ok');
        const confirmCancel = document.getElementById('confirm-cancel');
        let pendingForm = null;

        document.querySelectorAll('form.js-confirm').forEach((form) => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                pendingForm = form;
                confirmMessage.textContent = form.dataset.confirm || 'Confirmer cette action ?';
                confirmModal.classList.remove('hidden');
                confirmModal.classList.add('flex');
            });
        });

        confirmCancel?.addEventListener('click', () => {
            confirmModal.classList.add('hidden');
            confirmModal.classList.remove('flex');
            pendingForm = null;
        });

        confirmOk?.addEventListener('click', () => {
            if (pendingForm) pendingForm.submit();
        });

    </script>
</x-app-layout>
