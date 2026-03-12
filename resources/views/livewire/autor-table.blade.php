<div class="space-y-6" wire:poll.60s>
    <!-- Barra de Pesquisa e Filtros -->
    <div class="bg-white p-6 rounded-lg shadow-md text-black">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Pesquisa -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pesquisar Autor</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Nome do autor..."
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Filtro por Livro -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filtrar por Livro</label>
                <select wire:model.live="filtroLivro" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos os livros</option>
                    @foreach($livros as $livro)
                        <option value="{{ $livro->id }}">{{ $livro->nome }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botão Limpar Filtros -->
            <div class="flex items-end">
                <button wire:click="limparFiltros" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors duration-200">
                    Limpar Filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Cabeçalho da Lista com Ordenação -->
    <div class="bg-white p-4 rounded-lg shadow-md">
        <div class="grid grid-cols-12 gap-4 font-medium text-gray-700">
            <div class="col-span-2 cursor-pointer hover:text-blue-600" wire:click="sortBy('foto')">
                Foto
            </div>
            <div class="col-span-3 cursor-pointer hover:text-blue-600" wire:click="sortBy('nome')">
                Nome
                @if($ordenarPor === 'nome')
                    <span class="ml-1">{{ $ordenarDirecao === 'asc' ? '↑' : '↓' }}</span>
                @endif
            </div>
            <div class="col-span-4">Livros Publicados</div>
        </div>
    </div>

    <!-- Lista de Autores -->
    <div class="space-y-4">
        @forelse($autores as $autor)
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-4">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <!-- Foto -->
                    <div class="col-span-2">
                        @if($autor->foto)
                            <img src="{{ asset($autor->foto) }}" 
                                 alt="{{ $autor->nome }}" 
                                 class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 border-2 border-gray-200">
                                <span class="text-2xl font-bold">{{ substr($autor->nome, 0, 2) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Nome -->
                    <div class="col-span-3">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $autor->nome }}</h3>
                    </div>

                    <!-- Livros Publicados -->
                    <div class="col-span-4">
                        @if($autor->livros->isNotEmpty())
                            <div class="flex flex-wrap gap-1">
                                @foreach($autor->livros->take(3) as $livro)
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                        {{ $livro->nome }}
                                    </span>
                                @endforeach
                                @if($autor->livros->count() > 3)
                                    <span class="text-xs text-gray-500">
                                        +{{ $autor->livros->count() - 3 }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">Nenhum livro publicado</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded-lg shadow-md text-center text-gray-500">
                Nenhum autor encontrado.
            </div>
        @endforelse
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $autores->links() }}
    </div>

    <!-- Estatísticas -->
    <div class="bg-white p-4 rounded-lg shadow-md text-sm text-gray-600">
        Mostrando {{ $autores->firstItem() ?? 0 }} a {{ $autores->lastItem() ?? 0 }} de {{ $autores->total() }} autores
    </div>
</div>