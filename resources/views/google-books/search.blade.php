@extends('layouts.app')

@section('content')
<div class="px-6 py-4">

    <!-- Card de Busca -->
    <div class="max-w-4xl mx-auto mb-6">
        <div class="bg-white shadow-md rounded-lg">
            <div class="bg-white text-center py-4 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Pesquisar na Google Books</h3>
            </div>
            <div class="p-4">
                <form method="POST" action="{{ route('google-books.do-search') }}" id="searchForm">
                    @csrf
                    <div class="flex justify-center gap-2 text-gray-800">
                        <input type="text"
                            name="q"
                            class="border rounded-lg p-2 w-full max-w-2xl text-center @error('q') border-red-500 @enderror"
                            placeholder="Digite título, autor ou ISBN"
                            value="{{ old('q', $query ?? '') }}"
                            required>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search"></i> Pesquisar
                        </button>
                    </div>
                    @error('q')
                    <div class="text-red-500 text-sm mt-2 text-center">{{ $message }}</div>
                    @enderror
                </form>
            </div>
        </div>
    </div>

    <!-- Grid de Livros -->
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Se houver resultados da pesquisa --}}
            @if(isset($results) && isset($results['items']) && count($results['items']) > 0)
            @foreach($results['items'] as $book)
            @php
            $thumbnail = $book['volumeInfo']['imageLinks']['thumbnail'] ??
            $book['volumeInfo']['imageLinks']['smallThumbnail'] ?? null;
            $volumeId = $book['id'];
            $title = $book['volumeInfo']['title'] ?? 'Sem título';
            $authors = implode(', ', $book['volumeInfo']['authors'] ?? ['Autor desconhecido']);
            $description = $book['volumeInfo']['description'] ?? 'Sem descrição';
            $publishedDate = $book['volumeInfo']['publishedDate'] ?? 'Data desconhecida';
            $isbn = null;
            foreach ($book['volumeInfo']['industryIdentifiers'] ?? [] as $identifier) {
            if ($identifier['type'] === 'ISBN_13') {
            $isbn = $identifier['identifier'];
            break;
            }
            }
            @endphp

            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col">
                @if($thumbnail)
                <img src="{{ $thumbnail }}" class="h-64 w-full object-contain p-4 bg-gray-50 rounded-t-xl" alt="{{ $title }}">
                @else
                <div class="h-64 flex items-center justify-center bg-gray-100 text-gray-400 rounded-t-xl">
                    <i class="fas fa-book fa-2x"></i>
                </div>
                @endif
                <div class="p-4 flex flex-col flex-1">
                    <h5 class="text-gray-800 font-semibold text-lg mb-2 line-clamp-2">{{ Str::limit($title, 60) }}</h5>
                    <p class="text-gray-500 text-sm mb-1 line-clamp-1"><i class="fas fa-user"></i> {{ Str::limit($authors, 50) }}</p>
                    <p class="text-gray-400 text-sm mb-2"><i class="fas fa-calendar"></i> {{ $publishedDate }}</p>
                    @if($isbn)
                    <p class="text-gray-400 text-sm mb-2"><i class="fas fa-barcode"></i> ISBN: {{ $isbn }}</p>
                    @endif
                    <div class="mt-auto pt-2">
                        <button type="button" class="bg-blue-600 text-white w-full py-2 rounded-lg hover:bg-blue-700 transition"
                            data-bs-toggle="modal" data-bs-target="#importModal"
                            data-volume-id="{{ $volumeId }}"
                            data-title="{{ addslashes($title) }}"
                            data-authors="{{ addslashes($authors) }}"
                            data-isbn="{{ $isbn }}"
                            data-description="{{ addslashes($description) }}">
                            <i class="fas fa-download"></i> Importar Livro
                        </button>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Livros de exemplo caso não haja pesquisa --}}
            @else
            @php
            $exampleBooks = [
            [
            'title' => 'Collectables Price Guide 2007',
            'authors' => 'Judith Miller',
            'thumbnail' => 'https://covers.openlibrary.org/b/id/10407230-L.jpg',
            'publishedDate' => '2007',
            'isbn' => '9788594319584',
            ],
            [
            'title' => 'The Wise Man Fear',
            'authors' => 'Patrick Rothfuss',
            'thumbnail' => 'https://covers.openlibrary.org/b/id/8155411-L.jpg',
            'publishedDate' => '2011',
            'isbn' => '9788491050274',
            ],
            [
            'title' => '1984',
            'authors' => 'George Orwell',
            'thumbnail' => 'https://covers.openlibrary.org/b/id/7222246-L.jpg',
            'publishedDate' => '1949',
            'isbn' => '9780451524935',
            ],
            ];
            @endphp

            @foreach($exampleBooks as $book)
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col">
                <img src="{{ $book['thumbnail'] }}" class="h-64 w-full object-contain p-4 bg-gray-50 rounded-t-xl" alt="{{ $book['title'] }}">
                <div class="p-4 flex flex-col flex-1">
                    <h5 class="text-gray-800 font-semibold text-lg mb-2">{{ $book['title'] }}</h5>
                    <p class="text-gray-500 text-sm mb-1"><i class="fas fa-user"></i> {{ $book['authors'] }}</p>
                    <p class="text-gray-400 text-sm mb-2"><i class="fas fa-calendar"></i> {{ $book['publishedDate'] }}</p>
                    <p class="text-gray-400 text-sm mb-2"><i class="fas fa-barcode"></i> ISBN: {{ $book['isbn'] }}</p>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Modal de Importação -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-blue-600 text-white">
                <h5 class="modal-title"><i class="fas fa-download"></i> Importar Livro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="importForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="volume_id" id="volume_id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nome" class="block font-semibold mb-1">Nome do Livro *</label>
                            <input type="text" id="nome" name="nome" class="border rounded w-full p-2" required>
                        </div>
                        <div>
                            <label for="isbn" class="block font-semibold mb-1">ISBN</label>
                            <input type="text" id="isbn" name="isbn" class="border rounded w-full p-2">
                        </div>
                        <div>
                            <label for="preco" class="block font-semibold mb-1">Preço *</label>
                            <input type="number" step="0.01" id="preco" name="preco" class="border rounded w-full p-2" required>
                        </div>
                        <div>
                            <label for="editora_id" class="block font-semibold mb-1">Editora *</label>
                            <select id="editora_id" name="editora_id" class="border rounded w-full p-2" required>
                                <option value="">Selecione uma editora</option>
                                @foreach($editoras ?? [] as $editora)
                                <option value="{{ $editora->id }}">{{ $editora->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="autores" class="block font-semibold mb-1">Autores</label>
                            <select id="autores" name="autores[]" multiple size="4" class="border rounded w-full p-2">
                                @foreach($autores ?? [] as $autor)
                                <option value="{{ $autor->id }}">{{ $autor->nome }}</option>
                                @endforeach
                            </select>
                            <p class="text-gray-500 text-sm mt-1">Pressione Ctrl para selecionar múltiplos autores</p>
                        </div>
                        <div class="md:col-span-2">
                            <label for="bibliografia" class="block font-semibold mb-1">Descrição</label>
                            <textarea id="bibliografia" name="bibliografia" rows="4" class="border rounded w-full p-2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex justify-end gap-2 mt-4">
                    <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Importar Livro</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const importModal = document.getElementById('importModal');
        const importForm = document.getElementById('importForm');

        if (importModal) {
            importModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                document.getElementById('volume_id').value = button.getAttribute('data-volume-id');
                document.getElementById('nome').value = button.getAttribute('data-title');
                document.getElementById('isbn').value = button.getAttribute('data-isbn');
                document.getElementById('bibliografia').value = button.getAttribute('data-description');
                const autorSelect = document.getElementById('autores');
                const authors = button.getAttribute('data-authors').split(', ');
                for (let option of autorSelect.options) {
                    option.selected = authors.includes(option.text);
                }
            });
        }

        if (importForm) {
            importForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch('{{ route("google-books.import") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert('Erro ao importar livro: ' + (data.error || 'Erro desconhecido'));
                    }
                }).catch(err => {
                    console.error(err);
                    alert('Erro ao importar livro. Tente novamente.');
                });
            });
        }
    });
</script>
@endpush
@endsection