<header class="bg-blue-600 text-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-2">
                        <img src="<?php echo e(asset('icons/inovcorp-bg-w.png')); ?>" class="h-12 w-auto" alt="Logo">
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
                <a href="<?php echo e(route('google-books.search')); ?>"
                    class="<?php echo e(request()->routeIs('google-books.*') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition">
                    <i class="fab fa-google"></i> Google Books
                </a>

                <a href="<?php echo e(route('carrinho.index')); ?>"
                    class="<?php echo e(request()->routeIs('carrinho.*') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition relative">
                    <i class="fas mr-2"></i> Carrinho
                    <?php
                        $totalItens = session('carrinho_total_itens', 0);
                        if ($totalItens == 0 && Auth::check()) {
                            $carrinho = \App\Models\Carrinho::where('user_id', Auth::id())
                                ->where('status', 'aberto')
                                ->first();
                            if ($carrinho) {
                                $totalItens = $carrinho->itens->sum('quantidade');
                                session(['carrinho_total_itens' => $totalItens]);
                            }
                        }
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalItens > 0): ?>
                        <span id="carrinho-contador" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            <?php echo e($totalItens); ?>

                        </span>
                    <?php else: ?>
                        <span id="carrinho-contador" class="hidden"></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>

                <a href="<?php echo e(route('encomendas.index')); ?>"
                    class="<?php echo e(request()->routeIs('encomendas.*') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition">
                    <i class="fas mr-2"></i> Minhas Compras
                </a>

                <a href="/logout"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="hover:bg-blue-700 px-3 py-2 rounded transition">
                    Sair
                </a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
                    <?php echo csrf_field(); ?>
                </form>
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
                <a href="<?php echo e(route('google-books.search')); ?>"
                    class="<?php echo e(request()->routeIs('google-books.*') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition block">
                    <i class="fab fa-google"></i> Google Books
                </a>
                <a href="<?php echo e(route('carrinho.index')); ?>"
                    class="<?php echo e(request()->routeIs('carrinho.*') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition block relative">
                    <i class="fas fa-shopping-cart mr-2"></i> Carrinho
                    <?php
                        $totalItensMobile = session('carrinho_total_itens', 0);
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalItensMobile > 0): ?>
                        <span id="carrinho-contador-mobile" class="bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 ml-2">
                            <?php echo e($totalItensMobile); ?>

                        </span>
                    <?php else: ?>
                        <span id="carrinho-contador-mobile" class="hidden"></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>
                <a href="<?php echo e(route('encomendas.index')); ?>"
                    class="<?php echo e(request()->routeIs('encomendas.*') ? 'bg-blue-700' : 'hover:bg-blue-700'); ?> px-3 py-2 rounded transition block">
                    <i class="fas fa-shopping-bag mr-2"></i> Minhas Compras
                </a>
                <a href="/logout"
                    onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                    class="hover:bg-blue-700 px-3 py-2 rounded transition block">
                    Sair
                </a>
                <form id="logout-form-mobile" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
                    <?php echo csrf_field(); ?>
                </form>
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
</script>
<?php  ?>