@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Reviews Pendentes</h1>
        <a href="{{ route('reviews.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            Todas as Reviews
        </a>
    </div>

    @if($reviews->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded">
            <p>Nenhuma review pendente de moderação.</p>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($reviews as $review)
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-yellow-500">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Livro: {{ $review->livro->nome }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    Por: {{ $review->user->name }} ({{ $review->user->email }})
                                </p>
                                <p class="text-xs text-gray-500">
                                    Data: {{ $review->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            @if($review->rating)
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            @endif
                        </div>

                        <div class="mb-4">
                            <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">
                                {{ $review->review }}
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('reviews.show', $review->id) }}" 
                               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                <i class="fas fa-eye mr-2"></i>Ver Detalhes
                            </a>
                            <button type="button" 
                                    data-id="{{ $review->id }}"
                                    data-review="{{ str_replace(["\r\n", "\n", "\r", '"'], [' ', ' ', ' ', '\"'], $review->review) }}"
                                    class="moderate-btn bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                                <i class="fas fa-check-circle mr-2"></i>Moderar
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif
</div>

<div id="moderateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mb-4">
            <h3 class="text-lg font-bold text-gray-900">Moderar Review</h3>
        </div>
        
        <form id="moderateForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Review:</label>
                <p id="reviewText" class="text-gray-600 bg-gray-50 p-2 rounded text-sm"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Decisão:</label>
                <select id="statusSelect" name="status" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="">Selecione...</option>
                    <option value="ativo">Aprovar</option>
                    <option value="recusado">Recusar</option>
                </select>
            </div>

            <div id="justificativaDiv" class="mb-4 hidden">
                <label class="block text-gray-700 text-sm font-bold mb-2">Justificativa (para recusa):</label>
                <textarea name="justificativa" rows="3" class="w-full px-3 py-2 border rounded-lg" 
                          placeholder="Explique o motivo da recusa..."></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Confirmar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModerateModal(reviewId, reviewText) {
    const modal = document.getElementById('moderateModal');
    const form = document.getElementById('moderateForm');
    const reviewTextElement = document.getElementById('reviewText');
    
    form.action = '/reviews/' + reviewId + '/status';
    reviewTextElement.textContent = reviewText;
    modal.classList.remove('hidden');
}

function closeModal() {
    const modal = document.getElementById('moderateModal');
    modal.classList.add('hidden');
    
    const statusSelect = document.getElementById('statusSelect');
    const justificativaDiv = document.getElementById('justificativaDiv');
    const moderateForm = document.getElementById('moderateForm');
    
    if (statusSelect) statusSelect.value = '';
    if (justificativaDiv) justificativaDiv.classList.add('hidden');
    if (moderateForm) moderateForm.reset();
}

document.addEventListener('DOMContentLoaded', function() {
    const moderateButtons = document.querySelectorAll('.moderate-btn');
    moderateButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const reviewId = this.getAttribute('data-id');
            const reviewText = this.getAttribute('data-review');
            openModerateModal(reviewId, reviewText);
        });
    });
    
    const statusSelect = document.getElementById('statusSelect');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const justificativaDiv = document.getElementById('justificativaDiv');
            if (this.value === 'recusado') {
                justificativaDiv.classList.remove('hidden');
            } else {
                justificativaDiv.classList.add('hidden');
            }
        });
    }
});
</script>
@endsection