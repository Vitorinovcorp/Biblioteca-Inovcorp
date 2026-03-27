@extends('layouts.app')

@section('content')
<div class="px-6 py-4">
    <!-- Card de Busca -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="bg-white shadow-md rounded-lg">
            <div class="bg-white text-center py-4 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Pesquisar na Google Books</h3>
            </div>
            <div class="p-4">
                <form method="POST" action="{{ route('google-books.do-search') }}" id="searchForm">
                    @csrf
                    <div class="flex justify-center gap-2 text-gray-800">
                        <input type="text"
                            name="q"
                            id="search-input"
                            class="border rounded-lg p-2 w-full max-w-2xl text-center @error('q') border-red-500 @enderror"
                            placeholder="Digite título, autor ou ISBN"
                            value="{{ old('q', $query ?? '') }}"
                            required>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search"></i> Pesquisar
                        </button>
                    </div>
                    @error('q')
                    <div class="text-red-500 text-sm mt-2 text-center">{{ $message }}</div>
                    @enderror
                </form>
            </div>
        </div>
    </div>

    <!-- Resultado da Busca -->
    @if(isset($query) && $query)
    <div class="max-w-7xl mx-auto mb-4">
        <h4 class="text-lg font-semibold text-gray-700">
            Resultados para: "{{ $query }}"
        </h4>
    </div>
    @endif

    <!-- Grid de Livros - 3 colunas -->
    <div class="max-w-7xl mx-auto">
        <div id="livros-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @if(isset($results) && isset($results['items']) && count($results['items']) > 0)
                @foreach($results['items'] as $book)
                    @include('google-books.partials.book-card', ['book' => $book])
                @endforeach
            @elseif(isset($results) && isset($results['items']) && count($results['items']) === 0)
                <div class="col-span-3 text-center py-10 text-gray-500">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <p>Nenhum livro encontrado para "{{ $query ?? '' }}"</p>
                </div>
            @else
                <div class="col-span-3 text-center py-10">
                    <p class="text-gray-500 mb-4">Digite algo no campo de busca acima para encontrar livros!</p>
                    <p class="text-gray-400 text-sm">Exemplos: "José Saramago", "Harry Potter", "1984"</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Importação SIMPLES (sem Bootstrap) -->
