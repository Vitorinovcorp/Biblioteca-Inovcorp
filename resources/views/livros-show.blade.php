@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/3 p-6">
                @if($livro->imagem_capa)
                @php
                // Garante o caminho correto da imagem
                $imagePath = $livro->imagem_capa;
                // Se já começa com storage/, mantém
                if (str_starts_with($imagePath, 'storage/')) {
                $imageUrl = asset($imagePath);
                }
                // Se começa com imagens/, adiciona storage/
                elseif (str_starts_with($imagePath, 'imagens/')) {
                $imageUrl = asset('storage/' . $imagePath);
                }
                // Se começa com /, remove a barra
                elseif (str_starts_with($imagePath, '/')) {
                $imageUrl = asset('storage' . $imagePath);
                }
                // Caso contrário, assume que está em storage/imagens/livros
                else {
                $imageUrl = asset('storage/imagens/livros/' . basename($imagePath));
                }
                @endphp
                <img src="{{ $imageUrl }}"
                    alt="{{ $livro->nome }}"
                    class="w-full rounded-lg shadow-md"
                    onerror="this.src='https://placehold.co/400x600?text=Sem+Imagem'">
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
                    <span class="text-gray-800">{{ $livro->isbn ?? 'Nao informado' }}</span>
                </div>

                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-dollar-sign mr-2"></i> Preço:</span>
                    <span class="text-2xl font-bold text-green-600">€ {{ number_format($livro->preco, 2, ',', '.') }}</span>
                </div>

                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-boxes mr-2"></i> Quantidade em estoque:</span>
                    <span class="text-xl font-semibold {{ $livro->quantidade > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $livro->quantidade }}
                    </span>
                </div>

                {{-- Disponibilidade e Botão de Notificação --}}
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-check-circle mr-2"></i> Disponibilidade:</span>

                    @if($disponivelAgora && $livro->quantidade > 0)
                    <div>
                        <span class="inline-block px-3 py-1 rounded text-sm font-semibold bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Disponível para empréstimo
                        </span>
                    </div>
                    @else
                    <div class="flex flex-col space-y-3">
                        <span class="inline-block px-3 py-1 rounded text-sm font-semibold bg-red-100 text-red-800 w-fit">
                            <i class="fas fa-times-circle mr-1"></i> Indisponível no momento
                        </span>

                        @auth
                        @if(Auth::user()->role !== 'admin')
                        <div id="notification-area">
                            <button id="btn-notificar"
                                class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm"
                                onclick="solicitarNotificacao('{{ $livro->id }}')">
                                <i class="fas fa-bell mr-2"></i>
                                Notificar-me quando disponível
                            </button>

                            <button id="btn-cancelar"
                                class="hidden inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm"
                                onclick="cancelarNotificacao('{{ $livro->id }}')">
                                <i class="fas fa-bell-slash mr-2"></i>
                                Cancelar notificação
                            </button>

                            <p id="mensagem-notificacao" class="text-sm mt-2"></p>
                        </div>
                        @endif
                        @endauth
                    </div>
                    @endif
                </div>

                @if($livro->bibliografia)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Descrição:</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $livro->bibliografia }}</p>
                </div>
                @endif
               
                    
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
</div>

