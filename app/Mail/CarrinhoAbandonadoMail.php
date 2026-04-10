<?php

namespace App\Mail;

use App\Models\Carrinho;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CarrinhoAbandonadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $carrinho;

    public function __construct(Carrinho $carrinho)
    {
        $this->carrinho = $carrinho;
    }

    public function build()
    {
        return $this->subject('Você ainda tem itens no carrinho!')
                    ->markdown('emails.carrinho-abandonado');
    }
}