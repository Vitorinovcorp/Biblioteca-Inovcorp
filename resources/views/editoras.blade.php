<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editoras</title>
    @vite('resources/js/app.js')
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen">

    <x-header />

    <main class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Lista de Editoras</h1>
        
        <!-- Componente Livewire -->
        @livewire('editora-table')
    </main>

    @livewireScripts
</body>
</html>