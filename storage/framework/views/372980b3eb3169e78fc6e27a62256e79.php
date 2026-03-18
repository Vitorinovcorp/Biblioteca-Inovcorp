<header class="bg-blue-600 text-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-2">
                        <img src="<?php echo e(asset('icons/inovcorp.png')); ?>" class="h-8 w-auto">
                        <span class="font-semibold text-white text-lg">
                            Inovcorp 
                        </span>
                    </a>
                </div>
            </div>

            <nav class="hidden md:flex space-x-4">
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
                <a href="<?php echo e(route('requisicoes.index')); ?>"
                    class="<?php echo e(request()->routeIs('requisicoes.*') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition">
                    Requisições
                </a>
                <a href="/login"
                    class="<?php echo e(request()->is('sair') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition">
                    Sair
                </a>
            </nav>

            <div class="md:hidden">
                <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-blue-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <div class="flex flex-col space-y-2">
                <a href="/livros"
                    class="<?php echo e(request()->is('livros') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition block">
                    Livros
                </a>
                <a href="/editoras"
                    class="<?php echo e(request()->is('editoras') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition block">
                    Editoras
                </a>
                <a href="/autores"
                    class="<?php echo e(request()->is('autores') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition block">
                    Autores
                </a>
                <a href="<?php echo e(route('requisicoes.index')); ?>"
                    class="<?php echo e(request()->routeIs('requisicoes.*') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition block">
                    Requisições
                </a>
                <a href="/login"
                    class="<?php echo e(request()->is('sair') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition block">
                    Sair
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');

        if (button && menu) {
            button.addEventListener('click', function() {
                menu.classList.toggle('hidden');
            });
        }
    });
</script><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/components/header.blade.php ENDPATH**/ ?>