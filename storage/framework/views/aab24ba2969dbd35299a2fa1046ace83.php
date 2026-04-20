

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/3 p-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livro->imagem_capa): ?>
                <?php
                // Garante o caminho correto da imagem
                $imagePath = $livro->imagem_capa;
                // Se já começa com storage/, mantém
                if (str_starts_with($imagePath, 'storage/')) {
                $imageUrl = asset($imagePath);
                }
                // Se começa com imagens/, adiciona storage/
                elseif (str_starts_with($imagePath, 'imagens/')) {
                $imageUrl = asset('storage/' . $imagePath);
                }
                // Se começa com /, remove a barra
                elseif (str_starts_with($imagePath, '/')) {
                $imageUrl = asset('storage' . $imagePath);
                }
                // Caso contrário, assume que está em storage/imagens/livros
                else {
                $imageUrl = asset('storage/imagens/livros/' . basename($imagePath));
                }
                ?>
                <img src="<?php echo e($imageUrl); ?>"
                    alt="<?php echo e($livro->nome); ?>"
                    class="w-full rounded-lg shadow-md"
                    onerror="this.src='https://placehold.co/400x600?text=Sem+Imagem'">
                <?php else: ?>
                <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book fa-4x text-gray-400"></i>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="md:w-2/3 p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo e($livro->nome); ?></h1>

                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-user mr-2"></i> Autores:</span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livro->autores->count() > 0): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $livro->autores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2 text-sm">
                        <?php echo e($autor->nome); ?>

                    </span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <?php else: ?>
                    <span class="text-gray-500">Não informado</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-building mr-2"></i> Editora:</span>
                    <span class="text-gray-800"><?php echo e($livro->editora->nome ?? 'Não informada'); ?></span>
                </div>

                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-barcode mr-2"></i> ISBN:</span>
                    <span class="text-gray-800"><?php echo e($livro->isbn ?? 'Nao informado'); ?></span>
                </div>

                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-dollar-sign mr-2"></i> Preço:</span>
                    <span class="text-2xl font-bold text-green-600">€ <?php echo e(number_format($livro->preco, 2, ',', '.')); ?></span>
                </div>

                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-boxes mr-2"></i> Quantidade em estoque:</span>
                    <span class="text-xl font-semibold <?php echo e($livro->quantidade > 0 ? 'text-green-600' : 'text-red-600'); ?>">
                        <?php echo e($livro->quantidade); ?>

                    </span>
                </div>

                
                <div class="mb-4">
                    <span class="text-gray-600"><i class="fas fa-check-circle mr-2"></i> Disponibilidade:</span>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($disponivelAgora && $livro->quantidade > 0): ?>
                    <div>
                        <span class="inline-block px-3 py-1 rounded text-sm font-semibold bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Disponível para empréstimo
                        </span>
                    </div>
                    <?php else: ?>
                    <div class="flex flex-col space-y-3">
                        <span class="inline-block px-3 py-1 rounded text-sm font-semibold bg-red-100 text-red-800 w-fit">
                            <i class="fas fa-times-circle mr-1"></i> Indisponível no momento
                        </span>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role !== 'admin'): ?>
                        <div id="notification-area">
                            <button id="btn-notificar"
                                class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm"
                                onclick="solicitarNotificacao('<?php echo e($livro->id); ?>')">
                                <i class="fas fa-bell mr-2"></i>
                                Notificar-me quando disponível
                            </button>

                            <button id="btn-cancelar"
                                class="hidden inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm"
                                onclick="cancelarNotificacao('<?php echo e($livro->id); ?>')">
                                <i class="fas fa-bell-slash mr-2"></i>
                                Cancelar notificação
                            </button>

                            <p id="mensagem-notificacao" class="text-sm mt-2"></p>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livro->bibliografia): ?>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Descrição:</h3>
                    <p class="text-gray-600 leading-relaxed"><?php echo e($livro->bibliografia); ?></p>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="flex gap-4 mt-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role === 'admin'): ?>
                    <a href="<?php echo e(route('livros.edit', $livro->id)); ?>"
                        class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 transition">
                        <i class="fas fa-edit mr-2"></i> Editar
                    </a>

                    <form action="<?php echo e(route('livros.destroy', $livro->id)); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit"
                            class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition"
                            onclick="return confirm('Tem certeza que deseja excluir este livro?')">
                            <i class="fas fa-trash mr-2"></i> Excluir
                        </button>
                    </form>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($livro->quantidade > 0 && $disponivelAgora): ?>
                    <a href="<?php echo e(route('requisicoes.create', ['livro_id' => $livro->id])); ?>"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-hand-holding-heart mr-2"></i> Solicitar Empréstimo
                    </a>

                    <button type="button"
                        onclick="adicionarAoCarrinho(<?php echo e($livro->id); ?>)"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-shopping-cart mr-2"></i> Adicionar ao Carrinho
                    </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="reviews" class="mt-12 max-w-4xl mx-auto">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-star text-yellow-500 mr-2"></i>
            Avaliação dos Leitores
        </h2>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($totalReviews) && $totalReviews > 0): ?>
        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm inline-block mt-2">
            <?php echo e($totalReviews); ?> <?php echo e($totalReviews == 1 ? 'avaliacao' : 'avaliacoes'); ?>

        </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($totalReviews) && $totalReviews > 0): ?>
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-8 text-center">
        <div class="text-5xl font-bold text-gray-800"><?php echo e(number_format($mediaRating, 1)); ?></div>
        <div class="flex items-center justify-center mt-2">
            <?php
            $roundedRating = round($mediaRating);
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i <=$roundedRating): ?>
                <i class="fas fa-star text-xl text-yellow-400"></i>
                <?php else: ?>
                <i class="fas fa-star text-xl text-gray-300"></i>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        <p class="text-sm text-gray-500 mt-1">Média de <?php echo e($totalReviews); ?> Avaliação</p>
    </div>

    <div class="space-y-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $livro->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                        <?php echo e(strtoupper(substr($review->user->name, 0, 1))); ?>

                    </div>
                    <div>
                        <p class="font-semibold text-gray-800"><?php echo e($review->user->name); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($review->created_at->format('d/m/Y H:i')); ?></p>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->rating): ?>
                <div class="flex items-center bg-yellow-50 px-3 py-1 rounded-full">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i <=$review->rating): ?>
                        <i class="fas fa-star text-sm text-yellow-400"></i>
                        <?php else: ?>
                        <i class="fas fa-star text-sm text-gray-300"></i>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <span class="ml-2 text-sm font-semibold text-gray-700"><?php echo e($review->rating); ?>/5</span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="mt-3">
                <p class="text-gray-700 leading-relaxed"><?php echo e($review->review); ?></p>
            </div>

            <div class="mt-4 flex items-center text-xs text-green-600">
                <i class="fas fa-check-circle mr-1"></i>
                <span>Review verificada</span>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
    <?php else: ?>
    <div class="bg-gray-50 rounded-lg p-8 text-center">
        <i class="fas fa-comment-dots text-5xl text-gray-400 mb-3"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">Este livro ainda não possui avaliação</h3>
        <p class="text-gray-500">Seja o primeiro a avaliar este livro após requisitá-lo e devolvê-lo!</p>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('requisicoes.create')); ?>" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
            <i class="fas fa-hand-paper mr-2"></i> Requisitar este livro
        </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($recommendations) && $recommendations->count() > 0): ?>
