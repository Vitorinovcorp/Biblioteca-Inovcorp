@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/3 p-6">
                @if($livro->imagem_capa)
                    <img src="{{ Storage::url($livro->imagem_capa) }}" 
                         alt="{{ $livro->nome }}" 
                         class="w-full rounded-lg shadow-md">
                @else
                    <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book fa-4x text-gray-400"></i>
                    </div>
                @endif
            </div>
            
            <div class="md:w-2/3 p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $livro->nome }}</h1>
                
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-user mr-2"></i> Autores:</span>
                    @if($livro->autores->count() > 0)
                        @foreach($livro->autores as $autor)
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2 text-sm">
                                {{ $autor->nome }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-gray-500">Não informado</span>
                    @endif
                </div>
                
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-building mr-2"></i> Editora:</span>
                    <span class="text-gray-800">{{ $livro->editora->nome ?? 'Não informada' }}</span>
                </div>
                
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-barcode mr-2"></i> ISBN:</span>
                    <span class="text-gray-800">{{ $livro->isbn ?? 'Não informado' }}</span>
                </div>
                
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-dollar-sign mr-2"></i> Preço:</span>
                    <span class="text-2xl font-bold text-green-600">R$ {{ number_format($livro->preco, 2, ',', '.') }}</span>
                </div>
                
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-boxes mr-2"></i> Quantidade em estoque:</span>
                    <span class="text-xl font-semibold {{ $livro->quantidade > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $livro->quantidade }}
                    </span>
                </div>
                
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-check-circle mr-2"></i> Disponibilidade:</span>
                    <span class="inline-block px-3 py-1 rounded text-sm font-semibold {{ $disponivelAgora ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $disponivelAgora ? 'Disponível para empréstimo' : 'Indisponível no momento' }}
                    </span>
                </div>
                
                @if($livro->bibliografia)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Descrição:</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $livro->bibliografia }}</p>
                </div>
                @endif
                
                <div class="flex gap-4 mt-6">
                    <a href="{{ route('livros.index') }}" 
                       class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Voltar
                    </a>
                    
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('livros.edit', $livro->id) }}" 
                               class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 transition">
                                <i class="fas fa-edit mr-2"></i> Editar
                            </a>
                            
                            <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition"
                                        onclick="return confirm('Tem certeza que deseja excluir este livro?')">
                                    <i class="fas fa-trash mr-2"></i> Excluir
                                </button>
                            </form>
                        @endif
                        
                        @if($livro->quantidade > 0 && $disponivelAgora)
                            <a href="{{ route('requisicoes.create', ['livro_id' => $livro->id]) }}" 
                               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-hand-holding-heart mr-2"></i> Solicitar Empréstimo
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
    
    @auth
        @if(Auth::user()->role === 'admin' && isset($historico) && $historico->count() > 0)
        <div class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-history mr-2"></i> Histórico de Empréstimos
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Empréstimo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Devolução</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($historico as $requisicao)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $requisicao->user->name ?? 'Usuário não encontrado' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($requisicao->data_inicio)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($requisicao->data_fim)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $requisicao->status === 'aprovada' ? 'bg-green-100 text-green-800' : 
                                       ($requisicao->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($requisicao->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endauth
</div>
@endsection