

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto text-center">
        <div class="bg-green-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check-circle text-green-600 text-5xl"></i>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-4">Pagamento Confirmado!</h1>
        <p class="text-gray-600 mb-8">Obrigado pela sua compra. Seu pedido foi processado com sucesso.</p>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 text-left">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Detalhes do Pedido</h2>
            <p class="text-gray-600 mb-2"><strong>Número do Pedido:</strong> <?php echo e($encomenda->numero_encomenda); ?></p>
            <p class="text-gray-600 mb-2"><strong>Data:</strong> <?php echo e($encomenda->created_at->format('d/m/Y H:i')); ?></p>
            <p class="text-gray-600 mb-4"><strong>Total:</strong> € <?php echo e(number_format($encomenda->total, 2, ',', '.')); ?></p>

            <h3 class="font-semibold text-gray-800 mb-2">Itens Comprados:</h3>
            <div class="space-y-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $encomenda->itens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between">
                    <span><?php echo e($item->quantidade); ?>x <?php echo e($item->livro->nome); ?></span>
                    <span>€ <?php echo e(number_format($item->preco_unitario * $item->quantidade, 2, ',', '.')); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="border-t mt-4 pt-4">
                <h3 class="font-semibold text-gray-800 mb-2">Morada de Entrega:</h3>
                <p class="text-gray-600"><?php echo e($encomenda->morada_entrega); ?></p>
                <p class="text-gray-600"><?php echo e($encomenda->codigo_postal); ?>, <?php echo e($encomenda->cidade); ?></p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($encomenda->telefone): ?>
                    <p class="text-gray-600">Tel: <?php echo e($encomenda->telefone); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="flex justify-center space-x-4">
            <a href="<?php echo e(route('livros.index')); ?>" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                Continuar Comprando
            </a>
            <a href="<?php echo e(route('encomendas.index')); ?>" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                Ver Minhas Encomendas
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/carrinho/sucesso.blade.php ENDPATH**/ ?>