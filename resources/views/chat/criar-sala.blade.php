@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">Criar Nova Sala</h1>
    
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('chat.salas.store') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nome da Sala</label>
                <input type="text" name="nome" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Convidar Utilizadores</label>
                <select name="utilizadores[]" multiple
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach($utilizadores as $utilizador)
                        <option value="{{ $utilizador->id }}">{{ $utilizador->name }} ({{ $utilizador->email }})</option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">Segure Ctrl (Windows) ou Cmd (Mac) para selecionar múltiplos</p>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Criar Sala
                </button>
            </div>
        </form>
    </div>
</div>
@endsection