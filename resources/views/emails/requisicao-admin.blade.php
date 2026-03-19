<!DOCTYPE html>
<html>
<head>
    <title>Nova Requisição</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #2563eb; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9fafb; }
        .book-info { background-color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .book-cover { max-width: 200px; margin: 10px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
        .status-badge { background-color: #fbbf24; color: black; padding: 5px 10px; border-radius: 9999px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Nova Requisição Pendente</h1>
        </div>
        
        <div class="content">
            <p>Olá Administrador,</p>
            
            <p>Uma nova requisição foi feita e aguarda sua aprovação:</p>
            
            <div class="book-info">
                <h3>📖 Detalhes do Livro</h3>
                
                @if($requisicao->livro->capa)
                    <div class="book-cover">
                        <img src="{{ asset('storage/' . $requisicao->livro->capa) }}" 
                             alt="Capa do livro" style="max-width: 200px;">
                    </div>
                @endif
                
                <p><strong>Título:</strong> {{ $requisicao->livro->nome }}</p>
                <p><strong>ISBN:</strong> {{ $requisicao->livro->isbn }}</p>
                <p><strong>Autores:</strong> 
                    @foreach($requisicao->livro->autores as $autor)
                        {{ $autor->nome }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </p>
                
                <h4 class="mt-4">👤 Requisitante</h4>
                <p><strong>Nome:</strong> {{ $requisicao->user->name }}</p>
                <p><strong>Email:</strong> {{ $requisicao->user->email }}</p>
                <p><strong>Telefone:</strong> {{ $requisicao->user->telefone ?? 'Não informado' }}</p>
                
                @if($requisicao->user->foto)
                    <p><strong>Foto do Cidadão:</strong></p>
                    <img src="{{ asset('storage/' . $requisicao->user->foto) }}" 
                         alt="Foto do cidadão" style="max-width: 100px; border-radius: 50%;">
                @endif
                
                <h4 class="mt-4">📅 Período da Requisição</h4>
                <p><strong>Data de Início:</strong> {{ $requisicao->data_inicio->format('d/m/Y') }}</p>
                <p><strong>Data de Fim Prevista:</strong> {{ $requisicao->data_fim->format('d/m/Y') }}</p>
                
                @if($requisicao->observacoes)
                    <p><strong>Observações:</strong> {{ $requisicao->observacoes }}</p>
                @endif
                
                <p><strong>Status:</strong> <span class="status-badge">Pendente</span></p>
            </div>
            
            <p>Acesse o sistema para aprovar ou rejeitar esta requisição:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('requisicoes.show', $requisicao) }}" 
                   style="background-color: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;">
                    Ver Requisição
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Biblioteca. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>