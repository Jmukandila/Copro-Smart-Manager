<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-3xl text-slate-800 tracking-tight">Gestion des Utilisateurs</h2>
                <p class="text-sm text-slate-500 font-medium">Administrez les accès et les rôles de la copropriété.</p>
            </div>
            <div class="bg-white px-4 py-2 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Communauté</p>
                <p class="text-xl font-black text-indigo-600">{{ $users->count() }} <span class="text-slate-400 text-sm">Membres</span></p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl shadow-sm font-bold text-sm flex items-center animate__animated animate__fadeIn">
                    <span class="mr-3 bg-emerald-500 text-white rounded-full p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </span>
                    {{ session('success') }}
                </div>
            @endif

         
            <div class="mb-8 flex flex-col md:flex-row gap-4">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" id="userSearch" placeholder="Rechercher un nom, un email ou un appartement..." 
                        class="w-full pl-11 pr-4 py-3 bg-white border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-medium text-slate-600">
                </div>
            </div>

            <div class="bg-white shadow-xl shadow-slate-200/40 rounded-[2.5rem] border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="p-6 text-[11px] font-black uppercase text-slate-400 tracking-wider">Identité</th>
                                <th class="p-6 text-[11px] font-black uppercase text-slate-400 tracking-wider">Statut Occupant</th>
                                <th class="p-6 text-[11px] font-black uppercase text-slate-400 tracking-wider">Rôle & Autorité</th>
                                <th class="p-6 text-[11px] font-black uppercase text-slate-400 tracking-wider text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50" id="userTableBody">
                            @foreach($users as $user)
                            <tr class="hover:bg-indigo-50/30 transition-all duration-200 group">
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-2xl {{ $user->role == 'admin' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center font-black text-lg shadow-sm group-hover:scale-110 transition-transform">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-400 font-medium">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase {{ $user->occupant_status == 'proprietaire' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $user->occupant_status ?? 'Non défini' }}
                                    </span>
                                </td>
                                <td class="p-6">
                                    <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="relative">
                                        @csrf @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" 
                                            class="appearance-none pr-8 py-2 text-[11px] font-black rounded-xl border-none cursor-pointer focus:ring-2 focus:ring-indigo-500 {{ $user->role == 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>LOCATAIRE</option>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>ADMINISTRATEUR</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="p-6">
                                    <div class="flex justify-center">
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Attention : Cette action est irréversible. Supprimer {{ $user->name }} ?')">
                                                @csrf @method('DELETE')
                                                <button class="p-3 text-slate-300 hover:text-red-600 hover:bg-red-50 rounded-2xl transition-all group/btn">
                                                    <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="px-3 py-1 bg-slate-50 text-slate-400 text-[10px] font-bold rounded-lg border border-slate-100 uppercase">Vous</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('userSearch').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#userTableBody tr');

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>