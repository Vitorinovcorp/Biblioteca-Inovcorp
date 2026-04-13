@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Finalizar Compra</h1>

    <div class="flex flex-col lg:flex-row gap-8">
        <div class="lg:w-1/2">
            <div class="bg-white rounded-lg shadow-lg p-6" style="min-height: 450px;">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-truck text-purple-600 mr-2"></i>
                    Informações de Entrega
                </h2>
                
                <form action="{{ route('carrinho.processar') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="morada_entrega">
                            Morada de Entrega *
                        </label>
                        <input type="text" name="morada_entrega" id="morada_entrega" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                               required value="{{ old('morada_entrega') }}">
                        @error('morada_entrega')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="codigo_postal">
                                Código Postal *
                            </label>
                            <input type="text" name="codigo_postal" id="codigo_postal" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                   required value="{{ old('codigo_postal') }}" placeholder="1234-567">
                            @error('codigo_postal')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="cidade">
                                Cidade *
                            </label>
                            <input type="text" name="cidade" id="cidade" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                   required value="{{ old('cidade') }}">
                            @error('cidade')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="telefone">
                            Telefone (opcional)
                        </label>
                        <input type="tel" name="telefone" id="telefone" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                               value="{{ old('telefone') }}" placeholder="912345678">
                        @error('telefone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-purple-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-purple-700 transition flex items-center justify-center">
                        <i class="fab fa-stripe mr-2"></i>
                        Prosseguir para Pagamento
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:w-1/2">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24" style="min-height: 450px;">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-shopping-cart text-purple-600 mr-2"></i>
                    Resumo do Pedido
                </h2>

                <div class="space-y-4 max-h-96 overflow-y-auto mb-4">
                    @foreach($carrinho->itens as $item)
                    <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg hover:shadow-md transition">
                        <div class="w-16 h-24 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                            @if($item->livro->imagem_capa)
                                <img src="{{ asset('storage/' . $item->livro->imagem_capa) }}" 
                                     alt="{{ $item->livro->nome }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-book fa-2x"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800">{{ $item->livro->nome }}</h3>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-user mr-1"></i>
                                {{ $item->livro->autores->pluck('nome')->implode(', ') ?: 'Autor não informado' }}
                            </p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-sm text-gray-600">
                                    Quantidade: <span class="font-semibold">{{ $item->quantidade }}x</span>
                                </span>
                                <span class="text-purple-600 font-bold">
                                    € {{ number_format($item->subtotal, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal:</span>
                        <span>€ {{ number_format($carrinho->total, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Envio:</span>
                        <span class="text-green-600">Grátis</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold text-gray-800 pt-2 border-t">
                        <span>Total:</span>
                        <span class="text-purple-600">€ {{ number_format($carrinho->total, 2, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t text-center">
                    <div class="flex justify-center space-x-3">
                        <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                        <i class="fab fa-cc-mastercard text-2xl text-red-600"></i>
                        <i class="fab fa-cc-amex text-2xl text-blue-400"></i>
                        <i class="fab fa-cc-paypal text-2xl text-blue-500"></i>
                        <i class="fab fa-stripe text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .max-h-96::-webkit-scrollbar {
        width: 6px;
    }
    
    .max-h-96::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .max-h-96::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .max-h-96::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    @media (max-width: 768px) {
        .sticky {
            position: relative;
            top: 0;
        }
        
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
</style>
@endsection