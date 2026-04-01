@component('mail::message')
# Status da sua Review

Olá {{ $review->user->name }},

Sua review para o livro **{{ $review->livro->nome }}** foi **{{ $status === 'ativo' ? 'APROVADA' : 'RECUSADA' }}**.

@if($status === 'ativo')
Sua review agora está publicada e visível para outros usuários na página do livro.

@component('mail::button', ['url' => route('livros.show', $review->livro->id)])
Ver Livro
@endcomponent
@else
@if($justificativa)
**Justificativa da recusa:**
{{ $justificativa }}
@endif

Sua review não foi publicada. Você pode enviar uma nova review seguindo as diretrizes da comunidade.

@component('mail::button', ['url' => route('requisicoes.show', $review->requisicao->id))]
Ver Requisição
@endcomponent
@endif

Obrigado pela sua contribuição,<br>
{{ config('app.name') }}
@endcomponent