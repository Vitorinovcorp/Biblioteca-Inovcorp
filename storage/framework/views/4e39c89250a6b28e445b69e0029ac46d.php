
<!DOCTYPE html>
<html>
<head>
    <title>Lembrete de Devolução</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f59e0b; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9fafb; }
        .book-info { background-color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .book-cover { max-width: 200px; margin: 10px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
        .warning { background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; }
        .button { background-color: #f59e0b; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⏰ Lembrete de Devolução</h1>
        </div>
        
        <div class="content">
            <p>Olá <?php echo e($requisicao->user->name); ?>,</p>
            
            <div class="warning">
                <p><strong>⚠️ Atenção:</strong> O prazo de devolução do livro é <strong>AMANHÃ</strong>!</p>
            </div>
            
            <p>Este é um lembrete amigável para devolver o livro na biblioteca dentro do prazo.</p>
            
            <div class="book-info">
                <h3>📖 Detalhes da Requisição</h3>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requisicao->livro->capa): ?>
                    <div class="book-cover">
                        <img src="<?php echo e(asset('storage/' . $requisicao->livro->capa)); ?>" 
                             alt="Capa do livro" style="max-width: 200px;">
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <p><strong>Livro:</strong> <?php echo e($requisicao->livro->nome); ?></p>
                <p><strong>Autor(es):</strong> 
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $requisicao->livro->autores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($autor->nome); ?><?php echo e(!$loop->last ? ', ' : ''); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
                
                <h4 class="mt-4">📅 Datas Importantes</h4>
                <p><strong>Data de Início:</strong> <?php echo e($requisicao->data_inicio->format('d/m/Y')); ?></p>
                <p><strong>Data de Fim (AMANHÃ):</strong> <?php echo e($requisicao->data_fim->format('d/m/Y')); ?></p>
                
                <?php
                    $diasRestantes = now()->diffInDays($requisicao->data_fim, false);
                ?>
                <p><strong>Prazo restante:</strong> <?php echo e($diasRestantes); ?> dia(s)</p>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <p>Por favor, devolva o livro amanhã para evitar multas por atraso.</p>
                <a href="<?php echo e(route('requisicoes.index')); ?>" class="button">
                    Ver Minhas Requisições
                </a>
            </div>
            
            <p><strong>Horário de funcionamento da biblioteca:</strong> Segunda a Sexta, 9h às 18h</p>
        </div>
        
        <div class="footer">
            <p>© <?php echo e(date('Y')); ?> Biblioteca. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/emails/reminder-devolucao.blade.php ENDPATH**/ ?>