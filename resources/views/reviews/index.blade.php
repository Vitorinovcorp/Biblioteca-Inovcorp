@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Todas as Reviews</h1>
        <a href="{{ route('reviews.pending') }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
            Reviews Pendentes
        </a>
    </div>

    @if($reviews->isEmpty())
        <div class="bg-gray-100 rounded-lg p-8 text-center">
            <p class="text-gray-500">Nenhuma review encontrada.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Livro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Review</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($reviews as $review)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $review->livro->nome }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $review->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $review->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600 max-w-md truncate">{{ $review->review }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($review->status === 'ativo')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Ativo</span>
                            @elseif($review->status === 'suspenso')
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Suspenso</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Recusado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $review->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('reviews.show', $review->id) }}" 
                               class="text-blue-500 hover:text-blue-700">
                                Ver Detalhes
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection