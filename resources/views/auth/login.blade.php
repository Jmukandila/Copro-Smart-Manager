<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight mb-2">Bon retour !</h1>
        <p class="text-sm text-slate-500 font-medium">Connectez-vous pour gérer vos incidents et suivre la vie de votre résidence.</p>
    </div>

    <x-auth-session-status class="mb-6 p-4 bg-indigo-50 text-indigo-700 rounded-2xl border border-indigo-100 text-sm font-bold text-center" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Adresse Email</label>
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" /></svg>
                </span>
                <x-text-input id="email" class="block w-full pl-11 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 shadow-sm text-sm font-medium text-slate-600 transition-all" type="email" name="email" :value="old('email')" required autofocus placeholder="joshkaninda@gmail.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold text-red-500" />
        </div>

        <div>
            <div class="flex justify-between items-center mb-2 ml-1">
                <label for="password" class="block text-xs font-black uppercase tracking-widest text-slate-400">Mot de passe</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-black uppercase text-indigo-500 hover:text-indigo-700 transition-colors tracking-tighter" href="{{ route('password.request') }}">
                        Oublié ?
                    </a>
                @endif
            </div>
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </span>
                <x-text-input id="password" class="block w-full pl-11 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 shadow-sm text-sm font-medium text-slate-600 transition-all"
                                type="password"
                                name="password"
                                required placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold text-red-500" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded-lg border-slate-200 text-indigo-600 shadow-sm focus:ring-indigo-500 transition-all" name="remember">
                <span class="ms-2 text-xs font-bold text-slate-500 group-hover:text-slate-700 transition-colors uppercase tracking-widest">{{ __('Se souvenir de moi') }}</span>
            </label>
        </div>

        <div>
            <x-primary-button class="w-full justify-center py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl shadow-lg shadow-indigo-100 transition-all transform hover:-translate-y-1 active:scale-95 font-black uppercase tracking-widest text-xs">
                {{ __('Se connecter') }}
            </x-primary-button>
        </div>
    </form>
    
    <div class="mt-8 text-center border-t border-slate-100 pt-6">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
            Pas encore de compte ? 
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 ml-1">Créer un compte</a>
        </p>
    </div>
</x-guest-layout>