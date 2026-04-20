@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/3 p-6">
                @if($livro->imagem_capa)
                @php
                $imagePath = $livro->imagem_capa;
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
                <img src="{{ $imageUrl }}" alt="{{ $livro->nome }}" class="w-full rounded-lg shadow-md" onerror="this.src='https://placehold.co/400x600?text={{ __('messages.no_image') }}'">
                @else
                <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book fa-4x text-gray-400"></i>
                </div>
                @endif
            </div>

            <div class="md:w-2/3 p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $livro->nome }}</h1>

                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-user mr-2"></i> {{ __('messages.authors') }}:</span>
                    @if($livro->autores->count() > 0)
                    @foreach($livro->autores as $autor)
                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2 text-sm">
                        {{ $autor->nome }}
                    </span>
                    @endforeach
                    @else
                    <span class="text-gray-500">{{ __('messages.not_informed') }}</span>
                    @endif
                </div>

                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-building mr-2"></i> {{ __('messages.publisher') }}:</span>
                    <span class="text-gray-800">{{ $livro->editora->nome ?? __('messages.not_informed') }}</span>
                </div>

                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-barcode mr-2"></i> {{ __('messages.isbn') }}:</span>
                    <span class="text-gray-800">{{ $livro->isbn ?? __('messages.not_informed') }}</span>
                </div>

                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-dollar-sign mr-2"></i> {{ __('messages.price') }}:</span>
                    <span class="text-2xl font-bold text-green-600">€ {{ number_format($livro->preco, 2, ',', '.') }}</span>
                </div>

                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-boxes mr-2"></i> {{ __('messages.stock') }}:</span>
                    <span class="text-xl font-semibold {{ $livro->quantidade > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $livro->quantidade }}
                    </span>
                </div>

                @if($livro->bibliografia)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ __('messages.description') }}:</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $livro->bibliografia }}</p>
                </div>
                @endif

                <div class="flex gap-4 mt-6">
                    @auth
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('livros.edit', $livro->id) }}" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 transition">
                        <i class="fas fa-edit mr-2"></i> {{ __('messages.edit_book') }}
                    </a>

                    <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition" onclick="return confirm('{{ __('messages.delete_confirmation') }}')">
                            <i class="fas fa-trash mr-2"></i> {{ __('messages.delete_book') }}
                        </button>
                    </form>
                    @endif

                    @if($livro->quantidade > 0)
                    <form action="{{ route('carrinho.adicionar', $livro->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
                            <i class="fas fa-cart-plus mr-2"></i> {{ __('messages.add_to_cart') }}
                        </button>
                    </form>
                    @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

@if($livro->reviewsAtivas()->count() > 0)
<div class="mt-8 max-w-4xl mx-auto">
    <h3 class="text-2xl font-bold mb-4 text-center">{{ __('messages.reader_reviews') }}</h3>
    <div class="space-y-4">
        @foreach($livro->reviewsAtivas()->with('user')->get() as $review)
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <span class="font-semibold">{{ $review->user->name }}</span>
                    <span class="text-sm text-gray-500 ml-2">{{ $review->created_at->format('d/m/Y') }}</span>
                </div>
                @if($review->rating)
                <div class="flex">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                    @endfor
                </div>
                @endif
            </div>
            <p class="text-gray-700">{{ $review->review }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection