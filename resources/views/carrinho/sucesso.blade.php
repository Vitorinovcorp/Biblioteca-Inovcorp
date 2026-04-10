@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto text-center">
        <div class="bg-green-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check-circle text-green-600 text-5xl"></i>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-4">Pagamento Confirmado!</h1>
        <p class="text-gray-600 mb-8">Obrigado pela sua compra. Seu pedido foi processado com sucesso.</p>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 text-left">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Detalhes do Pedido</h2>
            <p class="text-gray-600 mb-2"><strong>Número do Pedido:</strong> {{ $encomenda->numero_encomenda }}</p>
            <p class="text-gray-600 mb-2"><strong>Data:</strong> {{ $encomenda->created_at->format('d/m/Y H:i') }}</p>
            <p class="text-gray-600 mb-4"><strong>Total:</strong> € {{ number_format($encomenda->total, 2, ',', '.') }}</p>

            <h3 class="font-semibold text-gray-800 mb-2">Itens Comprados:</h3>
            <div class="space-y-2">
                @foreach($encomenda->itens as $item)
                <div class="flex justify-between">
                    <span>{{ $item->quantidade }}x {{ $item->livro->nome }}</span>
                    <span>€ {{ number_format($item->preco_unitario * $item->quantidade, 2, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="border-t mt-4 pt-4">
                <h3 class="font-semibold text-gray-800 mb-2">Morada de Entrega:</h3>
                <p class="text-gray-600">{{ $encomenda->morada_entrega }}</p>
                <p class="text-gray-600">{{ $encomenda->codigo_postal }}, {{ $encomenda->cidade }}</p>
                @if($encomenda->telefone)
                    <p class="text-gray-600">Tel: {{ $encomenda->telefone }}</p>
                @endif
            </div>
        </div>

        <div class="flex justify-center space-x-4">
            <a href="{{ route('livros.index') }}" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                Continuar Comprando
            </a>
            <a href="{{ route('encomendas.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                Ver Minhas Encomendas
            </a>
        </div>
    </div>
</div>
@endsection