{{-- Seção de Avaliações Centralizada --}}
<div id="reviews" class="mt-12 max-w-4xl mx-auto">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-star text-yellow-500 mr-2"></i>
            Avaliação dos Leitores
        </h2>
        @if(isset($totalReviews) && $totalReviews > 0)
        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm inline-block mt-2">
            {{ $totalReviews }} {{ $totalReviews == 1 ? 'avaliacao' : 'avaliacoes' }}
        </span>
        @endif
    </div>

    @if(isset($totalReviews) && $totalReviews > 0)
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-8 text-center">
        <div class="text-5xl font-bold text-gray-800">{{ number_format($mediaRating, 1) }}</div>
        <div class="flex items-center justify-center mt-2">
            @php
            $roundedRating = round($mediaRating);
            @endphp
            @for($i = 1; $i <= 5; $i++)
                @if($i <=$roundedRating)
                <i class="fas fa-star text-xl text-yellow-400"></i>
                @else
                <i class="fas fa-star text-xl text-gray-300"></i>
                @endif
                @endfor
        </div>
        <p class="text-sm text-gray-500 mt-1">Média de {{ $totalReviews }} Avaliação</p>
    </div>

    <div class="space-y-6">
        @foreach($livro->reviews as $review)
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $review->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $review->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                @if($review->rating)
                <div class="flex items-center bg-yellow-50 px-3 py-1 rounded-full">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <=$review->rating)
                        <i class="fas fa-star text-sm text-yellow-400"></i>
                        @else
                        <i class="fas fa-star text-sm text-gray-300"></i>
                        @endif
                        @endfor
                        <span class="ml-2 text-sm font-semibold text-gray-700">{{ $review->rating }}/5</span>
                </div>
                @endif
            </div>

            <div class="mt-3">
                <p class="text-gray-700 leading-relaxed">{{ $review->review }}</p>
            </div>

            <div class="mt-4 flex items-center text-xs text-green-600">
                <i class="fas fa-check-circle mr-1"></i>
                <span>Review verificada</span>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-gray-50 rounded-lg p-8 text-center">
        <i class="fas fa-comment-dots text-5xl text-gray-400 mb-3"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">Este livro ainda não possui avaliação</h3>
        <p class="text-gray-500">Seja o primeiro a avaliar este livro após requisitá-lo e devolvê-lo!</p>
        @auth
        <a href="{{ route('requisicoes.create') }}" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
            <i class="fas fa-hand-paper mr-2"></i> Requisitar este livro
        </a>
        @endauth
    </div>
    @endif
</div>

{{-- Seção de Livros Recomendados Centralizada --}}
@if(isset($recommendations) && $recommendations->count() > 0)
<div class="mt-12 max-w-7xl mx-auto">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-brain text-purple-500 mr-2"></i>
            Livros Recomendados para Você
        </h2>
        <p class="text-gray-600 mt-1">
            Baseado na descrição e características deste livro, sugerimos estas leituras relacionadas:
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($recommendations as $recommendation)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
            <a href="{{ route('livros.show', $recommendation->id) }}" class="block">
                <div class="relative h-64 overflow-hidden">
                    @if($recommendation->imagem_capa)
                    @php
                    $imagePath = $recommendation->imagem_capa;
                    if (str_starts_with($imagePath, 'storage/')) {
                    $imageUrl = asset($imagePath);
                    } elseif (str_starts_with($imagePath, 'imagens/')) {
                    $imageUrl = asset('storage/' . $imagePath);
                    } elseif (str_starts_with($imagePath, '/')) {
                    $imageUrl = asset('storage' . $imagePath);
                    } else {
                    $imageUrl = asset('storage/imagens/livros/' . basename($imagePath));
                    }
                    @endphp
                    <img src="{{ $imageUrl }}"
                        alt="{{ $recommendation->nome }}"
                        class="mx-auto h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='https://placehold.co/400x600?text=Sem+Imagem'">
                    @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-book fa-4x text-gray-400"></i>
                    </div>
                    @endif
                </div>

                <div class="p-4">
                    <h3 class="font-bold text-gray-800 mb-2 line-clamp-2">{{ $recommendation->nome }}</h3>

                    @if($recommendation->autores->count() > 0)
                    <p class="text-sm text-gray-600 mb-2">
                        <i class="fas fa-user mr-1"></i>
                        {{ $recommendation->autores->take(2)->pluck('nome')->implode(', ') }}
                        @if($recommendation->autores->count() > 2)
                        <span class="text-gray-400"> +{{ $recommendation->autores->count() - 2 }}</span>
                        @endif
                    </p>
                    @endif

                    <div class="flex items-center justify-between mt-3">
                        <span class="text-lg font-bold text-green-600">
                            € {{ number_format($recommendation->preco, 2, ',', '.') }}
                        </span>

                        @if($recommendation->quantidade > 0)
                        <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">
                            <i class="fas fa-check-circle"></i> Disponível
                        </span>
                        @else
                        <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded-full">
                            <i class="fas fa-times-circle"></i> Indisponível
                        </span>
                        @endif
                    </div>

                    @if(isset($similarityScores[$recommendation->id]) && $similarityScores[$recommendation->id] > 0.3)
                    <div class="mt-2 text-xs text-purple-600">
                        <i class="fas fa-chart-line mr-1"></i>
                        {{ round($similarityScores[$recommendation->id] * 100) }}% de similaridade
                    </div>
                    @endif
                </div>
            </a>
        </div>
        @endforeach
    </div>

    {{-- Link para ver mais recomendações --}}
    <div class="text-center mt-6">
        <a href="{{ route('livros.recommendations', $livro->id) }}"
            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
            <i class="fas fa-magic mr-2"></i>
            Ver mais recomendações inteligentes
        </a>
    </div>
