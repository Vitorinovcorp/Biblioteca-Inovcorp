<header class="bg-blue-600 text-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <img src="{{ asset('icons/inovcorp-bg-w.png') }}" class="h-12 w-auto" alt="Logo">
                        <span class="font-semibold text-white text-lg">Inovcorp</span>
                    </a>
                </div>
            </div>

            <!-- Menu Desktop -->
            <nav class="hidden md:flex items-center space-x-2">
                <a href="/livros" class="{{ request()->is('livros') ? 'bg-blue-700' : 'hover:bg-blue-700' }} px-3 py-2 rounded transition">
                    {{ __('messages.books') }}
                </a>
                <a href="/editoras" class="{{ request()->is('editoras') ? 'bg-blue-700' : 'hover:bg-blue-700' }} px-3 py-2 rounded transition">
                    {{ __('messages.publishers') }}
                </a>
                <a href="/autores" class="{{ request()->is('autores') ? 'bg-blue-700' : 'hover:bg-blue-700' }} px-3 py-2 rounded transition">
                    {{ __('messages.authors') }}
                </a>
                <a href="{{ route('requisicoes.index') }}" class="{{ request()->routeIs('requisicoes.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} px-3 py-2 rounded transition">
                    {{ __('messages.requests') }}
                </a>
                <a href="{{ route('google-books.search') }}" class="{{ request()->routeIs('google-books.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} px-3 py-2 rounded transition">
                     Google Books
                </a>

                <a href="{{ route('carrinho.index') }}" class="{{ request()->routeIs('carrinho.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} px-3 py-2 rounded transition relative">
                    {{ __('messages.cart') }}
                    @php
                    $totalItens = session('carrinho_total_itens', 0);
                    if ($totalItens == 0 && Auth::check()) {
                    $carrinho = \App\Models\Carrinho::where('user_id', Auth::id())->where('status', 'aberto')->first();
                    if ($carrinho) {
                    $totalItens = $carrinho->itens->sum('quantidade');
                    session(['carrinho_total_itens' => $totalItens]);
                    }
                    }
                    @endphp
                    @if($totalItens > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">{{ $totalItens }}</span>
                    @endif
                </a>

                <a href="{{ route('encomendas.index') }}" class="{{ request()->routeIs('encomendas.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} px-3 py-2 rounded transition">
                    {{ __('messages.my_purchases') }}
                </a>

                <a href="{{ route('chat.index') }}" 
                   class="{{ request()->routeIs('chat.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} px-3 py-2 rounded transition">
                    Chat
                </a>

                <!-- Seletor de idioma -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        @click.away="open = false"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-500 hover:bg-blue-700 transition text-white">
                        <span>
                            @if(App::getLocale() == 'pt') Português
                            @elseif(App::getLocale() == 'en') English
                            @elseif(App::getLocale() == 'es') Español
                            @else Français
                            @endif
                        </span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg py-2 z-50"
                        style="display: none;">
                        <a href="{{ route('lang.switch', 'pt') }}"
                            class="block px-4 py-2 hover:bg-gray-100 transition {{ App::getLocale() == 'pt' ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                            Português
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="block px-4 py-2 hover:bg-gray-100 transition {{ App::getLocale() == 'en' ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                            English
                        </a>
                        <a href="{{ route('lang.switch', 'es') }}"
                            class="block px-4 py-2 hover:bg-gray-100 transition {{ App::getLocale() == 'es' ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                            Español
                        </a>
                        <a href="{{ route('lang.switch', 'fr') }}"
                            class="block px-4 py-2 hover:bg-gray-100 transition {{ App::getLocale() == 'fr' ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                            Français
                        </a>
                    </div>
                </div>
                <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="hover:bg-blue-700 px-3 py-2 rounded transition">
                    {{ __('messages.logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </nav>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center gap-2">
                <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-blue-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <div class="flex flex-col space-y-2">
                <a href="/livros" class="hover:bg-blue-700 px-3 py-2 rounded block">{{ __('messages.books') }}</a>
                <a href="/editoras" class="hover:bg-blue-700 px-3 py-2 rounded block">{{ __('messages.publishers') }}</a>
                <a href="/autores" class="hover:bg-blue-700 px-3 py-2 rounded block">{{ __('messages.authors') }}</a>
                <a href="{{ route('requisicoes.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded block">{{ __('messages.requests') }}</a>
                <a href="{{ route('google-books.search') }}" class="hover:bg-blue-700 px-3 py-2 rounded block">Google Books</a>
                <a href="{{ route('carrinho.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded block">{{ __('messages.cart') }}</a>
                <a href="{{ route('encomendas.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded block">{{ __('messages.my_purchases') }}</a>
                
                <a href="{{ route('chat.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded block">
                    Chat
                </a>

                <!-- Idioma no mobile -->
                <div class="border-t border-blue-500 pt-2 mt-2">
                    <div class="font-semibold px-3 py-1 text-sm">{{ __('messages.language') }}:</div>
                    <a href="{{ route('lang.switch', 'pt') }}" class="block px-3 py-1 hover:bg-blue-700 rounded">🇵🇹 {{ __('messages.portuguese') }}</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="block px-3 py-1 hover:bg-blue-700 rounded">🇬🇧 {{ __('messages.english') }}</a>
                    <a href="{{ route('lang.switch', 'es') }}" class="block px-3 py-1 hover:bg-blue-700 rounded">🇪🇸 {{ __('messages.spanish') }}</a>
                    <a href="{{ route('lang.switch', 'fr') }}" class="block px-3 py-1 hover:bg-blue-700 rounded">🇫🇷 {{ __('messages.french') }}</a>
                </div>

                <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" class="hover:bg-blue-700 px-3 py-2 rounded block">{{ __('messages.logout') }}</a>
                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');
        if (button && menu) {
            button.addEventListener('click', function() {
                menu.classList.toggle('hidden');
            });
        }
    });
</script>