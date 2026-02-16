<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion Copropriété</title>

    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'media',
            theme: {
                extend: {
                    colors: {
                        darkBg: '#0a0a0a',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    </body>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm">
                        Tableau de bord
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18]">Connexion</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] border text-[#1b1b18] dark:border-[#3E3E3A] rounded-sm">
                            Créer un compte
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow">
        <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row shadow-xl rounded-lg overflow-hidden">
            
            <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC]">
                <h1 class="mb-4 text-2xl font-bold">Gestion Copropriété</h1>
                <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A]">Bienvenue sur votre plateforme résidents. Simplifiez vos échanges avec le syndic et suivez la vie de votre immeuble.</p>
                
                <ul class="flex flex-col gap-4 mb-8">
                    <li class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                        <span>Signaler un incident technique en 2 minutes</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                        <span>Suivre l'avancement des réparations</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                        <span>Accéder aux documents de la copropriété</span>
                    </li>
                </ul>

                <div class="flex gap-4">
                    <a href="{{ route('register') }}" class="bg-[#1b1b18] text-white dark:bg-[#eeeeec] dark:text-[#1b1b18] px-6 py-2 rounded-md font-medium">
                        Commencer
                    </a>
                </div>
            </div>

            <div class="flex-1 bg-gray-100 dark:bg-[#1b1b18] p-6 flex flex-col justify-center items-center text-center border-l dark:border-[#3E3E3A]">
                <div class="w-20 h-20 mb-4 bg-red-500 rounded-2xl flex items-center justify-center shadow-lg shadow-red-500/20">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold">Votre Immeuble Connecté</h2>
                <p class="text-sm text-[#706f6c] mt-2 px-4">Un problème dans les parties communes ? Prenez une photo et envoyez-la nous instantanément.</p>
            </div>
        </main>
    </div>
</body>