</div>
@endif

@auth
@if(Auth::user()->role === 'admin' && isset($historico) && $historico->count() > 0)
<div class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden max-w-7xl mx-auto">
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
                        @if($requisicao->status === 'aprovada')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aprovada</span>
                        @elseif($requisicao->status === 'pendente')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                        @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejeitada</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endauth

@auth
@if(Auth::user()->role !== 'admin')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        verificarStatusInscricao('{{ $livro->id }}');
    });

    function verificarStatusInscricao(livroId) {
        fetch('/livros/' + livroId + '/check-subscription', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.is_subscribed) {
                    document.getElementById('btn-notificar').classList.add('hidden');
                    document.getElementById('btn-cancelar').classList.remove('hidden');
                    document.getElementById('mensagem-notificacao').innerHTML =
                        '<i class="fas fa-check-circle text-green-500 mr-1"></i> Você receberá um email quando o livro ficar disponível.';
                    document.getElementById('mensagem-notificacao').classList.add('text-green-600');
                }
            })
            .catch(function(error) {
                console.error('Erro:', error);
            });
    }

    function solicitarNotificacao(livroId) {
        var btn = document.getElementById('btn-notificar');
        var mensagem = document.getElementById('mensagem-notificacao');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processando...';

        fetch('/livros/' + livroId + '/notificar', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    document.getElementById('btn-notificar').classList.add('hidden');
                    document.getElementById('btn-cancelar').classList.remove('hidden');
                    mensagem.innerHTML = '<i class="fas fa-check-circle text-green-500 mr-1"></i> ' + data.message;
                    mensagem.classList.add('text-green-600');
                } else {
                    mensagem.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> ' + data.message;
                    mensagem.classList.add('text-red-600');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-bell mr-2"></i> Notificar-me quando disponível';
                }
            })
            .catch(function(error) {
                console.error('Erro:', error);
                mensagem.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> Erro ao processar solicitação.';
                mensagem.classList.add('text-red-600');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-bell mr-2"></i> Notificar-me quando disponível';
            });
    }

    function cancelarNotificacao(livroId) {
        var btn = document.getElementById('btn-cancelar');
        var mensagem = document.getElementById('mensagem-notificacao');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Cancelando...';

        fetch('/livros/' + livroId + '/cancelar-notificacao', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    document.getElementById('btn-notificar').classList.remove('hidden');
                    document.getElementById('btn-cancelar').classList.add('hidden');
                    mensagem.innerHTML = '<i class="fas fa-info-circle text-blue-500 mr-1"></i> ' + data.message;
                    mensagem.classList.add('text-blue-600');
                    setTimeout(function() {
                        mensagem.innerHTML = '';
                        mensagem.classList.remove('text-blue-600');
                    }, 3000);
                } else {
                    mensagem.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> ' + data.message;
                    mensagem.classList.add('text-red-600');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-bell-slash mr-2"></i> Cancelar notificação';
                }
            })
            .catch(function(error) {
                console.error('Erro:', error);
                mensagem.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> Erro ao cancelar solicitação.';
                mensagem.classList.add('text-red-600');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-bell-slash mr-2"></i> Cancelar notificação';
            });
    }
</script>
@endif
@endauth
@endsection