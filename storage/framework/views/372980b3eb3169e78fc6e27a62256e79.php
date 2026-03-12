<header class="bg-blue-600 text-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="text-2xl font-bold">InovCorp Biblioteca </a>
            </div>

            <!-- Menu -->
            <nav class="space-x-4">
                <a href="/livros" 
                   class="<?php echo e(request()->is('livros') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition">
                    Livros
                </a>
                <a href="/editoras" 
                   class="<?php echo e(request()->is('editoras') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition">
                    Editoras
                </a>
                <a href="/autores" 
                   class="<?php echo e(request()->is('autores') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition">
                    Autores
                </a>
            </nav>
        </div>
    </div>
</header><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/components/header.blade.php ENDPATH**/ ?>