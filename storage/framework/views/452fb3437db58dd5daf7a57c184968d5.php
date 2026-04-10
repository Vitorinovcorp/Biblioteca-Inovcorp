

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Detalhes da Encomenda</h1>
            <a href="<?php echo e(route('encomendas.index')); ?>" class="text-purple-600 hover:text-purple-800">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <div class="flex justify-between items-center text-white">
                    <div>
                        <p class="text-sm opacity-90">Nº Encomenda</p>
                        <p class="text-xl font-bold"><?php echo e($encomenda->numero_encomenda); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm opacity-90">Data</p>
                        <p class="text-xl font-bold"><?php echo e($encomenda->created_at->format('d/m/Y H:i')); ?></p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Status do Pagamento</h3>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($encomenda->status_pagamento === 'pago'): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-2"></i> Pago em <?php echo e($encomenda->pago_em ? $encomenda->pago_em->format('d/m/Y H:i') : 'N/A'); ?>

                            </span>
                        <?php elseif($encomenda->status_pagamento === 'pendente'): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-2"></i> Pagamento Pendente
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-2"></i> Pagamento Falhou
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Total da Encomenda</h3>
                        <p class="text-3xl font-bold text-green-600">€ <?php echo e(number_format($encomenda->total, 2, ',', '.')); ?></p>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Itens Comprados</h3>
                    <div class="border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Livro</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Quantidade</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Preço Unitário</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $encomenda->itens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-4 py-3">
                                        <div>
                                            <p class="font-medium text-gray-800"><?php echo e($item->livro->nome); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo e($item->livro->autores->pluck('nome')->implode(', ')); ?></p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-600"><?php echo e($item->quantidade); ?></td>
                                    <td class="px-4 py-3 text-right text-gray-600">€ <?php echo e(number_format($item->preco_unitario, 2, ',', '.')); ?></td>
                                    <td class="px-4 py-3 text-right font-medium text-gray-800">€ <?php echo e(number_format($item->quantidade * $item->preco_unitario, 2, ',', '.')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-800">Total:</td>
                                    <td class="px-4 py-3 text-right text-xl font-bold text-green-600">€ <?php echo e(number_format($encomenda->total, 2, ',', '.')); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Morada de Entrega</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700"><?php echo e($encomenda->morada_entrega); ?></p>
                        <p class="text-gray-700"><?php echo e($encomenda->codigo_postal); ?>, <?php echo e($encomenda->cidade); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($encomenda->telefone): ?>
                            <p class="text-gray-700">Tel: <?php echo e($encomenda->telefone); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($encomenda->status_pagamento === 'pendente'): ?>
                <div class="mt-6 text-center">
                    <a href="<?php echo e(route('carrinho.checkout')); ?>" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-credit-card mr-2"></i> Finalizar Pagamento
                    </a>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/encomendas/show.blade.php ENDPATH**/ ?>