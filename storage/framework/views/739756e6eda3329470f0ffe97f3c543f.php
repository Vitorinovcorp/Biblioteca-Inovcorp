<div class="space-y-6" wire:poll.60s>
    <!-- Barra de Pesquisa e Filtros -->
    <div class="bg-white p-6 rounded-lg shadow-md text-black">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Pesquisa -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pesquisar</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Nome, ISBN ou bibliografia..."
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 ">
            </div>

            <!-- Filtro por Editora -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Editora</label>
                <select wire:model.live="filtroEditora" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todas as editoras</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $editoras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $editora): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($editora->id); ?>"><?php echo e($editora->nome); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
            </div>

            <!-- Filtro por Autor -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                <select wire:model.live="filtroAutor" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos os autores</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $autores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($autor->id); ?>"><?php echo e($autor->nome); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
            </div>

            <!-- Filtro de Preço Mínimo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Preço mínimo (€)</label>
                <input type="number" 
                       wire:model.live="filtroPrecoMin" 
                       step="0.01"
                       min="0"
                       placeholder="0.00"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Filtro de Preço Máximo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Preço máximo (€)</label>
                <input type="number" 
                       wire:model.live="filtroPrecoMax" 
                       step="0.01"
                       min="0"
                       placeholder="999.99"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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

    <!-- Cabeçalho da Tabela com Ordenação -->
    <div class="bg-white p-4 rounded-lg shadow-md">
        <div class="grid grid-cols-12 gap-4 font-medium text-gray-700">
            <div class="col-span-2 cursor-pointer hover:text-blue-600" wire:click="sortBy('imagem_capa')">
                Capa
            </div>
            <div class="col-span-2 cursor-pointer hover:text-blue-600" wire:click="sortBy('nome')">
                Nome
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'nome'): ?>
                    <span class="ml-1"><?php echo e($sortDirection === 'asc' ? '↑' : '↓'); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="col-span-2 cursor-pointer hover:text-blue-600" wire:click="sortBy('isbn')">
                ISBN
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'isbn'): ?>
                    <span class="ml-1"><?php echo e($sortDirection === 'asc' ? '↑' : '↓'); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="col-span-2">Bibliografia</div>
            <div class="col-span-1 cursor-pointer hover:text-blue-600 text-right" wire:click="sortBy('preco')">
                Preço
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'preco'): ?>
                    <span class="ml-1"><?php echo e($sortDirection === 'asc' ? '↑' : '↓'); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="col-span-2">Editora</div>
            <div class="col-span-1">Autores</div>
        </div>
    </div>

    <!-- Lista de Livros -->
    <div class="space-y-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $livros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $livro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-4">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <!-- Capa -->
                    <div class="col-span-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livro->imagem_capa): ?>
                            <img src="<?php echo e(asset($livro->imagem_capa)); ?>" 
                                 alt="<?php echo e($livro->nome); ?>" 
                                 class="w-20 h-24 object-cover rounded">
                        <?php else: ?>
                            <div class="w-20 h-24 bg-gray-200 flex items-center justify-center text-gray-400 rounded">
                                <span class="text-3xl">📚</span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Nome -->
                    <div class="col-span-2 font-semibold text-gray-800 break-words">
                        <?php echo e($livro->nome); ?>

                    </div>

                    <!-- ISBN -->
                    <div class="col-span-2 text-gray-600">
                        <?php echo e($livro->isbn); ?>

                    </div>

                    <!-- Bibliografia (resumida) -->
                    <div class="col-span-2 text-gray-600 text-sm">
                        <?php echo e(Str::limit($livro->bibliografia, 50)); ?>

                    </div>

                    <!-- Preço -->
                    <div class="col-span-1 text-right font-bold text-green-600">
                        €<?php echo e(number_format($livro->preco, 2, ',', '.')); ?>

                    </div>

                    <!-- Editora -->
                    <div class="col-span-2">
                        <span class="bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded">
                            <?php echo e($livro->editora->nome ?? 'N/A'); ?>

                        </span>
                    </div>

                    <!-- Autores -->
                    <div class="col-span-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livro->autores->isNotEmpty()): ?>
                            <div class="flex flex-col gap-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $livro->autores->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">
                                        <?php echo e($autor->nome); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livro->autores->count() > 2): ?>
                                    <span class="text-xs text-gray-500">
                                        +<?php echo e($livro->autores->count() - 2); ?>

                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">Sem autores</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white p-8 rounded-lg shadow-md text-center text-gray-500">
                Nenhum livro encontrado.
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        <?php echo e($livros->links()); ?>

    </div>

    <!-- Estatísticas -->
    <div class="bg-white p-4 rounded-lg shadow-md text-sm text-gray-600">
        Mostrando <?php echo e($livros->firstItem() ?? 0); ?> a <?php echo e($livros->lastItem() ?? 0); ?> de <?php echo e($livros->total()); ?> livros
    </div>
</div><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/livewire/livro-table.blade.php ENDPATH**/ ?>