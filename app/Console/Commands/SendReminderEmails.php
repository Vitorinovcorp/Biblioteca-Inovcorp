<?php

namespace App\Console\Commands;

use App\Models\Requisicao;
use App\Mail\ReminderDevolucaoMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendReminderEmails extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Envia emails de lembrete para devoluções no dia seguinte';

    public function handle()
    {
        $this->info('Procurando requisições com devolução amanhã...');
        
        $requisicoes = Requisicao::with('user', 'livro')
            ->where('status', 'aprovada')
            ->whereDate('data_fim', Carbon::tomorrow()->toDateString())
            ->get();
        
        $count = 0;
        
        if ($requisicoes->isEmpty()) {
            $this->info('Nenhuma requisição encontrada para amanhã.');
            return Command::SUCCESS;
        }
        
        foreach ($requisicoes as $requisicao) {
            try {
                Mail::to($requisicao->user->email)->send(new ReminderDevolucaoMail($requisicao));
                $count++;
                $this->info("✅ Email enviado para: {$requisicao->user->email} - Livro: {$requisicao->livro->nome}");
                Log::info("Reminder enviado para requisição ID: {$requisicao->id}");
            } catch (\Exception $e) {
                $this->error("❌ Erro ao enviar email para {$requisicao->user->email}: " . $e->getMessage());
                Log::error("Erro no reminder: " . $e->getMessage());
            }
            
            usleep(500000);
        }
        
        $this->info("🎉 Total de {$count} lembretes enviados com sucesso!");
        
        return Command::SUCCESS;
    }
}