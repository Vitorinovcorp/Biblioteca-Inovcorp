



<?php $__env->startSection('content'); ?>
<div class="px-6">

    
    <div class="mb-6">
        <h3 class="text-2xl font-semibold mt-3 text-gray-800">
            Resultados para: "<?php echo e($query); ?>"
        </h3>
        <p class="text-gray-600">
            Encontrados <?php echo e($results['totalItems'] ?? 0); ?> livros
        </p>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-gray-800" >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $results['items'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <?php
                $thumbnail = $book['volumeInfo']['imageLinks']['thumbnail'] ?? 
                             $book['volumeInfo']['imageLinks']['smallThumbnail'] ?? null;
            ?>

            <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col">

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thumbnail): ?>
                    <img src="<?php echo e($thumbnail); ?>" 
                         class="h-64 object-contain p-4 bg-gray-50 rounded-t-xl"
                         alt="<?php echo e($book['volumeInfo']['title'] ?? 'Capa'); ?>">
                <?php else: ?>
                    <div class="h-64 flex items-center justify-center bg-gray-100 text-gray-400">
                        Sem imagem
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <div class="p-4 flex flex-col flex-1 text-center">

                    <h2 class="text-sm font-semibold mb-2 line-clamp-2">
                        <?php echo e($book['volumeInfo']['title'] ?? 'Sem título'); ?>

                    </h2>

                    <p class="text-xs text-gray-500 mb-2">
                        <?php echo e(implode(', ', $book['volumeInfo']['authors'] ?? ['Autor desconhecido'])); ?>

                    </p>

                    <p class="text-xs text-gray-400 mb-3 line-clamp-3">
                        <?php echo e($book['volumeInfo']['description'] ?? 'Sem descrição'); ?>

                    </p>

                    <div class="mt-auto">
                        <p class="text-xs text-gray-400 mb-2">
                            <?php echo e($book['volumeInfo']['publishedDate'] ?? 'Data desconhecida'); ?>

                        </p>

                        <a href="<?php echo e(route('google-books.import-form', $book['id'])); ?>" 
                           class="inline-flex items-center justify-center bg-indigo-600 text-white text-xs px-4 py-1.5 rounded-full hover:bg-indigo-700 transition">
                            <i class="fas fa-download mr-1"></i> Importar
                        </a>
                    </div>

                </div>
            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3">
                <div class="bg-blue-100 text-blue-700 p-4 rounded">
                    Nenhum resultado encontrado para "<?php echo e($query); ?>".
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($results['totalItems'] ?? 0) > count($results['items'] ?? [])): ?>
        <div class="mt-6 text-center">
            <button class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded hover:bg-indigo-600 hover:text-white transition" id="loadMore">
                Carregar mais resultados
            </button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/google-books/results.blade.php ENDPATH**/ ?>