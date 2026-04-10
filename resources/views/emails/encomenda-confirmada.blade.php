<x-mail::message>
# Olá {{ $encomenda->user->name }}!

Sua encomenda **{{ $encomenda->numero_encomenda }}** foi confirmada com sucesso!

## Detalhes da Encomenda

**Data:** {{ $encomenda->created_at->format('d/m/Y H:i') }}
**Total:** € {{ number_format($encomenda->total, 2, ',', '.') }}

### Itens Comprados:
@foreach($encomenda->itens as $item)
- **{{ $item->quantidade }}x** {{ $item->livro->nome }} - € {{ number_format($item->preco_unitario * $item->quantidade, 2, ',', '.') }}
@endforeach

### Morada de Entrega:
{{ $encomenda->morada_entrega }}
{{ $encomenda->codigo_postal }}, {{ $encomenda->cidade }}
@if($encomenda->telefone)
Tel: {{ $encomenda->telefone }}
@endif

<x-mail::button :url="route('encomendas.show', $encomenda->id)" color="green">
Ver Detalhes da Encomenda
</x-mail::button>

Obrigado pela sua compra!

Atenciosamente,<br>
Equipe da Biblioteca
</x-mail::message>