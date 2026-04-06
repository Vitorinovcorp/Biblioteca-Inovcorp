

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg p-8 mb-8">
        <div class="text-center text-white">
            <h1 class="text-3xl font-bold mb-2">
                Recomendações Inteligentes
            </h1>
            <p class="text-lg opacity-90">
                Baseado no livro: <strong class="font-semibold"><?php echo e($livro->nome); ?></strong>
            </p>
            <p class="mt-2">
                <i class="fas fa-chart-line mr-1"></i>
                Sistema de recomendação baseado em análise de conteúdo
            </p>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-24 h-32 bg-gray-100 rounded-lg flex items-center justify-center">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livro->imagem_capa): ?>
                    <?php
                        $imagePath = $livro->imagem_capa;
                        if (str_starts_with($imagePath, 'storage/')) {
                            $imageUrl = asset($imagePath);
                        } elseif (str_starts_with($imagePath, 'imagens/')) {
                            $imageUrl = asset('storage/' . $imagePath);
                        } else {
                            $imageUrl = asset('storage/imagens/livros/' . basename($imagePath));
                        }
                    ?>
                    <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($livro->nome); ?>" class="w-full h-full object-cover rounded-lg">
                <?php else: ?>
                    <i class="fas fa-book fa-3x text-gray-400"></i>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800"><?php echo e($livro->nome); ?></h2>
                <p class="text-gray-600 mt-1">
                    <i class="fas fa-user mr-1"></i>
                    <?php echo e($livro->autores->pluck('nome')->implode(', ') ?: 'Autor não informado'); ?>

                </p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livro->bibliografia): ?>
                    <p class="text-gray-500 text-sm mt-2"><?php echo e(Str::limit($livro->bibliografia, 200)); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-brain text-purple-500 mr-2"></i>
                Livros Relacionados
            </h2>
            <span class="text-gray-500">
                <?php echo e($recommendations->count()); ?> recomendações encontradas
            </span>
        </div>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recommendations->count() > 0): ?>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recommendation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group flex flex-col h-full">
                <a href="<?php echo e(route('livros.show', $recommendation->id)); ?>" class="block flex-1">
                    <div class="relative h-64 overflow-hidden">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recommendation->imagem_capa): ?>
                            <?php
                                $imagePath = $recommendation->imagem_capa;
                                if (str_starts_with($imagePath, 'storage/')) {
                                    $imageUrl = asset($imagePath);
                                } elseif (str_starts_with($imagePath, 'imagens/')) {
                                    $imageUrl = asset('storage/' . $imagePath);
                                } else {
                                    $imageUrl = asset('storage/imagens/livros/' . basename($imagePath));
                                }
                            ?>
                            <img src="<?php echo e($imageUrl); ?>" 
                                 alt="<?php echo e($recommendation->nome); ?>" 
                                 class="mx-auto h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 onerror="this.src='https://placehold.co/400x600?text=Sem+Imagem'">
                        <?php else: ?>
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-book fa-4x text-gray-400"></i>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    
                    <div class="p-4">
                        <h3 class="font-bold text-gray-800 mb-2 line-clamp-2"><?php echo e($recommendation->nome); ?></h3>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recommendation->autores->count() > 0): ?>
                        <p class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-user mr-1"></i>
                            <?php echo e($recommendation->autores->take(2)->pluck('nome')->implode(', ')); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recommendation->autores->count() > 2): ?>
                                <span class="text-gray-400"> +<?php echo e($recommendation->autores->count() - 2); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recommendation->bibliografia): ?>
                        <p class="text-xs text-gray-500 line-clamp-2 mb-3">
                            <?php echo e(Str::limit($recommendation->bibliografia, 100)); ?>

                        </p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-lg font-bold text-green-600">
                                € <?php echo e(number_format($recommendation->preco, 2, ',', '.')); ?>

                            </span>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recommendation->quantidade > 0): ?>
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">
                                    <i class="fas fa-check-circle"></i> Disponível
                                </span>
                            <?php else: ?>
                                <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded-full">
                                    <i class="fas fa-times-circle"></i> Indisponível
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($similarityScores[$recommendation->id])): ?>
                        <div class="mt-2 pt-2 border-t border-gray-100">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-purple-600">
                                    <i class="fas fa-chart-line mr-1"></i>
                                    Similaridade
                                </span>
                                <div class="flex-1 mx-2">
                                    <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-purple-500 rounded-full" style="width: <?php echo e(min(100, round($similarityScores[$recommendation->id] * 100))); ?>%"></div>
                                    </div>
                                </div>
                                <span class="font-semibold text-purple-600">
                                    <?php echo e(round($similarityScores[$recommendation->id] * 100)); ?>%
                                </span>
                            </div>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php else: ?>
        <div class="bg-gray-50 rounded-lg p-12 text-center">
            <i class="fas fa-book-open text-5xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Nenhuma recomendação encontrada</h3>
            <p class="text-gray-500">Não encontramos livros relacionados a este título no momento.</p>
            <a href="<?php echo e(route('livros.index')); ?>" class="inline-block mt-4 text-purple-600 hover:text-purple-700">
                <i class="fas fa-arrow-left mr-1"></i> Voltar para todos os livros
            </a>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    
    <div class="text-center">
        <a href="<?php echo e(route('livros.show', $livro->id)); ?>" 
           class="inline-flex items-center px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Voltar para o livro
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/livros-recommendations.blade.php ENDPATH**/ ?>