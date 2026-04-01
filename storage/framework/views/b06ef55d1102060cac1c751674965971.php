<?php
    $volumeInfo = $book['volumeInfo'] ?? [];
    $imageLinks = $volumeInfo['imageLinks'] ?? [];
    $thumbnail = $imageLinks['thumbnail'] ?? $imageLinks['smallThumbnail'] ?? null;
    $title = $volumeInfo['title'] ?? 'Título não disponível';
    $authors = isset($volumeInfo['authors']) ? implode(', ', $volumeInfo['authors']) : 'Autor não informado';
    $publishedDate = $volumeInfo['publishedDate'] ?? 'Data não informada';
    $isbn = '';
    
    if (isset($volumeInfo['industryIdentifiers'])) {
        foreach ($volumeInfo['industryIdentifiers'] as $identifier) {
            if ($identifier['type'] === 'ISBN_13' || $identifier['type'] === 'ISBN_10') {
                $isbn = $identifier['identifier'];
                break;
            }
        }
    }
    
    $description = $volumeInfo['description'] ?? '';
    $volumeId = $book['id'] ?? '';
    
    // Formata a data para exibir apenas ano se for uma data completa
    if (strlen($publishedDate) > 4) {
        $publishedDate = substr($publishedDate, 0, 4);
    }
?>

<div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex flex-col h-full border border-gray-100">
    <!-- Imagem do Livro -->
    <div class="flex justify-center items-center p-4 bg-gray-50 rounded-t-lg h-48">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thumbnail): ?>
            <img src="<?php echo e($thumbnail); ?>" class="h-full object-contain" alt="<?php echo e($title); ?>">
        <?php else: ?>
            <div class="flex flex-col items-center justify-center text-gray-400">
                <i class="fas fa-book fa-3x mb-2"></i>
                <span class="text-xs">Sem imagem</span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    
    <!-- Informações do Livro -->
    <div class="p-4 flex flex-col flex-1">
        <h5 class="text-gray-800 font-semibold text-base mb-2 line-clamp-2 min-h-[3.5rem]">
            <?php echo e(Str::limit($title, 80)); ?>

        </h5>
        
        <p class="text-gray-600 text-sm mb-2 line-clamp-2">
            <i class="fas fa-user mr-1 text-gray-400"></i> <?php echo e(Str::limit($authors, 60)); ?>

        </p>
        
        <div class="text-gray-500 text-xs mb-3 space-y-1">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($publishedDate && $publishedDate != 'Data não informada'): ?>
                <div><i class="far fa-calendar-alt mr-1"></i> <?php echo e($publishedDate); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isbn): ?>
                <div><i class="fas fa-barcode mr-1"></i> ISBN: <?php echo e($isbn); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        
        <!-- Botão Importar -->
        <div class="mt-auto pt-2">
            <button type="button" 
                class="import-btn bg-blue-600 text-white text-sm px-4 py-2 rounded-md hover:bg-blue-700 transition w-full"
                data-volume-id="<?php echo e($volumeId); ?>"
                data-title="<?php echo e(htmlspecialchars($title, ENT_QUOTES, 'UTF-8')); ?>"
                data-authors="<?php echo e(htmlspecialchars($authors, ENT_QUOTES, 'UTF-8')); ?>"
                data-isbn="<?php echo e($isbn); ?>"
                data-description="<?php echo e(htmlspecialchars($description, ENT_QUOTES, 'UTF-8')); ?>">
                <i class="fas fa-download mr-1"></i> Importar Livro
            </button>
        </div>
    </div>
</div><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/google-books/partials/book-card.blade.php ENDPATH**/ ?>