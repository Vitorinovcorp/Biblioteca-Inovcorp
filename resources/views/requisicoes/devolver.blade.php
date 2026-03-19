{{-- resources/views/requisicoes/devolver.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">Confirmar Devolução do Livro</h1>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-600">📖 Detalhes da Requisição</h2>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Livro:</p>
                <p class="font-medium text-gray-600">{{ $requisicao->livro->nome }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Requisitante:</p>
                <p class="font-medium text-gray-600">{{ $requisicao->user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Data de Início:</p>
                <p class="font-medium text-gray-600">{{ $requisicao->data_inicio->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Data de Fim Prevista:</p>
                <p class="font-medium text-gray-600">{{ $requisicao->data_fim->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('requisicoes.confirmar-devolucao', $requisicao) }}">
            @csrf
            
            <div class="mb-4">
                <label for="data_devolucao_real" class="block text-sm font-medium text-gray-700 mb-2">
                    Data de Devolução Real <span class="text-red-500 text-gray-600">*</span>
                </label>
                <input type="date" 
                       name="data_devolucao_real" 
                       id="data_devolucao_real" 
                       value="{{ old('data_devolucao_real', date('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm text-gray-600 focus:border-blue-500 focus:ring-blue-500 @error('data_devolucao_real') border-red-500 @enderror"
                       required>
                @error('data_devolucao_real')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="observacoes_devolucao" class="block text-sm font-medium text-gray-700 mb-2">
                    Observações da Devolução
                </label>
                <textarea name="observacoes_devolucao" 
                          id="observacoes_devolucao" 
                          rows="3"
                          class="w-full rounded-md border-gray-300 shadow-sm text-gray-600 focus:border-blue-500 focus:ring-blue-500 @error('observacoes_devolucao') border-red-500 @enderror"
                          placeholder="Ex: Livro em bom estado, pequena avaria na capa, etc...">{{ old('observacoes_devolucao') }}</textarea>
                @error('observacoes_devolucao')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 p-4 rounded-md mb-4">
                <p class="text-sm text-blue-800">
                    <strong>⚠️ Informação:</strong> Os dias de atraso serão calculados automaticamente com base na data de devolução informada.
                </p>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('requisicoes.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Confirmar Devolução
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('data_devolucao_real').addEventListener('change', function() {
    let dataDevolucao = new Date(this.value);
    let dataFimPrevista = new Date('{{ $requisicao->data_fim->format("Y-m-d") }}');
    if (dataDevolucao > dataFimPrevista) {
        let diffTime = dataDevolucao - dataFimPrevista;
        let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        alert(`Atenção: Esta devolução está ${diffDays} dia(s) em atraso.`);
    }
});
</script>
@endsection