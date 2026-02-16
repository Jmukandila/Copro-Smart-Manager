<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>

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
                        <p class="opacity-90 mt-1 font-light">L'IA peut vous aider à remplir ces champs via le chat.</p>
                    </div>

                    <form id="incidentForm" action="{{ route('incidents.store') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10 space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nature du problème <span class="text-red-500">*</span></label>
                                <select name="category" required class="w-full px-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500/20 transition-all text-slate-700 shadow-inner">
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
                                    <label class="flex-1 text-center py-3 rounded-xl cursor-pointer text-sm font-medium transition-all has-[:checked]:bg-white has-[:checked]:text-indigo-600">
                                        <input type="radio" name="priority" value="basse" class="hidden"> Normal
                                    </label>
                                    <label class="flex-1 text-center py-3 rounded-xl cursor-pointer text-sm font-medium transition-all has-[:checked]:bg-white has-[:checked]:text-orange-500">
                                        <input type="radio" name="priority" value="moyenne" checked class="hidden"> Urgent
                                    </label>
                                    <label class="flex-1 text-center py-3 rounded-xl cursor-pointer text-sm font-medium transition-all has-[:checked]:bg-white has-[:checked]:text-red-600">
                                        <input type="radio" name="priority" value="haute" class="hidden"> Critique
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Objet du signalement</label>
                            <input type="text" name="title" placeholder="Ex: Fuite sous l'évier" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl shadow-inner">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Localisation</label>
                            <input type="text" name="location" placeholder="Ex: Cuisine, Hall A..." class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl shadow-inner">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                            <textarea name="description" rows="3" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl shadow-inner resize-none" placeholder="Décrivez le problème..."></textarea>
                        </div>

                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Photo</label>
                            <input type="file" name="photo" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                        </div>

                        <button type="submit" class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-bold py-5 rounded-2xl shadow-xl transition-all uppercase tracking-widest text-sm">
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
                            
                            <div id="chat-content" class="flex-1 overflow-y-auto space-y-4 mb-6 pr-2 custom-scrollbar">
                                <div class="bg-slate-800 text-slate-200 p-5 rounded-3xl rounded-tl-none border border-slate-700 text-sm shadow-sm">
                                    Bonjour ! Dites-moi quel est votre problème, je vais remplir le formulaire pour vous. 👋
                                </div>
                            </div>

                            <div class="relative mt-auto">
                                <input type="text" id="chat-input" placeholder="Posez une question..." class="w-full pl-6 pr-14 py-5 bg-slate-800/50 border border-slate-700 rounded-2xl text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                <button id="send-btn" class="absolute right-3 top-1/2 -translate-y-1/2 p-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg transition-all">
                                    🚀
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const chatInput = document.getElementById('chat-input');
        const chatContent = document.getElementById('chat-content');
        const sendBtn = document.getElementById('send-btn');

        async function sendMessage() {
            const message = chatInput.value.trim();
            if (!message) return;

            // 1. Affichage message utilisateur
            chatContent.insertAdjacentHTML('beforeend', `
                <div class="flex flex-col items-end max-w-[85%] ml-auto animate__animated animate__fadeInRight mb-4">
                    <div class="bg-indigo-600 text-white p-4 rounded-3xl rounded-tr-none text-sm shadow-md">${message}</div>
                </div>
            `);
            chatInput.value = '';
            chatContent.scrollTop = chatContent.scrollHeight;

            // 2. Indicateur de chargement
            const loadingId = 'loading-' + Date.now();
            chatContent.insertAdjacentHTML('beforeend', `
                <div id="${loadingId}" class="flex flex-col items-start max-w-[85%] animate__animated animate__fadeIn mb-4">
                    <div class="bg-slate-800 text-slate-400 p-4 rounded-3xl rounded-tl-none border border-slate-700 text-sm italic">L'assistant réfléchit...</div>
                </div>
            `);
            chatContent.scrollTop = chatContent.scrollHeight;

            try {
                const response = await fetch("{{ route('ai.chat') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ message: message })
                });

                const result = await response.json();
                document.getElementById(loadingId).remove();

                // 3. Analyse de la réponse (JSON ou Texte)
                let aiResponse;
                try {
                    // Si l'IA envoie une chaîne JSON dans sa réponse
                    aiResponse = JSON.parse(result.reply);
                } catch(e) {
                    // Si c'est juste du texte
                    aiResponse = { reply: result.reply, data: {} };
                }

                // 4. Affichage bulle IA
                chatContent.insertAdjacentHTML('beforeend', `
                    <div class="flex flex-col items-start max-w-[85%] mb-4 animate__animated animate__fadeInLeft">
                        <div class="bg-slate-800 text-slate-200 p-4 rounded-3xl rounded-tl-none border border-slate-700 text-sm shadow-sm">
                            ${aiResponse.reply}
                        </div>
                    </div>
                `);

                // 5. Remplissage du formulaire
                if (aiResponse.data) {
                    const d = aiResponse.data;
                    if (d.title) document.querySelector('input[name="title"]').value = d.title;
                    if (d.location) document.querySelector('input[name="location"]').value = d.location;
                    if (d.description) document.querySelector('textarea[name="description"]').value = d.description;
                    if (d.category) {
                        const select = document.querySelector('select[name="category"]');
                        if ([...select.options].some(opt => opt.value === d.category.toLowerCase())) {
                            select.value = d.category.toLowerCase();
                        }
                    }
                }
                chatContent.scrollTop = chatContent.scrollHeight;

            } catch (error) {
                if(document.getElementById(loadingId)) document.getElementById(loadingId).remove();
                console.error("Erreur:", error);
            }
        }

        sendBtn.addEventListener('click', (e) => { e.preventDefault(); sendMessage(); });
        chatInput.addEventListener('keypress', (e) => { if(e.key === 'Enter') { e.preventDefault(); sendMessage(); } });
    </script>
</x-app-layout>