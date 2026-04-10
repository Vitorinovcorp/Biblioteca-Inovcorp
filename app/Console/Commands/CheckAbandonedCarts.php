<?php

namespace App\Console\Commands;

use App\Models\Carrinho;
use App\Mail\CarrinhoAbandonadoMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;

class CheckAbandonedCarts extends Command
{
    protected $signature = 'carts:check-abandoned';
    protected $description = 'Verifica carrinhos abandonados e envia emails';

    public function handle()
    {
        $carrinhos = Carrinho::where('status', 'aberto')
            ->where('updated_at', '<=', now()->subHour())
            ->with('user', 'itens.livro')
            ->get();

        foreach ($carrinhos as $carrinho) {
            if ($carrinho->itens->count() > 0) {
                try {
                    Mail::to($carrinho->user->email)->send(new CarrinhoAbandonadoMail($carrinho));
                    $this->info('Email enviado para: ' . $carrinho->user->email);
                } catch (\Exception $e) {
                    $this->error('Erro ao enviar email: ' . $e->getMessage());
                }
            }
        }

        $this->info('Processados ' . $carrinhos->count() . ' carrinhos abandonados.');
    }
}