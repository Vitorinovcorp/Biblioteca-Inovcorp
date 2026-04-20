@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center">Testes Automatizados - Sistema de Requisições</h1>

    @if(session('resultado'))
    <div class="mb-8 bg-gray-100 rounded-lg p-6 border-l-4 {{ session('resultado.status') == 'PASS' ? 'border-white-1' : 'border-red-500' }}">
        <h2 class="text-2xl font-bold mb-4">Resultado do Teste</h2>
        <div class="bg-gray-800 text-green-400 p-4 rounded overflow-auto">
            <pre>{{ json_encode(session('resultado'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-blue-600">1. Teste de Criação de Requisição de Livro</h2>

            </div>
            <p class="text-gray-600 mb-4">Verifica se um utilizador pode criar uma requisição de um livro corretamente.</p>
            <form action="{{ route('testes.criar-requisicao') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Executar Teste
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-blue-600">2. Teste de Validação de Requisição de Livro</h2>

            </div>
            <p class="text-gray-600 mb-4">Assegura que uma requisição não pode ser criada sem um livro válido.</p>
            <form action="{{ route('testes.validacao') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Executar Teste
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-blue-600">3. Teste de Devolução de Livro</h2>

            </div>
            <p class="text-gray-600 mb-4">Confirma se um utilizador pode devolver um livro corretamente.</p>
            <form action="{{ route('testes.devolucao') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Executar Teste
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-blue-600">4. Teste de Listagem por Utilizador</h2>

            </div>
            <p class="text-gray-600 mb-4">Garante que um utilizador consegue ver apenas as suas requisições.</p>
            <form action="{{ route('testes.listagem') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Executar Teste
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-blue-600">5. Teste de Stock na Requisição de Livro</h2>

            </div>
            <p class="text-gray-600 mb-4">Confirma se não é possível requisitar um livro sem stock disponível.</p>
            <form action="{{ route('testes.stock') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Executar Teste
                </button>
            </form>
        </div>
        @endsection