@if($livro->reviewsAtivas()->count() > 0)
<div class="mt-8">
    <h3 class="text-xl font-bold mb-4">Avaliações dos Leitores</h3>
    <div class="space-y-4">
        @foreach($livro->reviewsAtivas()->with('user')->get() as $review)
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <span class="font-semibold">{{ $review->user->name }}</span>
                    <span class="text-sm text-gray-500 ml-2">{{ $review->created_at->format('d/m/Y') }}</span>
                </div>
                @if($review->rating)
                <div class="flex">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                    @endfor
                </div>
                @endif
            </div>
            <p class="text-gray-700">{{ $review->review }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif