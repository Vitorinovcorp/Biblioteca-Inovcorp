@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Importar Livro da Google Books</h3>
                </div>

                <div class="card-body">
                    @if($existe)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Este livro já existe na base de dados (pelo ISBN ou ID do Google).
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-3">
                            @php $thumbnail = $mappedData['imagem_capa_url']; @endphp
                            @if($thumbnail)
                                <img src="{{ $thumbnail }}" class="img-fluid rounded">
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h4>{{ $mappedData['nome'] }}</h4>
                            <p><strong>Autores:</strong> {{ implode(', ', $mappedData['autores_nomes']) }}</p>
                            <p><strong>ISBN:</strong> {{ $mappedData['isbn'] ?? 'Não disponível' }}</p>
                            <p><strong>Publicado:</strong> {{ $mappedData['published_date'] }}</p>
                            <p><strong>Páginas:</strong> {{ $mappedData['page_count'] }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('google-books.import', $googleBook['id']) }}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="external_id" value="{{ $googleBook['id'] }}">

                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" 
                                   value="{{ old('isbn', $mappedData['isbn']) }}">
                            <small class="text-muted">Pode editar se necessário</small>
                        </div>

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Livro *</label>
                            <input type="text" class="form-control" id="nome" name="nome" 
                                   value="{{ old('nome', $mappedData['nome']) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="bibliografia" class="form-label text-gray-800">Descrição</label>
                            <textarea class="form-control" id="bibliografia" name="bibliografia" rows="4">{{ old('bibliografia', $mappedData['bibliografia']) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="preco" class="form-label text-gray-800">Preço *</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" step="0.01" class="form-control" 
                                       id="preco" name="preco" value="{{ old('preco') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editora_id" class="form-label text-gray-800">Editora *</label>
                            <select class="form-control" id="editora_id" name="editora_id" required>
                                <option value="">Selecione uma editora</option>
                                @foreach($editoras as $editora)
                                    <option value="{{ $editora->id }}" {{ old('editora_id') == $editora->id ? 'selected' : '' }}>
                                        {{ $editora->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="autores" class="form-label text-gray-800">Autores</label>
                            <select class="form-control" id="autores" name="autores[]" multiple size="5">
                                @foreach($autores as $autor)
                                    <option value="{{ $autor->id }}" 
                                        {{ in_array($autor->nome, $mappedData['autores_nomes']) ? 'selected' : '' }}>
                                        {{ $autor->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                Sugestões baseadas nos autores do Google Books. 
                                Se algum não existir, <a href="{{ route('autores.create') }}" target="_blank">crie-o primeiro</a>.
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save text-gray-800"></i> Importar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection