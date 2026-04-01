<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Livros</title>
    @vite('resources/js/app.js')
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen">

    <x-header />

    <main class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Lista de Livros</h1>
        
        @livewire('livro-table')
    </main>

    @livewireScripts
</body>
</html>