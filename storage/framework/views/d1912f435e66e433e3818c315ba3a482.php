

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Importar Livro da Google Books</h3>
                </div>

                <div class="card-body">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existe): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Este livro já existe na base de dados (pelo ISBN ou ID do Google).
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <?php $thumbnail = $mappedData['imagem_capa_url']; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thumbnail): ?>
                                <img src="<?php echo e($thumbnail); ?>" class="img-fluid rounded">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="col-md-9">
                            <h4><?php echo e($mappedData['nome']); ?></h4>
                            <p><strong>Autores:</strong> <?php echo e(implode(', ', $mappedData['autores_nomes'])); ?></p>
                            <p><strong>ISBN:</strong> <?php echo e($mappedData['isbn'] ?? 'Não disponível'); ?></p>
                            <p><strong>Publicado:</strong> <?php echo e($mappedData['published_date']); ?></p>
                            <p><strong>Páginas:</strong> <?php echo e($mappedData['page_count']); ?></p>
                        </div>
                    </div>

                    <form method="POST" action="<?php echo e(route('google-books.import', $googleBook['id'])); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="external_id" value="<?php echo e($googleBook['id']); ?>">

                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" 
                                   value="<?php echo e(old('isbn', $mappedData['isbn'])); ?>">
                            <small class="text-muted">Pode editar se necessário</small>
                        </div>

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Livro *</label>
                            <input type="text" class="form-control" id="nome" name="nome" 
                                   value="<?php echo e(old('nome', $mappedData['nome'])); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="bibliografia" class="form-label">Descrição</label>
                            <textarea class="form-control" id="bibliografia" name="bibliografia" rows="4"><?php echo e(old('bibliografia', $mappedData['bibliografia'])); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço *</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" step="0.01" class="form-control" 
                                       id="preco" name="preco" value="<?php echo e(old('preco')); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editora_id" class="form-label">Editora *</label>
                            <select class="form-control" id="editora_id" name="editora_id" required>
                                <option value="">Selecione uma editora</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $editoras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $editora): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($editora->id); ?>" <?php echo e(old('editora_id') == $editora->id ? 'selected' : ''); ?>>
                                        <?php echo e($editora->nome); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="autores" class="form-label">Autores</label>
                            <select class="form-control" id="autores" name="autores[]" multiple size="5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $autores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($autor->id); ?>" 
                                        <?php echo e(in_array($autor->nome, $mappedData['autores_nomes']) ? 'selected' : ''); ?>>
                                        <?php echo e($autor->nome); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                            <small class="text-muted">
                                Sugestões baseadas nos autores do Google Books. 
                                Se algum não existir, <a href="<?php echo e(route('autores.create')); ?>" target="_blank">crie-o primeiro</a>.
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('google-books.search')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Importar Livro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Vitor Ferreira\Herd\biblioteca-inovcorp\resources\views/google-books/import.blade.php ENDPATH**/ ?>