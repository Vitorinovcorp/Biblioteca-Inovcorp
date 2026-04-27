
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Carrinho') }} | Inovcorp</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="icon" href="{{ asset('icons/inovcorp-bg-w.png') }}" type="image/x-icon">
    <style>
    html, body {
        background-color: #ffffff !important;
    }
    
    .min-h-screen {
        background-color: #ffffff !important;
    }
</style>
</head>
<body class="font-sans antialiased bg-white">
    <x-banner />
    <div class="min-h-screen bg-white-200">
        <x-header />
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif
        <main>
            {{ $slot ?? '' }} 
            @yield('content') 
        </main>
    </div>
    @stack('modals')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @if(session('mensagem'))
    <div style="position: fixed; bottom: 20px; right: 20px; background: #22c55e; color: white; padding: 15px 20px; z-index: 9999; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-size: 14px; font-weight: 500;">
        {{ session('mensagem') }}
    </div>
    <script>
        setTimeout(function() {
            var div = document.querySelector('div[style*="position: fixed"]');
            if(div) div.remove();
        }, 3000);
    </script>
    @endif

    <script>
        function atualizarContadorCarrinho(total) {
            var contador = document.getElementById('carrinho-contador');
            if (contador) {
                if (total > 0) {
                    contador.textContent = total;
                    contador.classList.remove('hidden');
                } else {
                    contador.classList.add('hidden');
                }
            }
        }
    </script>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
