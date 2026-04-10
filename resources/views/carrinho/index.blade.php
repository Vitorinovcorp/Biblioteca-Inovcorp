@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Meu Carrinho</h1>

    @if($carrinho && $carrinho->itens->count() > 0)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Livro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Preço Unitário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantidade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($carrinho->itens as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-16 h-20 bg-gray-100 rounded overflow-hidden mr-4">
                                        @if($item->livro->imagem_capa)
                                            <img src="{{ asset('storage/' . $item->livro->imagem_capa) }}" 
                                                 alt="{{ $item->livro->nome }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-book fa-2x text-gray-400 flex items-center justify-center h-full"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $item->livro->nome }}</h3>
                                        <p class="text-sm text-gray-500">{{ $item->livro->autores->pluck('nome')->implode(', ') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-800">€ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('carrinho.atualizar', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantidade" value="{{ $item->quantidade }}" 
                                           min="1" max="{{ $item->livro->quantidade }}"
                                           class="w-20 px-2 py-1 border rounded text-center">
                                    <button type="submit" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-800">€ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('carrinho.remover', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-800">Total:</td>
                            <td class="px-6 py-4 text-xl font-bold text-green-600">€ {{ number_format($carrinho->total, 2, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t flex justify-between">
                <a href="{{ route('livros.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-2"></i> Continuar Comprando
                </a>
                <a href="{{ route('carrinho.checkout') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Finalizar Compra <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <i class="fas fa-shopping-cart fa-4x text-gray-400 mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-600 mb-2">Seu carrinho está vazio</h2>
            <p class="text-gray-500 mb-6">Adicione alguns livros ao carrinho para continuar.</p>
            <a href="{{ route('livros.index') }}" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                Ver Livros Disponíveis
            </a>
        </div>
    @endif
</div>
@endsection