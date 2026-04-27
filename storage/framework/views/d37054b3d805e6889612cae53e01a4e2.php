<div class="h-full flex flex-col bg-white">

    <!-- Cabeçalho -->
    <div class="p-4 border-b flex items-center justify-between bg-white">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold mr-3">
                <?php echo e(strtoupper(substr($sala->nome, 0, 1))); ?>

            </div>

            <div>
                <h2 class="font-bold text-gray-800"><?php echo e($sala->nome); ?></h2>
            </div>
        </div>

        <a href="<?php echo e(route('chat.index')); ?>"
           class="text-gray-500 hover:text-red-500 transition">
            ✕
        </a>
    </div>

    <!-- Mensagens -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-white"
         id="mensagens-container">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $mensagens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mensagem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'msg-'.e($mensagem->id).''; ?>wire:key="msg-<?php echo e($mensagem->id); ?>"
                 class="flex <?php echo e($mensagem->user_id == Auth::id() ? 'justify-end' : 'justify-start'); ?>">

                <div class="max-w-xs lg:max-w-md">

                    <div class="flex items-end space-x-2">

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mensagem->user_id != Auth::id()): ?>
                            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-xs font-bold">
                                <?php echo e(strtoupper(substr($mensagem->user->name, 0, 1))); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="<?php echo e($mensagem->user_id == Auth::id()
                                ? 'bg-blue-500 text-white'
                                : 'bg-gray-100 text-gray-800'); ?>

                            rounded-2xl px-4 py-2 shadow-sm">

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mensagem->user_id != Auth::id()): ?>
                                <p class="text-xs font-semibold mb-1 text-gray-600">
                                    <?php echo e($mensagem->user->name); ?>

                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <p><?php echo e($mensagem->conteudo); ?></p>

                            <p class="text-[11px] mt-1 text-right
                                <?php echo e($mensagem->user_id == Auth::id()
                                    ? 'text-blue-100'
                                    : 'text-gray-400'); ?>">
                                <?php echo e($mensagem->created_at->format('H:i')); ?>

                            </p>
                        </div>

                    </div>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="text-center text-gray-400 py-10">
                Nenhuma mensagem ainda.
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    <!-- Input -->
    <div class="p-4 border-t bg-white">
        <div class="flex items-center space-x-2">

            <input
                id="chatInput"
                type="text"
                wire:model="novaMensagem"
                wire:keydown.enter.prevent="enviarMensagem"
                autocomplete="off"
                placeholder="Digite sua mensagem..."
                class="flex-1 px-4 py-2 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">

            <button wire:click="enviarMensagem"
                    class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition">
                ➤
            </button>

        </div>
    </div>

</div>
<?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/livewire/chat/chat-room.blade.php ENDPATH**/ ?>