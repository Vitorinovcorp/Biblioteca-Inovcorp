@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Detalhes da Requisição</h1>
        <a href="{{ route('requisicoes.index') }}" 
           class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
            Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Livro</h3>
                    <p class="text-lg font-semibold">{{ $requisicao->livro->nome }}</p>
                    <p class="text-sm text-gray-600">ISBN: {{ $requisicao->livro->isbn }}</p>
                </div>

                @if(Auth::user()->isAdmin())
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Requisitante</h3>
                    <p class="text-lg font-semibold">{{ $requisicao->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $requisicao->user->email }}</p>
                </div>
                @endif

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Período</h3>
                    <p class="text-lg">
                        {{ $requisicao->data_inicio->format('d/m/Y') }} a {{ $requisicao->data_fim->format('d/m/Y') }}
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                    @if($requisicao->status == 'pendente')
                        <span class="px-3 py-1 text-sm rounded bg-yellow-100 text-yellow-800">Pendente</span>
                    @elseif($requisicao->status == 'aprovada')
                        <span class="px-3 py-1 text-sm rounded bg-green-100 text-green-800">Aprovada</span>
                    @elseif($requisicao->status == 'rejeitada')
                        <span class="px-3 py-1 text-sm rounded bg-red-100 text-red-800">Rejeitada</span>
                    @elseif($requisicao->status == 'devolvida')
                        <span class="px-3 py-1 text-sm rounded bg-gray-100 text-gray-800">Devolvida</span>
                    @endif
                </div>

                @if($requisicao->observacoes)
                <div class="col-span-2">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Observações</h3>
                    <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $requisicao->observacoes }}</p>
                </div>
                @endif
            </div>
        </div>

        @if(Auth::user()->isAdmin() && $requisicao->status == 'pendente')
        <div class="border-t px-6 py-4 bg-gray-50">
            <h3 class="text-sm font-medium text-gray-700 mb-3">Ações de Administrador</h3>
            <div class="flex space-x-3">
                <form action="{{ route('requisicoes.status', $requisicao) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="aprovada">
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                            onclick="return confirm('Confirmar aprovação?')">
                        Aprovar Requisição
                    </button>
                </form>
                
                <form action="{{ route('requisicoes.status', $requisicao) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejeitada">
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                            onclick="return confirm('Confirmar rejeição?')">
                        Rejeitar Requisição
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if(Auth::user()->isAdmin() && $requisicao->status == 'aprovada')
        <div class="border-t px-6 py-4 bg-gray-50">
            <form action="{{ route('requisicoes.status', $requisicao) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="devolvida">
                <button type="submit" 
                        class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700"
                        onclick="return confirm('Confirmar devolução?')">
                    Marcar como Devolvido
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection