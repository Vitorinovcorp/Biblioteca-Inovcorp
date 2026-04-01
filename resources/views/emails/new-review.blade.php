@component('mail::message')
# Nova Review Aguardando Moderação

Uma nova review foi submetida e aguarda sua moderação.

**Cidadão:** {{ $citizen->name }}<br>
**Email:** {{ $citizen->email }}<br>
**Livro:** {{ $review->livro->nome }}

**Review:**
{{ $review->review }}

@component('mail::button', ['url' => route('reviews.show', $review->id)])
Ver Detalhes da Review
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent