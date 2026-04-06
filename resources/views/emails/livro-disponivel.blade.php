<x-mail::message>
# Olá {{ $user->name }}!

O livro **{{ $livro->nome }}** que você estava aguardando já está disponível para empréstimo na biblioteca.

## Detalhes do Livro

- **Autor(es):** {{ $livro->autores->pluck('nome')->implode(', ') ?: 'Não informado' }}
- **Editora:** {{ $livro->editora->nome ?? 'Não informada' }}
- **Preço:** € {{ number_format($livro->preco, 2, ',', '.') }}

<x-mail::button :url="route('livros.show', $livro->id)" color="green">
📖 Solicitar Empréstimo
</x-mail::button>

Não perca tempo! O livro está disponível por tempo limitado.

**Atenção:** Este é um aviso automático. Por favor, não responda este email.

Atenciosamente,<br>
Equipe da Biblioteca
</x-mail::message>