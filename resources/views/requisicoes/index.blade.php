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
                            <form action="{{ route('requisicoes.status', $requisicao) }}" method="POST" class="inline ml-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="devolvida">
                                <button type="submit" class="text-purple-600 hover:text-purple-900">Devolver</button>
                            </form>
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