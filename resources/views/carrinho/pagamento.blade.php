@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Finalizar Pagamento</h1>

    @if(!isset($encomenda) || !$encomenda)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <p>Encomenda não encontrada. <a href="{{ route('carrinho.index') }}" class="underline">Voltar ao carrinho</a></p>
        </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-shopping-cart text-purple-600 mr-2"></i>
                Resumo do Pedido
            </h2>
            
            <div class="space-y-4 max-h-96 overflow-y-auto mb-4">
                @foreach($encomenda->itens as $item)
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                    <div class="w-16 h-20 bg-gray-200 rounded overflow-hidden flex-shrink-0">
                        @if($item->livro && $item->livro->imagem_capa)
                            <img src="{{ asset('storage/' . $item->livro->imagem_capa) }}" 
                                 alt="{{ $item->livro->nome }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-book text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800">{{ $item->livro->nome ?? 'Livro não disponível' }}</h3>
                        <p class="text-sm text-gray-500">{{ $item->livro->autores->pluck('nome')->implode(', ') ?? 'Autor não informado' }}</p>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm">{{ $item->quantidade }}x € {{ number_format($item->preco_unitario, 2, ',', '.') }}</span>
                            <span class="font-bold text-purple-600">€ {{ number_format($item->subtotal, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="border-t pt-4 space-y-2">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal:</span>
                    <span>€ {{ number_format($encomenda->total, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Envio:</span>
                    <span class="text-green-600">Grátis</span>
                </div>
                <div class="flex justify-between text-xl font-bold text-gray-800 pt-2 border-t">
                    <span>Total:</span>
                    <span class="text-purple-600">€ {{ number_format($encomenda->total, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fab fa-stripe text-purple-600 mr-2"></i>
                Informações de Pagamento
            </h2>

            <form id="payment-form">
                @csrf
                <input type="hidden" id="encomenda_id" value="{{ $encomenda->id }}">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" value="{{ auth()->user()->email }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                           readonly>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Informações do Cartão</label>
                    <div id="card-element" class="border border-gray-300 rounded-lg p-3 bg-white"></div>
                    <div id="card-errors" class="text-red-500 text-xs mt-1"></div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nome no Cartão</label>
                    <input type="text" id="cardholder-name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none"
                           placeholder="Nome completo como está no cartão"
                           required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">País</label>
                    <select id="country" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none">
                        <option value="PT">Portugal</option>
                        <option value="BR">Brasil</option>
                        <option value="AO">Angola</option>
                        <option value="CV">Cabo Verde</option>
                        <option value="MZ">Moçambique</option>
                    </select>
                </div>

                <button type="submit" id="submit-button" class="w-full bg-purple-600 text-white font-bold py-3 rounded-lg hover:bg-purple-700 transition">
                    <i class="fab fa-stripe mr-2"></i>
                    Pagar € {{ number_format($encomenda->total, 2, ',', '.') }}
                </button>
            </form>

            <div class="mt-6 pt-4 border-t text-center">
                <div class="flex justify-center space-x-3">
                    <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                    <i class="fab fa-cc-mastercard text-2xl text-red-600"></i>
                    <i class="fab fa-cc-amex text-2xl text-blue-400"></i>
                    <i class="fab fa-cc-paypal text-2xl text-blue-500"></i>
                </div>
                <p class="text-xs text-gray-500 mt-2">Pagamento 100% seguro via Stripe</p>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ env('STRIPE_KEY') }}');
    const elements = stripe.elements();
    
    const style = {
        base: {
            color: '#32325d',
            fontFamily: '"Figtree", "Helvetica Neue", sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': { color: '#aab7c4' }
        },
        invalid: { color: '#fa755a', iconColor: '#fa755a' }
    };
    
    const card = elements.create('card', { style: style });
    card.mount('#card-element');
    
    card.addEventListener('change', function(event) {
        document.getElementById('card-errors').textContent = event.error ? event.error.message : '';
    });
    
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processando...';
        
        const cardholderName = document.getElementById('cardholder-name').value;
        
        if (!cardholderName) {
            document.getElementById('card-errors').textContent = 'Por favor, insira o nome no cartão.';
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fab fa-stripe mr-2"></i> Pagar € {{ number_format($encomenda->total, 2, ',', '.') }}';
            return;
        }
        
        const { paymentMethod, error } = await stripe.createPaymentMethod('card', card, {
            billing_details: {
                name: cardholderName,
                email: document.getElementById('email').value,
            }
        });
        
        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fab fa-stripe mr-2"></i> Pagar € {{ number_format($encomenda->total, 2, ',', '.') }}';
        } else {
            const response = await fetch('{{ route("carrinho.processar-pagamento") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    payment_method_id: paymentMethod.id,
                    encomenda_id: document.getElementById('encomenda_id').value
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                window.location.href = '{{ route("carrinho.sucesso", ["encomenda" => $encomenda->id]) }}';
            } else {
                document.getElementById('card-errors').textContent = result.error;
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fab fa-stripe mr-2"></i> Pagar € {{ number_format($encomenda->total, 2, ',', '.') }}';
            }
        }
    });
</script>
@endsection