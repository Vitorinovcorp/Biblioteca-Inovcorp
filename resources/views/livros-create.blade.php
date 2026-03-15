@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Cadastrar Novo Livro</h3>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('livros.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN *</label>
                            <input type="text" class="form-control @error('isbn') is-invalid @enderror" 
                                   id="isbn" name="isbn" value="{{ old('isbn') }}" required>
                            @error('isbn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Livro *</label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                   id="nome" name="nome" value="{{ old('nome') }}" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bibliografia" class="form-label">Bibliografia</label>
                            <textarea class="form-control @error('bibliografia') is-invalid @enderror" 
                                      id="bibliografia" name="bibliografia" rows="4">{{ old('bibliografia') }}</textarea>
                            @error('bibliografia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço *</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" step="0.01" class="form-control @error('preco') is-invalid @enderror" 
                                       id="preco" name="preco" value="{{ old('preco') }}" required>
                            </div>
                            @error('preco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="editora_id" class="form-label">Editora *</label>
                            <select class="form-control @error('editora_id') is-invalid @enderror" 
                                    id="editora_id" name="editora_id" required>
                                <option value="">Selecione uma editora</option>
                                @foreach($editoras as $editora)
                                    <option value="{{ $editora->id }}" {{ old('editora_id') == $editora->id ? 'selected' : '' }}>
                                        {{ $editora->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('editora_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="autores" class="form-label">Autores</label>
                            <select class="form-control @error('autores') is-invalid @enderror" 
                                    id="autores" name="autores[]" multiple size="5">
                                @foreach($autores as $autor)
                                    <option value="{{ $autor->id }}" {{ in_array($autor->id, old('autores', [])) ? 'selected' : '' }}>
                                        {{ $autor->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Segure Ctrl para selecionar múltiplos autores</small>
                            @error('autores')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="imagem_capa" class="form-label">Imagem da Capa</label>
                            <input type="file" class="form-control @error('imagem_capa') is-invalid @enderror" 
                                   id="imagem_capa" name="imagem_capa" accept="image/*">
                            <small class="text-muted">Formatos aceitos: JPEG, PNG, JPG, GIF (max. 2MB)</small>
                            @error('imagem_capa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="preview_capa" 
                                       name="preview_capa" value="1">
                                <label class="form-check-label" for="preview_capa">
                                    Mostrar prévia da imagem após upload
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('livros.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cadastrar Livro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('imagem_capa').addEventListener('change', function(e) {
        if (document.getElementById('preview_capa').checked) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.style.maxWidth = '200px';
                    preview.style.maxHeight = '200px';
                    preview.style.marginTop = '10px';
                    preview.style.borderRadius = '5px';
                    
                    const oldPreview = document.getElementById('preview');
                    if (oldPreview) {
                        oldPreview.remove();
                    }
                    
                    preview.id = 'preview';
                    e.target.parentNode.appendChild(preview);
                }
                reader.readAsDataURL(file);
            }
        }
    });
</script>
@endpush
@endsection