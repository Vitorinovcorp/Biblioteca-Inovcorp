<div class="space-y-6">
    <!-- Barra de Pesquisa e Filtros -->
    <div class="bg-white p-6 rounded-lg shadow-md text-black">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Pesquisa -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pesquisar Editora</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Nome da editora..."
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

    <!-- Lista de Editoras -->
    <div class="space-y-4">
        @forelse($editoras as $editora)
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-4">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <!-- Logotipo -->
                    <div class="col-span-2">
                        @if($editora->logotipo)
                            <img src="{{ asset($editora->logotipo) }}" 
                                 alt="{{ $editora->nome }}" 
                                 class="w-16 h-16 object-contain border border-gray-200 rounded-lg p-1">
                        @else
                            <div class="w-16 h-16 bg-gray-200 flex items-center justify-center text-gray-400 rounded-lg">
                                <span class="text-3xl">🏢</span>
                            </div>
                        @endif
                    </div>

                    <!-- Nome -->
                    <div class="col-span-3">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $editora->nome }}</h3>
                    </div>

                    <!-- Livros Publicados -->
                    <div class="col-span-5">
                        @if($editora->livros->isNotEmpty())
                            <div class="flex flex-wrap gap-1">
                                @foreach($editora->livros->take(3) as $livro)
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                        {{ $livro->nome }}
                                    </span>
                                @endforeach
                                @if($editora->livros->count() > 3)
                                    <span class="text-xs text-gray-500">
                                        +{{ $editora->livros->count() - 3 }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Total: {{ $editora->livros->count() }} livros
                            </p>
                        @else
                            <span class="text-gray-400 text-sm">Nenhum livro publicado</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded-lg shadow-md text-center text-gray-500">
                Nenhuma editora encontrada.
            </div>
        @endforelse
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $editoras->links() }}
    </div>

    <!-- Estatísticas -->
    <div class="bg-white p-4 rounded-lg shadow-md text-sm text-gray-600">
        Mostrando {{ $editoras->firstItem() ?? 0 }} a {{ $editoras->lastItem() ?? 0 }} de {{ $editoras->total() }} editoras
    </div>
</div>