<div class="mt-12 max-w-7xl mx-auto">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-brain text-purple-500 mr-2"></i>
            Livros Recomendados para Você
        </h2>
        <p class="text-gray-600 mt-1">
            Baseado na descrição e características deste livro, sugerimos estas leituras relacionadas:
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recommendation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
            <a href="<?php echo e(route('livros.show', $recommendation->id)); ?>" class="block">
                <div class="relative h-64 overflow-hidden">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recommendation->imagem_capa): ?>
                    <?php
                    $imagePath = $recommendation->imagem_capa;
                    if (str_starts_with($imagePath, 'storage/')) {
                    $imageUrl = asset($imagePath);
                    } elseif (str_starts_with($imagePath, 'imagens/')) {
                    $imageUrl = asset('storage/' . $imagePath);
                    } elseif (str_starts_with($imagePath, '/')) {
                    $imageUrl = asset('storage' . $imagePath);
                    } else {
                    $imageUrl = asset('storage/imagens/livros/' . basename($imagePath));
                    }
                    ?>
                    <img src="<?php echo e($imageUrl); ?>"
                        alt="<?php echo e($recommendation->nome); ?>"
                        class="mx-auto h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='https://placehold.co/400x600?text=Sem+Imagem'">
                    <?php else: ?>
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-book fa-4x text-gray-400"></i>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="p-4">
                    <h3 class="font-bold text-gray-800 mb-2 line-clamp-2"><?php echo e($recommendation->nome); ?></h3>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recommendation->autores->count() > 0): ?>
                    <p class="text-sm text-gray-600 mb-2">
                        <i class="fas fa-user mr-1"></i>
                        <?php echo e($recommendation->autores->take(2)->pluck('nome')->implode(', ')); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recommendation->autores->count() > 2): ?>
                        <span class="text-gray-400"> +<?php echo e($recommendation->autores->count() - 2); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="flex items-center justify-between mt-3">
                        <span class="text-lg font-bold text-green-600">
                            € <?php echo e(number_format($recommendation->preco, 2, ',', '.')); ?>

                        </span>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recommendation->quantidade > 0): ?>
                        <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">
                            <i class="fas fa-check-circle"></i> Disponível
                        </span>
                        <?php else: ?>
                        <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded-full">
                            <i class="fas fa-times-circle"></i> Indisponível
                        </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($similarityScores[$recommendation->id]) && $similarityScores[$recommendation->id] > 0.3): ?>
                    <div class="mt-2 text-xs text-purple-600">
                        <i class="fas fa-chart-line mr-1"></i>
                        <?php echo e(round($similarityScores[$recommendation->id] * 100)); ?>% de similaridade
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </a>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    
    <div class="text-center mt-6">
        <a href="<?php echo e(route('livros.recommendations', $livro->id)); ?>"
            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
            <i class="fas fa-magic mr-2"></i>
            Ver mais recomendações inteligentes
        </a>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role === 'admin' && isset($historico) && $historico->count() > 0): ?>