<div id="importModalSimple" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div style="background: white; width: 90%; max-width: 800px; margin: 50px auto; border-radius: 8px; padding: 20px;">
        <div style="background: #2563eb; color: white; padding: 15px; margin: -20px -20px 20px -20px; border-radius: 8px 8px 0 0;">
            <h3 style="margin: 0;"><i class="fas fa-download"></i> Importar Livro</h3>
            <button onclick="document.getElementById('importModalSimple').style.display='none'" style="float: right; background: none; border: none; color: white; font-size: 20px;">×</button>
        </div>
        <form id="importFormSimple">
            @csrf
            <input type="hidden" name="volume_id" id="volume_id_simple">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="font-weight: bold;">Nome do Livro *</label>
                    <input type="text" id="nome_simple" name="nome" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                </div>
                <div>
                    <label style="font-weight: bold;">ISBN</label>
                    <input type="text" id="isbn_simple" name="isbn" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div>
                    <label style="font-weight: bold;">Preço *</label>
                    <input type="number" step="0.01" id="preco_simple" name="preco" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required placeholder="0.00">
                </div>
                <div>
                    <label style="font-weight: bold;">Quantidade *</label>
                    <input type="number" id="quantidade_simple" name="quantidade" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" value="1" required min="1">
                </div>
                <div>
                    <label style="font-weight: bold;">Editora *</label>
                    <select id="editora_id_simple" name="editora_id" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                        <option value="">Selecione uma editora</option>
                        @foreach($editoras ?? [] as $editora)
                        <option value="{{ $editora->id }}">{{ $editora->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-weight: bold;">Autores</label>
                    <select id="autores_simple" name="autores[]" multiple size="4" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        @foreach($autores ?? [] as $autor)
                        <option value="{{ $autor->id }}">{{ $autor->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="grid-column: span 2;">
                    <label style="font-weight: bold;">Descrição</label>
                    <textarea id="bibliografia_simple" name="bibliografia" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                </div>
            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="button" onclick="document.getElementById('importModalSimple').style.display='none'" style="background: #9ca3af; color: white; padding: 8px 16px; border: none; border-radius: 4px; margin-right: 10px;">Cancelar</button>
                <button type="submit" style="background: #2563eb; color: white; padding: 8px 16px; border: none; border-radius: 4px;">Importar Livro</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado - iniciando scripts');
    
    // Botões de importar
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.import-btn');
        if (btn) {
            e.preventDefault();
            console.log('Botão importar clicado!');
            
            // Pegar dados do botão
            const volumeId = btn.getAttribute('data-volume-id');
            const title = btn.getAttribute('data-title') || '';
            const authors = btn.getAttribute('data-authors') || '';
            const isbn = btn.getAttribute('data-isbn') || '';
            const description = btn.getAttribute('data-description') || '';
            
            console.log('Dados extraídos:', {volumeId, title, authors, isbn});
            
            // Preencher o modal simples
            document.getElementById('volume_id_simple').value = volumeId;
            document.getElementById('nome_simple').value = title;
            document.getElementById('isbn_simple').value = isbn;
            document.getElementById('bibliografia_simple').value = description;
            
            // Limpar campos
            document.getElementById('preco_simple').value = '';
            document.getElementById('quantidade_simple').value = '1';
            
            // Mostrar modal
            document.getElementById('importModalSimple').style.display = 'block';
        }
    });
    
    // Formulário de importação simples
    const importFormSimple = document.getElementById('importFormSimple');
    if (importFormSimple) {
        importFormSimple.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Formulário de importação enviado!');
            
            const preco = document.getElementById('preco_simple').value;
            const quantidade = document.getElementById('quantidade_simple').value;
            const editoraId = document.getElementById('editora_id_simple').value;
            
            if (!preco || preco <= 0) {
                alert('Por favor, informe um preço válido.');
                return;
            }
            
            if (!quantidade || quantidade < 1) {
                alert('Por favor, informe uma quantidade válida.');
                return;
            }
            
            if (!editoraId) {
                alert('Por favor, selecione uma editora.');
                return;
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importando...';
            submitBtn.disabled = true;
            
            const formData = new FormData(this);
            
            fetch('{{ route("google-books.import") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Resposta:', data);
                if (data.success) {
                    alert(data.message || 'Livro importado com sucesso!');
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        document.getElementById('importModalSimple').style.display = 'none';
                        importFormSimple.reset();
                    }
                } else {
                    alert('Erro: ' + (data.error || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao importar: ' + error.message);
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});
</script>


@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap não está carregado!');
        alert('Erro: Bootstrap não carregado. Atualize a página.');
        return;
    }
    
    console.log('Bootstrap carregado com sucesso!');
    
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('search-input');
    const livrosContainer = document.getElementById('livros-container');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const query = searchInput.value;
            if (!query) return;
            
            livrosContainer.innerHTML = '<div class="col-span-3 text-center py-10"><i class="fas fa-spinner fa-spin fa-2x text-blue-600"></i><p class="mt-2">Buscando livros...</p></div>';
            
            fetch('{{ route("google-books.do-search") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({q: query})
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    livrosContainer.innerHTML = data.html;
                    const resultadoTitulo = document.querySelector('.max-w-7xl.mx-auto.mb-4');
                    if (resultadoTitulo) {
                        resultadoTitulo.innerHTML = `<h4 class="text-lg font-semibold text-gray-700">Resultados para: "${query}"</h4>`;
                    }
                } else {
                    livrosContainer.innerHTML = '<div class="col-span-3 text-center py-10 text-gray-500">Nenhum livro encontrado.</div>';
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                livrosContainer.innerHTML = '<div class="col-span-3 text-center py-10 text-red-500">Erro ao buscar livros. Tente novamente.</div>';
            });
        });
    }
    
    const importModal = document.getElementById('importModal');
    let modalInstance = null;
    
    if (importModal) {
        modalInstance = new bootstrap.Modal(importModal);
        console.log('Modal inicializado com sucesso!');
    } else {
        console.error('Modal não encontrado!');
    }
    
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.import-btn');
        if (btn) {
            e.preventDefault();
            
            console.log('Botão importar clicado!');
            
            const volumeId = btn.getAttribute('data-volume-id');
            const title = btn.getAttribute('data-title') || '';
            const authors = btn.getAttribute('data-authors') || '';
            const isbn = btn.getAttribute('data-isbn') || '';
            const description = btn.getAttribute('data-description') || '';
            
            console.log('Dados do livro:', {volumeId, title, authors, isbn});
            
            const volumeIdField = document.getElementById('volume_id');
            const nomeField = document.getElementById('nome');
            const isbnField = document.getElementById('isbn');
            const bibliografiaField = document.getElementById('bibliografia');
            
            if (volumeIdField) volumeIdField.value = volumeId;
            if (nomeField) nomeField.value = title;
            if (isbnField) isbnField.value = isbn;
            if (bibliografiaField) bibliografiaField.value = description;
            
            const precoField = document.getElementById('preco');
            const quantidadeField = document.getElementById('quantidade');
            if (precoField) precoField.value = '';
            if (quantidadeField) quantidadeField.value = '1';
            
            const autorSelect = document.getElementById('autores');
            if (authors && autorSelect) {
                for (let option of autorSelect.options) {
                    option.selected = false;
                }
                
                const authorNames = authors.split(',').map(a => a.trim().toLowerCase());
                for (let option of autorSelect.options) {
                    if (authorNames.includes(option.text.toLowerCase())) {
                        option.selected = true;
                    }
                }
            }
            
            if (modalInstance) {
                modalInstance.show();
            } else {
                alert('Erro: Modal não pode ser aberto.');
            }
        }
    });
    
    const importForm = document.getElementById('importForm');
    
    if (importForm) {
        importForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Formulário de importação enviado!');
            
            const preco = document.getElementById('preco').value;
            const quantidade = document.getElementById('quantidade').value;
            const editoraId = document.getElementById('editora_id').value;
            
            if (!preco || preco <= 0) {
                alert('Por favor, informe um preço válido.');
                return;
            }
            
            if (!quantidade || quantidade < 1) {
                alert('Por favor, informe uma quantidade válida.');
                return;
            }
            
            if (!editoraId) {
                alert('Por favor, selecione uma editora.');
                return;
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importando...';
            submitBtn.disabled = true;
            
            const formData = new FormData(this);
            
            fetch('{{ route("google-books.import") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                console.log('Resposta status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Resposta data:', data);
                
                if (data.success) {
                    alert(data.message || 'Livro importado com sucesso!');
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        if (modalInstance) modalInstance.hide();
                        importForm.reset();
                    }
                } else {
                    alert('Erro: ' + (data.error || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Erro detalhado:', error);
                alert('Erro ao importar: ' + error.message);
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    } else {
        console.error('Formulário de importação não encontrado!');
    }
    
    setTimeout(function() {
        const botoes = document.querySelectorAll('.import-btn');
        console.log('Total de botões import-btn encontrados:', botoes.length);
        
        if (botoes.length === 0) {
            console.log('Verificando estrutura do container...');
            const container = document.getElementById('livros-container');
            if (container) {
                console.log('Container encontrado, HTML:', container.innerHTML.substring(0, 500));
            }
        }
    }, 1000);
});
</script>
@endpush