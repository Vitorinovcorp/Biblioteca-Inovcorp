

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Requisições</h1>
        <a href="<?php echo e(route('requisicoes.create')); ?>" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Nova Requisição
        </a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Livro</th>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->isAdmin()): ?>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requisitante</th>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Período</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $requisicoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requisicao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?php echo e($requisicao->livro->nome); ?></div>
                        <div class="text-sm text-gray-500"><?php echo e($requisicao->livro->isbn); ?></div>
                    </td>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->isAdmin()): ?>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900"><?php echo e($requisicao->user->name); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e($requisicao->user->email); ?></div>
                        </td>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            <?php echo e($requisicao->data_inicio->format('d/m/Y')); ?> a <?php echo e($requisicao->data_fim->format('d/m/Y')); ?>

                        </div>
                    </td>
                    
                    <td class="px-6 py-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requisicao->status == 'pendente'): ?>
                            <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pendente</span>
                        <?php elseif($requisicao->status == 'aprovada'): ?>
                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Aprovada</span>
                        <?php elseif($requisicao->status == 'rejeitada'): ?>
                            <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Rejeitada</span>
                        <?php elseif($requisicao->status == 'devolvida'): ?>
                            <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">Devolvida</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    
                    <td class="px-6 py-4 text-sm">
                        <a href="<?php echo e(route('requisicoes.show', $requisicao)); ?>" 
                           class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requisicao->status == 'pendente' && (Auth::user()->isAdmin() || Auth::id() == $requisicao->user_id)): ?>
                            <form action="<?php echo e(route('requisicoes.destroy', $requisicao)); ?>" 
                                  method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Tem certeza que deseja cancelar?')">
                                    Cancelar
                                </button>
                            </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->isAdmin() && $requisicao->status == 'pendente'): ?>
                            <form action="<?php echo e(route('requisicoes.status', $requisicao)); ?>" method="POST" class="inline ml-2">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <input type="hidden" name="status" value="aprovada">
                                <button type="submit" class="text-green-600 hover:text-green-900">Aprovar</button>
                            </form>
                            <form action="<?php echo e(route('requisicoes.status', $requisicao)); ?>" method="POST" class="inline ml-2">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <input type="hidden" name="status" value="rejeitada">
                                <button type="submit" class="text-red-600 hover:text-red-900">Rejeitar</button>
                            </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->isAdmin() && $requisicao->status == 'aprovada'): ?>
                            <form action="<?php echo e(route('requisicoes.status', $requisicao)); ?>" method="POST" class="inline ml-2">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <input type="hidden" name="status" value="devolvida">
                                <button type="submit" class="text-purple-600 hover:text-purple-900">Devolver</button>
                            </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="<?php echo e(Auth::user()->isAdmin() ? 5 : 4); ?>" class="px-6 py-4 text-center text-gray-500">
                        Nenhuma requisição encontrada.
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        <?php echo e($requisicoes->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/requisicoes/index.blade.php ENDPATH**/ ?>