<div class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden max-w-7xl mx-auto">
    <div class="px-6 py-4 bg-gray-50 border-b">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-history mr-2"></i> Histórico de Empréstimos
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Empréstimo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Devolução</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $historico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requisicao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo e($requisicao->user->name ?? 'Usuário não encontrado'); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo e(\Carbon\Carbon::parse($requisicao->data_inicio)->format('d/m/Y')); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo e(\Carbon\Carbon::parse($requisicao->data_fim)->format('d/m/Y')); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requisicao->status === 'aprovada'): ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aprovada</span>
                        <?php elseif($requisicao->status === 'pendente'): ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                        <?php else: ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejeitada</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<style>
    /* Estilos para o toast de mensagem */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    verificarStatusInscricao('<?php echo e($livro->id); ?>');
});

function verificarStatusInscricao(livroId) {
    fetch('/livros/' + livroId + '/check-subscription', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.is_subscribed) {
            document.getElementById('btn-notificar').classList.add('hidden');
            document.getElementById('btn-cancelar').classList.remove('hidden');
            document.getElementById('mensagem-notificacao').innerHTML = '<i class="fas fa-check-circle text-green-500 mr-1"></i> Você receberá um email quando o livro ficar disponível.';
            document.getElementById('mensagem-notificacao').classList.add('text-green-600');
        }
    })
    .catch(function(error) {
        console.error('Erro:', error);
    });
}

function solicitarNotificacao(livroId) {
    var btn = document.getElementById('btn-notificar');
    var mensagem = document.getElementById('mensagem-notificacao');

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processando...';

    fetch('/livros/' + livroId + '/notificar', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            document.getElementById('btn-notificar').classList.add('hidden');
            document.getElementById('btn-cancelar').classList.remove('hidden');
            mensagem.innerHTML = '<i class="fas fa-check-circle text-green-500 mr-1"></i> ' + data.message;
            mensagem.classList.add('text-green-600');
        } else {
            mensagem.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> ' + data.message;
            mensagem.classList.add('text-red-600');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-bell mr-2"></i> Notificar-me quando disponível';
        }
    })
    .catch(function(error) {
        console.error('Erro:', error);
        mensagem.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> Erro ao processar solicitação.';
        mensagem.classList.add('text-red-600');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-bell mr-2"></i> Notificar-me quando disponível';
    });
}

