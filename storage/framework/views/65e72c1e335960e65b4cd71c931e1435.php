<?php $__env->startComponent('mail::message'); ?>
# Status da sua Review

Olá <?php echo new \Illuminate\Support\EncodedHtmlString($review->user->name); ?>,

Sua review para o livro **<?php echo new \Illuminate\Support\EncodedHtmlString($review->livro->nome); ?>** foi **<?php echo new \Illuminate\Support\EncodedHtmlString($status === 'ativo' ? 'APROVADA' : 'RECUSADA'); ?>**.

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($status === 'ativo'): ?>
Sua review agora está publicada e visível para outros usuários na página do livro.

<?php $__env->startComponent('mail::button', ['url' => route('livros.show', $review->livro->id)]); ?>
Ver Livro
<?php echo $__env->renderComponent(); ?>
<?php else: ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($justificativa): ?>
**Justificativa da recusa:**
<?php echo new \Illuminate\Support\EncodedHtmlString($justificativa); ?>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

Sua review não foi publicada. Você pode enviar uma nova review seguindo as diretrizes da comunidade.

<?php $__env->startComponent('mail::button', ['url' => route('requisicoes.show', $review->requisicao->id)); ?>]
Ver Requisição
<?php echo $__env->renderComponent(); ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

Obrigado pela sua contribuição,<br>
<?php echo new \Illuminate\Support\EncodedHtmlString(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/emails/review-status.blade.php ENDPATH**/ ?>