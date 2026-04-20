@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">Nova Requisição</h1>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('requisicoes.store') }}">
            @csrf

            <div class="mb-4">
                <label for="livro_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Livro <span class="text-red-500">*</span>
                </label>
                <select name="livro_id" id="livro_id" 
                        class="w-full rounded-md border-gray-300 text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('livro_id') border-red-500 @enderror"
                        required>
                    <option value="">Selecione um livro</option>
                    @foreach($livrosDisponiveis as $livro)
                        <option value="{{ $livro->id }}" {{ old('livro_id') == $livro->id ? 'selected' : '' }}
                                data-stock="{{ $livro->quantidade }}">
                            {{ $livro->nome }} - 
                            @foreach($livro->autores as $autor)
                                {{ $autor->nome }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                            ({{ $livro->editora->nome ?? 'Sem editora' }}) - 
                            <span class="text-green-600">Estoque: {{ $livro->quantidade }}</span>
                        </option>
                    @endforeach
                </select>
                @error('livro_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                @if($livrosDisponiveis->count() == 0)
                    <p class="text-yellow-500 text-sm mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Não há livros disponíveis para requisição no momento.
                    </p>
                @endif
            </div>

            <div class="mb-4">
                <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                    Data de Início <span class="text-red-500">*</span>
                </label>
                <input type="date" name="data_inicio" id="data_inicio" 
                       value="{{ old('data_inicio') }}" 
                       min="{{ date('Y-m-d') }}"
                       class="w-full rounded-md border-gray-300 text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('data_inicio') border-red-500 @enderror"
                       required>
                @error('data_inicio')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    A data de fim será automaticamente definida para 5 dias após a data de início.
                </p>
            </div>

            <div class="mb-4">
                <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                <textarea name="observacoes" id="observacoes" rows="3" 
                          class="w-full rounded-md border-gray-300 text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('observacoes') border-red-500 @enderror">{{ old('observacoes') }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('requisicoes.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Cancelar
                </a>
                <button type="submit" 
                        id="btn-submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Solicitar Requisição
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('data_inicio').addEventListener('change', function() {
        let dataInicio = new Date(this.value);
        let dataFim = new Date(dataInicio);
        dataFim.setDate(dataFim.getDate() + 5);
        
        console.log('Data de fim prevista: ' + dataFim.toLocaleDateString('pt-PT'));
    });
</script>
@endsection