function cancelarNotificacao(livroId) {
    var btn = document.getElementById('btn-cancelar');
    var mensagem = document.getElementById('mensagem-notificacao');

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Cancelando...';

    fetch('/livros/' + livroId + '/cancelar-notificacao', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            document.getElementById('btn-notificar').classList.remove('hidden');
            document.getElementById('btn-cancelar').classList.add('hidden');
            mensagem.innerHTML = '<i class="fas fa-info-circle text-blue-500 mr-1"></i> ' + data.message;
            mensagem.classList.add('text-blue-600');
            setTimeout(function() {
                mensagem.innerHTML = '';
                mensagem.classList.remove('text-blue-600');
            }, 3000);
        } else {
            mensagem.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> ' + data.message;
            mensagem.classList.add('text-red-600');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-bell-slash mr-2"></i> Cancelar notificação';
        }
    })
    .catch(function(error) {
        console.error('Erro:', error);
        mensagem.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> Erro ao cancelar solicitação.';
        mensagem.classList.add('text-red-600');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-bell-slash mr-2"></i> Cancelar notificação';
    });
}

function mostrarMensagem(mensagem, tipo) {
    // Remove mensagem existente
    var msgExistente = document.querySelector('.toast-mensagem');
    if (msgExistente) {
        msgExistente.remove();
    }
    
    // Cria nova mensagem
    var div = document.createElement('div');
    div.className = 'toast-mensagem';
    div.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: ${tipo === 'success' ? '#22c55e' : '#ef4444'};
        color: white;
        padding: 15px 20px;
        z-index: 9999;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        font-size: 14px;
        font-weight: 500;
        animation: slideInRight 0.3s ease;
    `;
    div.textContent = mensagem;
    document.body.appendChild(div);
    
    // Remove após 3 segundos
    setTimeout(function() {
        div.style.animation = 'fadeOut 0.3s ease';
        setTimeout(function() {
            if (div && div.remove) {
                div.remove();
            }
        }, 300);
    }, 3000);
}

function adicionarAoCarrinho(livroId) {
    var token = document.querySelector('meta[name="csrf-token"]').content;
    
    // Desabilita o botão para evitar múltiplos cliques
    var btn = event.currentTarget;
    var originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Adicionando...';
    
    fetch('/carrinho/adicionar-ajax/' + livroId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(function(response) {
        if (!response.ok) {
            return response.json().then(function(data) {
                throw new Error(data.message || 'Erro ao adicionar ao carrinho');
            });
        }
        return response.json();
    })
    .then(function(data) {
        // Restaura o botão
        btn.disabled = false;
        btn.innerHTML = originalText;
        
        if (data.success) {
            // Mostra mensagem de sucesso
            mostrarMensagem(data.message, 'success');
            
            // Atualiza o contador do carrinho
            if (data.total_itens !== undefined) {
                var contador = document.getElementById('carrinho-contador');
                if (contador) {
                    contador.textContent = data.total_itens;
                    if (data.total_itens > 0) {
                        contador.classList.remove('hidden');
                    } else {
                        contador.classList.add('hidden');
                    }
                }
            }
            
            // Opcional: atualiza o ícone do carrinho ou outros elementos
            console.log('Item adicionado com sucesso!', data);
        } else {
            // Mostra mensagem de erro
            mostrarMensagem(data.message || 'Erro ao adicionar ao carrinho', 'error');
        }
    })
    .catch(function(error) {
        console.error('Erro detalhado:', error);
        // Restaura o botão
        btn.disabled = false;
        btn.innerHTML = originalText;
        // Mostra mensagem de erro
        mostrarMensagem(error.message || 'Erro ao adicionar ao carrinho. Tente novamente.', 'error');
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/livros-show.blade.php ENDPATH**/ ?>