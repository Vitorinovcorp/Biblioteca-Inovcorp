<?php

namespace App\Mail;

use App\Models\Livro;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LivroDisponivelMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $livro;

    public function __construct(User $user, Livro $livro)
    {
        $this->user = $user;
        $this->livro = $livro;
    }

    public function build()
    {
        return $this->subject('Livro Disponível para Empréstimo - ' . $this->livro->nome)
                    ->markdown('emails.livro-disponivel');
    }
}