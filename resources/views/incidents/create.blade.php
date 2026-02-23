<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        
        /* Style pour l'aperçu des images */
        .preview-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
        }
    </style>

    <div class="py-6 md:py-12 bg-[#F8FAFC] min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 animate__animated animate__fadeInDown text-center">
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Espace <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600">Signalement</span>
                </h1>
                <p class="text-slate-500 mt-2 text-base md:text-lg">Signalez un incident en quelques secondes, nous nous occupons du reste.</p>
            </div>

            <div class="flex justify-center">
                <div class="w-full lg:w-2/3 bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2rem] overflow-hidden border border-slate-100 animate__animated animate__fadeInUp">
                    
                    <div class="bg-gradient-to-br from-indigo-600 to-violet-700 p-8 md:p-10 text-white text-center">
                        <h2 class="text-2xl font-bold">Détails de l'incident</h2>
                        <p class="opacity-90 mt-1 font-light">L'IA en bas à droite peut vous aider à remplir les champs.</p>
                    </div>

                    <div class="p-8 md:p-10">
                        {{-- Notifications --}}
                        @if (session('error'))
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-2xl animate__animated animate__headShake shadow-sm">
                                <div class="flex items-center">
                                    <span class="mr-3 text-xl">⚠️</span>
                                    <p class="font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-2xl animate__animated animate__fadeIn shadow-sm">
                                <div class="flex items-center">
                                    <span class="mr-3 text-xl">✅</span>
                                    <p class="font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        <form id="incidentForm" action="{{ route('incidents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                                        <option value="autres">🔎 Autres (Précisez ci-dessous)</option>
                                    </select>
                                    <div id="other_category_div" class="hidden animate__animated animate__fadeIn mt-4">
                                        <input type="text" name="other_details" id="other_details" placeholder="Précisez ici..." class="w-full px-6 py-4 bg-indigo-50/50 border border-indigo-100 rounded-2xl shadow-inner outline-none">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Niveau d'urgence <span class="text-red-500">*</span></label>
                                    <div class="flex p-1 bg-slate-100 rounded-2xl">
                                        <label class="flex-1 text-center py-3 rounded-xl cursor-pointer text-sm font-medium has-[:checked]:bg-white has-[:checked]:text-indigo-600 transition-all">
                                            <input type="radio" name="priority" value="basse" class="hidden"> Normal
                                        </label>
                                        <label class="flex-1 text-center py-3 rounded-xl cursor-pointer text-sm font-medium has-[:checked]:bg-white has-[:checked]:text-orange-500 transition-all">
                                            <input type="radio" name="priority" value="moyenne" checked class="hidden"> Urgent
                                        </label>
                                        <label class="flex-1 text-center py-3 rounded-xl cursor-pointer text-sm font-medium has-[:checked]:bg-white has-[:checked]:text-red-600 transition-all">
                                            <input type="radio" name="priority" value="haute" class="hidden"> Critique
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Objet du signalement</label>
                                <input type="text" name="title" value="{{ old('title') }}" placeholder="Ex: Fuite sous l'évier" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl shadow-inner">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Localisation</label>
                                <input type="text" name="location" value="{{ old('location') }}" placeholder="Ex: Cuisine, Hall A..." class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl shadow-inner">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                                <textarea name="description" rows="3" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl shadow-inner resize-none" placeholder="Décrivez le problème...">{{ old('description') }}</textarea>
                            </div>

                            {{-- SECTION PHOTOS MODIFIÉE --}}
                            <div class="group">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Photos (max 5)</label>
                                <input type="file" name="photo_path[]" id="photo_input" multiple accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                                
                                {{-- Conteneur pour l'aperçu --}}
                                <div id="preview_container" class="flex flex-wrap gap-3 mt-4"></div>
                                
                                <p class="text-xs text-slate-400 mt-2">Maintenez "Ctrl" pour sélectionner plusieurs images. (Format : jpg, png)</p>
                            </div>

                            <button type="submit" class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-bold py-5 rounded-2xl shadow-xl transition-all uppercase tracking-widest text-sm">
                                Transmettre au syndic
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chatbot AI --}}
    <div class="fixed bottom-6 right-6 z-50 group">
        <div class="absolute bottom-full right-0 mb-4 bg-slate-900 text-white text-xs py-2 px-4 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
            Besoin d'aide ? ✨
        </div>
        
        <button id="toggle-chat" class="w-16 h-16 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-full shadow-2xl flex items-center justify-center text-2xl hover:scale-110 transition-transform border-4 border-white">
            🤖
        </button>

        <div id="chat-window" class="hidden absolute bottom-20 right-0 w-[350px] md:w-[400px] h-[550px] bg-slate-950 rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden animate__animated">
            <div class="bg-slate-900/50 flex flex-col h-full p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center text-lg">🤖</div>
                        <h3 class="text-white font-bold text-sm">Assistant Copro</h3>
                    </div>
                    <button id="close-chat" class="text-slate-400 hover:text-white">✕</button>
                </div>
                
                <div id="chat-content" class="flex-1 overflow-y-auto space-y-4 mb-4 pr-2 custom-scrollbar text-sm">
                    <div class="bg-slate-800 text-slate-200 p-4 rounded-2xl rounded-tl-none border border-slate-700">
                        Bonjour ! Décrivez-moi votre problème technique, je m'occupe de remplir le formulaire. 👋
                    </div>
                </div>

                <div class="relative">
                    <input type="text" id="chat-input" placeholder="Ecrivez ici..." class="w-full pl-4 pr-12 py-4 bg-slate-800/50 border border-slate-700 rounded-xl text-white text-sm outline-none">
                    <button id="send-btn" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-indigo-600 text-white rounded-lg">🚀</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const chatInput = document.getElementById('chat-input');
        const chatContent = document.getElementById('chat-content');
        const sendBtn = document.getElementById('send-btn');
        const categorySelect = document.querySelector('select[name="category"]');
        const otherDiv = document.getElementById('other_category_div');
        const chatWindow = document.getElementById('chat-window');
        const toggleBtn = document.getElementById('toggle-chat');
        const closeBtn = document.getElementById('close-chat');

        // GESTION APERÇU PHOTOS
        const photoInput = document.getElementById('photo_input');
        const previewContainer = document.getElementById('preview_container');

        photoInput.addEventListener('change', function() {
            previewContainer.innerHTML = '';
            const files = Array.from(this.files);
            
            files.slice(0, 5).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-image animate__animated animate__zoomIn';
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        });

        // GESTION CHAT ET UI
        toggleBtn.addEventListener('click', () => {
            chatWindow.classList.toggle('hidden');
            if (!chatWindow.classList.contains('hidden')) chatWindow.classList.add('animate__fadeInUp');
        });

        closeBtn.addEventListener('click', () => chatWindow.classList.add('hidden'));

        categorySelect.addEventListener('change', function() {
            otherDiv.classList.toggle('hidden', this.value !== 'autres');
        });

        async function sendMessage() {
            const message = chatInput.value.trim();
            if (!message) return;

            chatContent.insertAdjacentHTML('beforeend', `<div class="flex flex-col items-end mb-4 animate__animated animate__fadeInRight"><div class="bg-indigo-600 text-white p-4 rounded-2xl rounded-tr-none text-sm shadow-md">${message}</div></div>`);
            chatInput.value = '';
            chatContent.scrollTop = chatContent.scrollHeight;

            const loadingId = 'loading-' + Date.now();
            chatContent.insertAdjacentHTML('beforeend', `<div id="${loadingId}" class="flex flex-col items-start mb-4 animate__animated animate__fadeIn"><div class="bg-slate-800 text-slate-400 p-4 rounded-2xl rounded-tl-none text-sm italic">Analyse en cours...</div></div>`);

            try {
                const response = await fetch("{{ route('ai.chat') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify({ message: message })
                });

                const result = await response.json();
                document.getElementById(loadingId).remove();

                let aiResponse;
                try {
                    aiResponse = JSON.parse(result.reply);
                } catch (e) {
                    aiResponse = { reply: result.reply || 'Désolé, réponse invalide.', data: null };
                }

                let extra = (aiResponse.data && aiResponse.data.title) ? `<div class="mt-3 pt-3 border-t border-slate-700 text-[11px] text-indigo-400 font-bold animate__animated animate__pulse animate__infinite">👉 N'oubliez pas de cliquer sur "Transmettre" ci-contre !</div>` : "";

                chatContent.insertAdjacentHTML('beforeend', `<div class="flex flex-col items-start mb-4 animate__animated animate__fadeInLeft"><div class="bg-slate-800 text-slate-200 p-4 rounded-2xl rounded-tl-none border border-slate-700 text-sm shadow-sm">${aiResponse.reply}${extra}</div></div>`);

                if (aiResponse.data) {
                    const d = aiResponse.data;
                    if (d.title) document.querySelector('input[name="title"]').value = d.title;
                    if (d.location) document.querySelector('input[name="location"]').value = d.location;
                    if (d.description) document.querySelector('textarea[name="description"]').value = d.description;
                    if (d.category) {
                        categorySelect.value = d.category.toLowerCase();
                        otherDiv.classList.toggle('hidden', d.category.toLowerCase() !== 'autres');
                    }
                }
                chatContent.scrollTop = chatContent.scrollHeight;
            } catch (error) {
                if(document.getElementById(loadingId)) document.getElementById(loadingId).remove();
                console.error("Erreur:", error);
            }
        }

        sendBtn.addEventListener('click', sendMessage);
        chatInput.addEventListener('keypress', (e) => { if(e.key === 'Enter') sendMessage(); });
    </script>
</x-app-layout>