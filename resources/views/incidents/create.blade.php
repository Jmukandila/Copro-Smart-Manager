<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <div class="py-6 md:py-12 bg-[#F8FAFC] min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 animate__animated animate__fadeInDown text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Espace <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600">Signalement</span>
                </h1>
                <p class="text-slate-500 mt-2 text-base md:text-lg">Signalez un incident en quelques secondes, nous nous occupons du reste.</p>
            </div>

            @if (session('error'))
                <div class="mb-6 p-4 bg-orange-50 border-l-4 border-orange-500 text-orange-700 rounded-2xl animate__animated animate__shakeX shadow-sm">
                    <div class="flex items-center">
                        <span class="mr-3 text-xl">🚫</span>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="w-full lg:w-2/3 bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2rem] overflow-hidden border border-slate-100 animate__animated animate__fadeInLeft">
                    <div class="bg-gradient-to-br from-indigo-600 to-violet-700 p-8 md:p-10 text-white">
                        <h2 class="text-2xl font-bold">Détails de l'incident</h2>
                        <p class="opacity-90 mt-1 font-light">Tous les champs sont importants pour une intervention efficace.</p>
                    </div>

                    <form action="{{ route('incidents.store') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10 space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nature du problème <span class="text-red-500">*</span></label>
                                <select name="category" required oninvalid="this.setCustomValidity('Veuillez choisir une catégorie.')" oninput="this.setCustomValidity('')" class="w-full px-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all text-slate-700 shadow-inner">
                                    <option value="" disabled selected>Choisir une option...</option>
                                    <option value="plomberie">🚰 Plomberie & Eau</option>
                                    <option value="electricite">⚡ Électricité & Lumière</option>
                                    <option value="ascenseur">🛗 Ascenseur / Parties communes</option>
                                    <option value="securite">🔐 Sécurité & Accès</option>
                                    <option value="chauffage">🔥 Chauffage & Clim</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Niveau d'urgence <span class="text-red-500">*</span></label>
                                <div class="flex p-1 bg-slate-100 rounded-2xl">
                                    <label class="flex-1 text-center py-3 rounded-xl cursor-pointer text-sm font-medium transition-all has-[:checked]:bg-white has-[:checked]:text-indigo-600 has-[:checked]:shadow-sm">
                                        <input type="radio" name="priority" value="basse" class="hidden" required> Normal
                                    </label>
                                    <label class="flex-1 text-center py-3 rounded-xl cursor-pointer text-sm font-medium transition-all has-[:checked]:bg-white has-[:checked]:text-orange-500 has-[:checked]:shadow-sm">
                                        <input type="radio" name="priority" value="moyenne" checked class="hidden"> Urgent
                                    </label>
                                    <label class="flex-1 text-center py-3 rounded-xl cursor-pointer text-sm font-medium transition-all has-[:checked]:bg-white has-[:checked]:text-red-600 has-[:checked]:shadow-sm">
                                        <input type="radio" name="priority" value="haute" class="hidden"> Critique
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Objet du signalement <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required oninvalid="this.setCustomValidity('Veuillez donner un titre à votre signalement.')" oninput="this.setCustomValidity('')" placeholder="Ex: Fuite sous l'évier de la cuisine" value="{{ old('title') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all shadow-inner">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Où cela se trouve-t-il ? <span class="text-red-500">*</span></label>
                            <input type="text" name="location" required oninvalid="this.setCustomValidity('Veuillez préciser le lieu de l\'incident.')" oninput="this.setCustomValidity('')" placeholder="Ex: Hall B, 3ème étage, ou dans mon salon" value="{{ old('location') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all shadow-inner">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Description détaillée <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="4" required oninvalid="this.setCustomValidity('Merci de décrire le problème précisément.')" oninput="this.setCustomValidity('')" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all shadow-inner resize-none" placeholder="Donnez plus de détails sur le problème constaté...">{{ old('description') }}</textarea>
                        </div>

                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Preuve visuelle (Photo) <span class="text-red-500">*</span></label>
                            <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-slate-200 rounded-[2rem] bg-slate-50 hover:bg-indigo-50/30 hover:border-indigo-300 transition-all cursor-pointer">
                                <div class="flex flex-col items-center justify-center p-4 text-center">
                                    <svg class="w-8 h-8 text-indigo-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    <p class="text-xs md:text-sm text-slate-500 font-medium leading-tight">Glissez votre image ou <span class="text-indigo-600">parcourez vos fichiers</span></p>
                                </div>
                                <input type="file" name="photo" required oninvalid="this.setCustomValidity('Une photo est obligatoire pour valider le signalement.')" oninput="this.setCustomValidity('')" class="hidden" />
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-bold py-5 rounded-2xl shadow-xl shadow-indigo-100 transform hover:-translate-y-1 active:scale-[0.98] transition-all duration-300 uppercase tracking-widest text-sm">
                            Transmettre au syndic
                        </button>
                    </form>
                </div>
                
                <div class="w-full lg:w-1/3 animate__animated animate__fadeInRight">
                    <div class="bg-slate-950 rounded-[2.5rem] shadow-2xl p-2 h-[500px] lg:h-[750px] lg:sticky lg:top-8 border border-slate-800">
                        <div class="bg-slate-900/50 rounded-[2.2rem] flex flex-col h-full p-6">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="relative">
                                    <div class="w-12 h-12 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center text-xl text-white">🤖</div>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-4 border-slate-900 rounded-full"></div>
                                </div>
                                <div>
                                    <h3 class="text-white font-bold">Assistant Copro</h3>
                                    <p class="text-emerald-500 text-[10px] uppercase font-bold tracking-widest">En ligne</p>
                                </div>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto space-y-4 mb-6 pr-2 custom-scrollbar">
                                <div class="bg-slate-800 text-slate-200 p-5 rounded-3xl rounded-tl-none border border-slate-700 leading-relaxed text-sm shadow-sm">
                                    Bonjour ! Je suis l'IA de votre immeuble. 👋
                                    <br><br>
                                    Si vous avez un doute sur la priorité d'un incident ou si vous avez besoin d'une aide d'urgence, écrivez-moi !
                                </div>
                            </div>

                            <div class="relative mt-auto">
                                <input type="text" placeholder="Posez une question..." class="w-full pl-6 pr-14 py-5 bg-slate-800/50 border border-slate-700 rounded-2xl text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                <button class="absolute right-3 top-1/2 -translate-y-1/2 p-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg transition-all">
                                    🚀
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>