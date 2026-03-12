@section('title', 'Inscription')
<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight mb-2">Rejoindre la résidence</h1>
        <p class="text-sm text-slate-500 font-medium">Créez votre compte pour signaler des incidents et échanger avec le syndic.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nom complet</label>
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </span>
                <x-text-input id="name" class="block w-full pl-11 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 shadow-sm text-sm font-medium text-slate-600 transition-all" type="text" name="name" :value="old('name')" required autofocus placeholder="Ex: Josh Kaninda" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
        </div>

        <div>
            <label for="email" class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Adresse Email</label>
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </span>
                <x-text-input id="email" class="block w-full pl-11 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 shadow-sm text-sm font-medium text-slate-600 transition-all" type="email" name="email" :value="old('email')" required placeholder="joshkaninda@gmail.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="occupant_status" class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Vous êtes ?</label>
                <select name="occupant_status" id="occupant_status" class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 shadow-sm text-sm font-bold text-slate-600 appearance-none cursor-pointer" required>
                    <option value="" disabled selected>Choisir...</option>
                    <option value="locataire">Locataire</option>
                    <option value="proprietaire">Propriétaire</option>
                </select>
            </div>
            <div>
                <label for="apartment_number" class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Appartement</label>
                <x-text-input id="apartment_number" class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 shadow-sm text-sm font-medium text-slate-600 transition-all" type="text" name="apartment_number" :value="old('apartment_number')" placeholder="Ex: B24" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Mot de passe</label>
                <x-text-input id="password" class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 shadow-sm text-sm font-medium text-slate-600 transition-all" type="password" name="password" required placeholder="••••••••" />
            </div>
            <div>
                <label for="password_confirmation" class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Confirmation</label>
                <x-text-input id="password_confirmation" class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 shadow-sm text-sm font-medium text-slate-600 transition-all" type="password" name="password_confirmation" required placeholder="••••••••" />
            </div>
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl shadow-lg shadow-indigo-100 transition-all transform hover:-translate-y-1 active:scale-95 font-black uppercase tracking-widest text-xs">
                {{ __('Créer mon compte') }}
            </x-primary-button>
        </div>

        <div class="text-center pt-4">
            <a class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 transition-colors" href="{{ route('login') }}">
                {{ __('Déjà inscrit ? Connectez-vous') }}
            </a>
        </div>
    </form>
</x-guest-layout>
