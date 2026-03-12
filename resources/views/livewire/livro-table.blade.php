<div class="space-y-6" wire:poll.60s>
  
    <div class="bg-white p-6 rounded-lg shadow-md text-black">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pesquisar</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Nome, ISBN ou bibliografia..."
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 ">
            </div>

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Editora</label>
                <select wire:model.live="filtroEditora" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todas as editoras</option>
                    @foreach($editoras as $editora)
                        <option value="{{ $editora->id }}">{{ $editora->nome }}</option>
                    @endforeach
                </select>
            </div>

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                <select wire:model.live="filtroAutor" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos os autores</option>
                    @foreach($autores as $autor)
                        <option value="{{ $autor->id }}">{{ $autor->nome }}</option>
                    @endforeach
                </select>
            </div>

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Preço mínimo (€)</label>
                <input type="number" 
                       wire:model.live="filtroPrecoMin" 
                       step="0.01"
                       min="0"
                       placeholder="0.00"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Preço máximo (€)</label>
                <input type="number" 
                       wire:model.live="filtroPrecoMax" 
                       step="0.01"
                       min="0"
                       placeholder="999.99"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

           
            <div class="flex items-end">
                <button wire:click="limparFiltros" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors duration-200">
                    Limpar Filtros
                </button>
            </div>
        </div>
    </div>

    
    <div class="bg-white p-4 rounded-lg shadow-md">
        <div class="grid grid-cols-12 gap-4 font-medium text-gray-700">
            <div class="col-span-2 cursor-pointer hover:text-blue-600" wire:click="sortBy('imagem_capa')">
                Capa
            </div>
            <div class="col-span-2 cursor-pointer hover:text-blue-600" wire:click="sortBy('nome')">
                Nome
                @if($sortField === 'nome')
                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                @endif
            </div>
            <div class="col-span-2 cursor-pointer hover:text-blue-600" wire:click="sortBy('isbn')">
                ISBN
                @if($sortField === 'isbn')
                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                @endif
            </div>
            <div class="col-span-2">Bibliografia</div>
            <div class="col-span-1 cursor-pointer hover:text-blue-600 text-right" wire:click="sortBy('preco')">
                Preço
                @if($sortField === 'preco')
                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                @endif
            </div>
            <div class="col-span-2">Editora</div>
            <div class="col-span-1">Autores</div>
        </div>
    </div>

    
    <div class="space-y-4">
        @forelse($livros as $livro)
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-4">
                <div class="grid grid-cols-12 gap-4 items-center">
                    
                    <div class="col-span-2">
                        @if($livro->imagem_capa)
                            <img src="{{ asset($livro->imagem_capa) }}" 
                                 alt="{{ $livro->nome }}" 
                                 class="w-20 h-24 object-cover rounded">
                        @else
                            <div class="w-20 h-24 bg-gray-200 flex items-center justify-center text-gray-400 rounded">
                                <span class="text-3xl">📚</span>
                            </div>
                        @endif
                    </div>

                    
                    <div class="col-span-2 font-semibold text-gray-800 break-words">
                        {{ $livro->nome }}
                    </div>

                  
                    <div class="col-span-2 text-gray-600">
                        {{ $livro->isbn }}
                    </div>

                    
                    <div class="col-span-2 text-gray-600 text-sm">
                        {{ Str::limit($livro->bibliografia, 50) }}
                    </div>

                   
                    <div class="col-span-1 text-right font-bold text-green-600">
                        €{{ number_format($livro->preco, 2, ',', '.') }}
                    </div>

                    
                    <div class="col-span-2">
                        <span class="bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded">
                            {{ $livro->editora->nome ?? 'N/A' }}
                        </span>
                    </div>

                    
                    <div class="col-span-1">
                        @if($livro->autores->isNotEmpty())
                            <div class="flex flex-col gap-1">
                                @foreach($livro->autores->take(2) as $autor)
                                    <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">
                                        {{ $autor->nome }}
                                    </span>
                                @endforeach
                                @if($livro->autores->count() > 2)
                                    <span class="text-xs text-gray-500">
                                        +{{ $livro->autores->count() - 2 }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">Sem autores</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded-lg shadow-md text-center text-gray-500">
                Nenhum livro encontrado.
            </div>
        @endforelse
    </div>

    
    <div class="mt-6">
        {{ $livros->links() }}
    </div>

    
    <div class="bg-white p-4 rounded-lg shadow-md text-sm text-gray-600">
        Mostrando {{ $livros->firstItem() ?? 0 }} a {{ $livros->lastItem() ?? 0 }} de {{ $livros->total() }} livros
    </div>
</div>