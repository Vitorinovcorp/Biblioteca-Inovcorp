@extends('layouts.app')



@section('content')
<div class="px-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h3 class="text-2xl font-semibold mt-3 text-gray-800">
            Resultados para: "{{ $query }}"
        </h3>
        <p class="text-gray-600">
            Encontrados {{ $results['totalItems'] ?? 0 }} livros
        </p>
    </div>

    {{-- GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-gray-800" >
        @forelse($results['items'] ?? [] as $book)

            @php
                $thumbnail = $book['volumeInfo']['imageLinks']['thumbnail'] ?? 
                             $book['volumeInfo']['imageLinks']['smallThumbnail'] ?? null;
            @endphp

            <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col">

                {{-- IMAGEM --}}
                @if($thumbnail)
                    <img src="{{ $thumbnail }}" 
                         class="h-64 object-contain p-4 bg-gray-50 rounded-t-xl"
                         alt="{{ $book['volumeInfo']['title'] ?? 'Capa' }}">
                @else
                    <div class="h-64 flex items-center justify-center bg-gray-100 text-gray-400">
                        Sem imagem
                    </div>
                @endif

                {{-- CONTEÚDO --}}
                <div class="p-4 flex flex-col flex-1 text-center">

                    <h2 class="text-sm font-semibold mb-2 line-clamp-2">
                        {{ $book['volumeInfo']['title'] ?? 'Sem título' }}
                    </h2>

                    <p class="text-xs text-gray-500 mb-2">
                        {{ implode(', ', $book['volumeInfo']['authors'] ?? ['Autor desconhecido']) }}
                    </p>

                    <p class="text-xs text-gray-400 mb-3 line-clamp-3">
                        {{ $book['volumeInfo']['description'] ?? 'Sem descrição' }}
                    </p>

                    <div class="mt-auto">
                        <p class="text-xs text-gray-400 mb-2">
                            {{ $book['volumeInfo']['publishedDate'] ?? 'Data desconhecida' }}
                        </p>

                        <a href="{{ route('google-books.import-form', $book['id']) }}" 
                           class="inline-flex items-center justify-center bg-indigo-600 text-gray-800 text-xs px-4 py-1.5 rounded-full transition">
                            <i class="fas fa-download mr-1"></i> Importar
                        </a>
                    </div>

                </div>
            </div>

        @empty
            <div class="col-span-3">
                <div class="bg-blue-100 text-blue-700 p-4 rounded">
                    Nenhum resultado encontrado para "{{ $query }}".
                </div>
            </div>
        @endforelse
    </div>

    {{-- LOAD MORE --}}
    @if(($results['totalItems'] ?? 0) > count($results['items'] ?? []))
        <div class="mt-6 text-center">
            <button class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded hover:bg-indigo-600 hover:text-white transition" id="loadMore">
                Carregar mais resultados
            </button>
        </div>
    @endif

</div>
@endsection