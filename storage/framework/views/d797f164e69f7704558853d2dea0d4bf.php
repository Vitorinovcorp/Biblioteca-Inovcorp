<?php $__env->startComponent('mail::message'); ?>
# Nova Review Aguardando Moderação

Uma nova review foi submetida e aguarda sua moderação.

**Cidadão:** <?php echo new \Illuminate\Support\EncodedHtmlString($citizen->name); ?><br>
**Email:** <?php echo new \Illuminate\Support\EncodedHtmlString($citizen->email); ?><br>
**Livro:** <?php echo new \Illuminate\Support\EncodedHtmlString($review->livro->nome); ?>


**Review:**
<?php echo new \Illuminate\Support\EncodedHtmlString($review->review); ?>


<?php $__env->startComponent('mail::button', ['url' => route('reviews.show', $review->id)]); ?>
Ver Detalhes da Review
<?php echo $__env->renderComponent(); ?>

Obrigado,<br>
<?php echo new \Illuminate\Support\EncodedHtmlString(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/emails/new-review.blade.php ENDPATH**/ ?>