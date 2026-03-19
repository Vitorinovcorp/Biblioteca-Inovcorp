<!DOCTYPE html>
<html>
<head>
    <title>Devolução Confirmada</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #10b981; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9fafb; }
        .book-info { background-color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .atraso { background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 10px; margin: 20px 0; }
        .no-atraso { background-color: #d1fae5; border-left: 4px solid #10b981; padding: 10px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Livro Devolvido</h1>
        </div>
        
        <div class="content">
            <p>Olá {{ $requisicao->user->name }},</p>
            
            <p>A devolução do livro foi confirmada com sucesso.</p>
            
            <div class="book-info">
                <h3>📖 Detalhes da Devolução</h3>
                
                @if($requisicao->livro->capa)
                    <div class="book-cover">
                        <img src="{{ asset('storage/' . $requisicao->livro->capa) }}" 
                             alt="Capa do livro" style="max-width: 200px;">
                    </div>
                @endif
                
                <p><strong>Livro:</strong> {{ $requisicao->livro->nome }}</p>
                
                <h4 class="mt-4">📅 Datas</h4>
                <p><strong>Data de Início:</strong> {{ $requisicao->data_inicio->format('d/m/Y') }}</p>
                <p><strong>Data de Fim Prevista:</strong> {{ $requisicao->data_fim->format('d/m/Y') }}</p>
                <p><strong>Data de Devolução Real:</strong> {{ $requisicao->data_devolucao_real->format('d/m/Y') }}</p>
                
                @if($requisicao->dias_atraso > 0)
                    <div class="atraso">
                        <p><strong>⚠️ Dias em atraso:</strong> {{ $requisicao->dias_atraso }} dia(s)</p>
                        @if($requisicao->observacoes_devolucao)
                            <p><strong>Observações:</strong> {{ $requisicao->observacoes_devolucao }}</p>
                        @endif
                    </div>
                @else
                    <div class="no-atraso">
                        <p>✅ Livro devolvido dentro do prazo. Obrigado!</p>
                    </div>
                @endif
            </div>
            
            <p>Obrigado por utilizar nossa biblioteca!</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Biblioteca. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>