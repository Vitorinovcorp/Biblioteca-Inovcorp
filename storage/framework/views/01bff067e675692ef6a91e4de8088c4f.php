

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="<?php echo e(Auth::user()->role === 'admin' ? route('reviews.pending') : route('requisicoes.index')); ?>" 
               class="text-blue-500 hover:text-blue-700">
                ← Voltar
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gray-800 text-white px-6 py-4">
                <h1 class="text-2xl font-bold">Detalhe da Review</h1>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500">Livro</h3>
                        <p class="text-lg font-medium"><?php echo e($review->livro->nome); ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500">Cidadão</h3>
                        <p class="text-lg font-medium"><?php echo e($review->user->name); ?></p>
                        <p class="text-sm text-gray-600"><?php echo e($review->user->email); ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500">Data da Review</h3>
                        <p class="text-lg font-medium"><?php echo e($review->created_at->format('d/m/Y H:i')); ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500">Status</h3>
                        <p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->status === 'ativo'): ?>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Ativo</span>
                            <?php elseif($review->status === 'suspenso'): ?>
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">Suspenso</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">Recusado</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->rating): ?>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500">Avaliação</h3>
                        <div class="flex items-center mt-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star text-2xl <?php echo e($i <= $review->rating ? 'text-yellow-400' : 'text-gray-300'); ?>"></i>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-500 mb-2">Review</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700 whitespace-pre-wrap"><?php echo e($review->review); ?></p>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->justificativa_recusa): ?>
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-500 mb-2">Justificativa da Recusa</h3>
                    <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500">
                        <p class="text-red-700"><?php echo e($review->justificativa_recusa); ?></p>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role === 'admin' && $review->status === 'suspenso'): ?>
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-lg font-semibold mb-4">Moderação</h3>
                    <form action="<?php echo e(route('reviews.status', $review->id)); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Decisão:</label>
                            <select name="status" class="w-full md:w-1/2 px-3 py-2 border rounded-lg" required>
                                <option value="">Selecione...</option>
                                <option value="ativo">Aprovar e Publicar</option>
                                <option value="recusado">Recusar</option>
                            </select>
                        </div>

                        <div id="justificativaContainer" class="hidden">
                            <label class="block text-gray-700 font-medium mb-2">Justificativa (para recusa):</label>
                            <textarea name="justificativa" rows="4" class="w-full px-3 py-2 border rounded-lg" 
                                      placeholder="Explique ao usuário o motivo da recusa..."></textarea>
                            <p class="text-sm text-gray-500 mt-1">O usuário receberá esta justificativa por email.</p>
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                            Confirmar Decisão
                        </button>
                    </form>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('select[name="status"]').addEventListener('change', function() {
    const container = document.getElementById('justificativaContainer');
    if (this.value === 'recusado') {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/reviews/show.blade.php ENDPATH**/ ?>