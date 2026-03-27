

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <!-- Imagem do Livro -->
            <div class="md:w-1/3 p-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livro->imagem_capa): ?>
                    <img src="<?php echo e(Storage::url($livro->imagem_capa)); ?>" 
                         alt="<?php echo e($livro->nome); ?>" 
                         class="w-full rounded-lg shadow-md">
                <?php else: ?>
                    <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book fa-4x text-gray-400"></i>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            
            <!-- Informações do Livro -->
            <div class="md:w-2/3 p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo e($livro->nome); ?></h1>
                
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-user mr-2"></i> Autores:</span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livro->autores->count() > 0): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $livro->autores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2 text-sm">
                                <?php echo e($autor->nome); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <span class="text-gray-500">Não informado</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-building mr-2"></i> Editora:</span>
                    <span class="text-gray-800"><?php echo e($livro->editora->nome ?? 'Não informada'); ?></span>
                </div>
                
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-barcode mr-2"></i> ISBN:</span>
                    <span class="text-gray-800"><?php echo e($livro->isbn ?? 'Não informado'); ?></span>
                </div>
                
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-dollar-sign mr-2"></i> Preço:</span>
                    <span class="text-2xl font-bold text-green-600">R$ <?php echo e(number_format($livro->preco, 2, ',', '.')); ?></span>
                </div>
                
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-boxes mr-2"></i> Quantidade em estoque:</span>
                    <span class="text-xl font-semibold <?php echo e($livro->quantidade > 0 ? 'text-green-600' : 'text-red-600'); ?>">
                        <?php echo e($livro->quantidade); ?>

                    </span>
                </div>
                
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-check-circle mr-2"></i> Disponibilidade:</span>
                    <span class="inline-block px-3 py-1 rounded text-sm font-semibold <?php echo e($disponivelAgora ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                        <?php echo e($disponivelAgora ? 'Disponível para empréstimo' : 'Indisponível no momento'); ?>

                    </span>
                </div>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livro->bibliografia): ?>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Descrição:</h3>
                    <p class="text-gray-600 leading-relaxed"><?php echo e($livro->bibliografia); ?></p>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <div class="flex gap-4 mt-6">
                    <a href="<?php echo e(route('livros.index')); ?>" 
                       class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Voltar
                    </a>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role === 'admin'): ?>
                            <a href="<?php echo e(route('livros.edit', $livro->id)); ?>" 
                               class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 transition">
                                <i class="fas fa-edit mr-2"></i> Editar
                            </a>
                            
                            <form action="<?php echo e(route('livros.destroy', $livro->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" 
                                        class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition"
                                        onclick="return confirm('Tem certeza que deseja excluir este livro?')">
                                    <i class="fas fa-trash mr-2"></i> Excluir
                                </button>
                            </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livro->quantidade > 0 && $disponivelAgora): ?>
                            <a href="<?php echo e(route('requisicoes.create', ['livro_id' => $livro->id])); ?>" 
                               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-hand-holding-heart mr-2"></i> Solicitar Empréstimo
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Histórico de Empréstimos (apenas para admin) -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role === 'admin' && isset($historico) && $historico->count() > 0): ?>
        <div class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden">
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $historico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requisicao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($requisicao->user->name ?? 'Usuário não encontrado'); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e(\Carbon\Carbon::parse($requisicao->data_inicio)->format('d/m/Y')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e(\Carbon\Carbon::parse($requisicao->data_fim)->format('d/m/Y')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php echo e($requisicao->status === 'aprovada' ? 'bg-green-100 text-green-800' : 
                                       ($requisicao->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')); ?>">
                                    <?php echo e(ucfirst($requisicao->status)); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/livros-show.blade.php ENDPATH**/ ?>