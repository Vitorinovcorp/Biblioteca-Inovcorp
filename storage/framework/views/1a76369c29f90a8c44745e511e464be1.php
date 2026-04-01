

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Detalhes da Requisição</h1>
        <a href="<?php echo e(route('requisicoes.index')); ?>" 
           class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
            Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Livro</h3>
                    <p class="text-lg text-gray-600"><?php echo e($requisicao->livro->nome); ?></p>
                    <p class="text-sm text-gray-600">ISBN: <?php echo e($requisicao->livro->isbn); ?></p>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->isAdmin()): ?>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Requisitante</h3>
                    <p class="text-lg text-gray-600"><?php echo e($requisicao->user->name); ?></p>
                    <p class="text-sm text-gray-600"><?php echo e($requisicao->user->email); ?></p>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Período</h3>
                    <p class="text-lg text-gray-600">
                        <?php echo e($requisicao->data_inicio->format('d/m/Y')); ?> a <?php echo e($requisicao->data_fim->format('d/m/Y')); ?>

                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requisicao->status == 'pendente'): ?>
                        <span class="px-3 py-1 text-sm rounded bg-yellow-100 text-yellow-800">Pendente</span>
                    <?php elseif($requisicao->status == 'aprovada'): ?>
                        <span class="px-3 py-1 text-sm rounded bg-green-100 text-green-800">Aprovada</span>
                    <?php elseif($requisicao->status == 'rejeitada'): ?>
                        <span class="px-3 py-1 text-sm rounded bg-red-100 text-red-800">Rejeitada</span>
                    <?php elseif($requisicao->status == 'devolvida'): ?>
                        <span class="px-3 py-1 text-sm rounded bg-gray-100 text-gray-800">Devolvida</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requisicao->observacoes): ?>
                <div class="col-span-2">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Observações</h3>
                    <p class="text-gray-700 bg-gray-50 p-3 rounded"><?php echo e($requisicao->observacoes); ?></p>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->isAdmin() && $requisicao->status == 'pendente'): ?>
        <div class="border-t px-6 py-4 bg-gray-50">
            <h3 class="text-sm font-medium text-gray-700 mb-3">Ações de Administrador</h3>
            <div class="flex space-x-3">
                <form action="<?php echo e(route('requisicoes.status', $requisicao)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <input type="hidden" name="status" value="aprovada">
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                            onclick="return confirm('Confirmar aprovação?')">
                        Aprovar Requisição
                    </button>
                </form>
                
                <form action="<?php echo e(route('requisicoes.status', $requisicao)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <input type="hidden" name="status" value="rejeitada">
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                            onclick="return confirm('Confirmar rejeição?')">
                        Rejeitar Requisição
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->isAdmin() && $requisicao->status == 'aprovada'): ?>
        <div class="border-t px-6 py-4 bg-gray-50">
            <form action="<?php echo e(route('requisicoes.status', $requisicao)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <input type="hidden" name="status" value="devolvida">
                <button type="submit" 
                        class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700"
                        onclick="return confirm('Confirmar devolução?')">
                    Marcar como Devolvido
                </button>
            </form>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requisicao->status === 'devolvida' && !$requisicao->review && Auth::user()->role !== 'admin'): ?>
<div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
    <h3 class="text-lg font-semibold text-blue-800 mb-3">Avalie este livro</h3>
    <p class="text-blue-600 mb-3">Sua opinião é importante para outros leitores!</p>
    
    <form id="reviewForm" class="space-y-4">
        <?php echo csrf_field(); ?>
        <div>
            <label class="block text-gray-700 font-medium mb-2">Sua Avaliação (opcional):</label>
            <div class="flex gap-2" id="ratingStars">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star text-2xl cursor-pointer text-gray-300 hover:text-yellow-400 transition-colors" data-rating="<?php echo e($i); ?>"></i>
                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <input type="hidden" name="rating" id="ratingInput" value="">
        </div>
        
        <div>
            <label class="block text-gray-700 font-medium mb-2">Sua Review:</label>
            <textarea name="review" rows="4" class="w-full px-3 py-2 border rounded-lg" 
                      placeholder="Compartilhe sua experiência com este livro..." required></textarea>
        </div>
        
        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
            Enviar Review
        </button>
    </form>
    
    <div id="reviewMessage" class="mt-3"></div>
</div>

<script>
const stars = document.querySelectorAll('#ratingStars i');
const ratingInput = document.getElementById('ratingInput');

stars.forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.dataset.rating;
        ratingInput.value = rating;
        
        stars.forEach((s, index) => {
            if (index < rating) {
                s.classList.remove('text-gray-300');
                s.classList.add('text-yellow-400');
            } else {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-gray-300');
            }
        });
    });
});

document.getElementById('reviewForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const reviewText = formData.get('review');
    
    if (!reviewText || reviewText.length < 10) {
        showMessage('Por favor, escreva uma review com pelo menos 10 caracteres.', 'error');
        return;
    }
    
    try {
        const response = await fetch('<?php echo e(route("reviews.store", $requisicao->id)); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                review: reviewText,
                rating: ratingInput.value
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage(data.message, 'success');
            document.getElementById('reviewForm').remove();
        } else {
            showMessage(data.error, 'error');
        }
    } catch (error) {
        showMessage('Erro ao enviar review. Tente novamente.', 'error');
    }
});

function showMessage(message, type) {
    const messageDiv = document.getElementById('reviewMessage');
    messageDiv.className = `mt-3 p-3 rounded ${type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`;
    messageDiv.textContent = message;
    
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 5000);
}
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/requisicoes/show.blade.php ENDPATH**/ ?>