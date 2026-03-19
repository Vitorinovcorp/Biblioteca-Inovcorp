@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Requisições</h1>
        <a href="{{ route('requisicoes.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Nova Requisição
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    {{-- INDICADORES --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- Card: Requisições Ativas --}}
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm text-gray-600 uppercase tracking-wide">Requisições Ativas</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $requisicoesAtivas }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Livros atualmente requisitados</p>
        </div>

        {{-- Card: Requisições 30 dias --}}
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm text-gray-600 uppercase tracking-wide">Últimos 30 Dias</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $requisicoes30Dias }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Requisições nos últimos 30 dias</p>
        </div>

        {{-- Card: Livros Entregues Hoje --}}
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm text-gray-600 uppercase tracking-wide">Entregues Hoje</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $livrosEntreguesHoje }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Livros devolvidos hoje</p>
        </div>
    </div>

    {{-- TABELA DE REQUISIÇÕES --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Livro</th>
                    @if(Auth::user()->isAdmin())
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requisitante</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Período</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($requisicoes as $requisicao)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $requisicao->livro->nome }}</div>
                        <div class="text-sm text-gray-500">{{ $requisicao->livro->isbn }}</div>
                    </td>

                    @if(Auth::user()->isAdmin())
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $requisicao->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $requisicao->user->email }}</div>
                    </td>
                    @endif

                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $requisicao->data_inicio->format('d/m/Y') }} a {{ $requisicao->data_fim->format('d/m/Y') }}
                        </div>
                        @if($requisicao->status == 'aprovada' && $requisicao->data_fim->isTomorrow())
                            <span class="text-xs text-orange-600 font-semibold">⚠️ Entrega amanhã!</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        @if($requisicao->status == 'pendente')
                        <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pendente</span>
                        @elseif($requisicao->status == 'aprovada')
                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Aprovada</span>
                        @elseif($requisicao->status == 'rejeitada')
                        <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Rejeitada</span>
                        @elseif($requisicao->status == 'devolvida')
                        <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">Devolvida</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('requisicoes.show', $requisicao) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>

                        @if($requisicao->status == 'pendente' && (Auth::user()->isAdmin() || Auth::id() == $requisicao->user_id))
                        <form action="{{ route('requisicoes.destroy', $requisicao) }}"
                            method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-red-600 hover:text-red-900"
                                onclick="return confirm('Tem certeza que deseja cancelar?')">
                                Cancelar
                            </button>
                        </form>
                        @endif

                        @if(Auth::user()->isAdmin() && $requisicao->status == 'pendente')
                        <form action="{{ route('requisicoes.status', $requisicao) }}" method="POST" class="inline ml-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="aprovada">
                            <button type="submit" class="text-green-600 hover:text-green-900">Aprovar</button>
                        </form>
                        <form action="{{ route('requisicoes.status', $requisicao) }}" method="POST" class="inline ml-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejeitada">
                            <button type="submit" class="text-red-600 hover:text-red-900">Rejeitar</button>
                        </form>
                        @endif

                        @if(Auth::user()->isAdmin() && $requisicao->status == 'aprovada')
                        <a href="{{ route('requisicoes.devolver-form', $requisicao) }}"
                            class="text-purple-600 hover:text-purple-900 ml-2">
                            Devolver
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ Auth::user()->isAdmin() ? 5 : 4 }}" class="px-6 py-4 text-center text-gray-500">
                        Nenhuma requisição encontrada.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $requisicoes->links() }}
    </div>
</div>
@endsection