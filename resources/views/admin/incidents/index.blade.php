<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Incidents - Administration') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Message de succès --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl shadow-sm animate__animated animate__fadeIn">
                    <span class="font-bold">Succès :</span> {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total</p>
                    <p class="text-3xl font-black text-slate-800">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-amber-50 p-6 rounded-[2rem] shadow-sm border border-amber-100">
                    <p class="text-xs font-bold text-amber-600 uppercase tracking-widest">En attente</p>
                    <p class="text-3xl font-black text-amber-700">{{ $stats['en_attente'] }}</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-[2rem] shadow-sm border border-blue-100">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">En cours</p>
                    <p class="text-3xl font-black text-blue-700">{{ $stats['en_cours'] }}</p>
                </div>
                <div class="bg-green-50 p-6 rounded-[2rem] shadow-sm border border-green-100">
                    <p class="text-xs font-bold text-green-600 uppercase tracking-widest">Résolus</p>
                    <p class="text-3xl font-black text-green-700">{{ $stats['resolu'] }}</p>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-[2rem] border border-slate-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="p-6 text-xs font-bold uppercase text-slate-400">Locataire & Incident</th>
                            <th class="p-6 text-xs font-bold uppercase text-slate-400">Détails</th>
                            <th class="p-6 text-xs font-bold uppercase text-slate-400">Action & Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($incidents as $incident)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-6">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                        {{ substr($incident->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $incident->user->name }}</div>
                                        <div class="text-xs text-slate-500 italic">Appartement {{ $incident->apartment_number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <div class="font-semibold text-slate-700">{{ $incident->title }}</div>
                                <div class="text-xs text-slate-400 line-clamp-1">{{ $incident->description }}</div>
                                <span class="inline-block mt-2 px-2 py-0.5 rounded text-[10px] font-bold {{ $incident->priority == 'haute' ? 'bg-red-100 text-red-600' : ($incident->priority == 'moyenne' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600') }}">
                                    🚨 {{ strtoupper($incident->priority) }}
                                </span>
                            </td>
                            <td class="p-6">
                                <form action="{{ route('admin.incidents.update', $incident) }}" method="POST" class="space-y-2">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="flex items-center gap-2">
                                        <select name="status" class="text-xs rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                                            <option value="en_attente" {{ $incident->status == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                            <option value="en_cours" {{ $incident->status == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                            <option value="resolu" {{ $incident->status == 'resolu' ? 'selected' : '' }}>Résolu</option>
                                        </select>
                                        
                                        <button type="submit" class="bg-indigo-600 text-white p-2 rounded-xl hover:bg-indigo-700 transition-all shadow-sm flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </div>
                                    
                                    <input type="text" name="admin_comment" value="{{ $incident->admin_comment }}" 
                                        placeholder="Note au locataire..." 
                                        class="w-full text-[11px] rounded-xl border-slate-100 bg-slate-50 placeholder-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-20 text-center text-slate-400">
                                <div class="text-5xl mb-4">🍃</div>
                                Aucun signalement reçu pour le moment.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>