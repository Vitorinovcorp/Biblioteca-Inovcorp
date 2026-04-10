@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Minhas Compras</h1>

    @if($encomendas->count() > 0)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº Encomenda</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($encomendas as $encomenda)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $encomenda->numero_encomenda }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $encomenda->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                € {{ number_format($encomenda->total, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($encomenda->status_pagamento === 'pago')
                                    <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Pago
                                    </span>
                                @elseif($encomenda->status_pagamento === 'pendente')
                                    <span class="px-2 inline-flex items-center  text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Pendente
                                    </span>
                                @else
                                    <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Falhou
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('encomendas.show', $encomenda->id) }}" 
                                   class="text-purple-600 hover:text-purple-900">
                                    <i class="fas fa-eye mr-1"></i> Ver Detalhes
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $encomendas->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <i class="fas fa-shopping-bag fa-4x text-gray-400 mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-600 mb-2">Nenhuma compra encontrada</h2>
            <p class="text-gray-500 mb-6">Você ainda não realizou nenhuma compra.</p>
            <a href="{{ route('livros.index') }}" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                <i class="fas fa-book mr-2"></i> Ver Livros Disponíveis
            </a>
        </div>
    @endif
</div>
@endsection