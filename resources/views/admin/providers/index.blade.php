<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800">Gestion des Prestataires</h2>
    </x-slot>

    <div class="py-12 px-4 max-w-7xl mx-auto">
        <div class="bg-white rounded-[2rem] p-8 shadow-sm">
            <div class="flex justify-between items-center mb-8">
                <p class="text-slate-500">Liste des entreprises et artisans partenaires.</p>
                <button class="bg-slate-900 text-white px-6 py-2 rounded-xl text-sm font-bold">+ Nouveau Prestataire</button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="border border-slate-100 p-6 rounded-3xl bg-slate-50/50">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-12 w-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-bold">PL</div>
                        <div>
                            <h3 class="font-bold">Plomberie Express</h3>
                            <p class="text-xs text-slate-400">Urgence 24h/24</p>
                        </div>
                    </div>
                    <div class="text-sm text-slate-600 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 5a2 2 0 012-2h3.28a2 2 0 011.94 1.52l.72 2.89a2 2 0 01-.57 1.94l-1.32 1.32a16 16 0 006.36 6.36l1.32-1.32a2 2 0 011.94-.57l2.89.72A2 2 0 0121 17.72V21a2 2 0 01-2 2h-1C9.82 23 2 15.18 2 5z"/></svg>
                        081 000 000
                    </div>
                    <button class="w-full py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold hover:bg-slate-100 transition">Voir les interventions</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
